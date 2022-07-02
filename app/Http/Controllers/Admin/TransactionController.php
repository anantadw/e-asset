<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailTransaction;
use App\Models\IncomingTransaction;
use App\Models\ItemDetail;
use App\Models\OutgoingTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        return view('admin.incoming-transaction', [
            'link' => 'TransactionIn',
            'transactions' => IncomingTransaction::with(['user', 'item', 'item.category'])->latest()->get(),
        ]);
    }

    public function pending()
    {
        return view('admin.outgoing-transaction.pending', [
            'link' => 'TransactionOut',
            'transactions' => OutgoingTransaction::with(['detailTransactions', 'user', 'detailTransactions.itemDetail'])->where('status', '1')->get()
        ]);
    }

    public function approve(Request $request)
    {
        $outgoing_transaction = OutgoingTransaction::find($request->id);
        $outgoing_transaction->status = 2;
        $outgoing_transaction->description = 'Silakan ambil barang';

        if ($outgoing_transaction->save()) {
            return response()->json([
                'status' => true,
                'text' => 'Pesanan disetujui!',
                'redirect' => route('admin-outgoing-transaction-approved')
            ]);
        }
    }

    public function reject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->toArray()
            ]);
        } else {
            $result = DB::transaction(function () use ($request) {
                $outgoing_transaction = OutgoingTransaction::find($request->id);
                $outgoing_transaction->status = 5;
                $outgoing_transaction->description = $request->description;

                $detailTransactions = $outgoing_transaction->detailTransactions;
                foreach ($detailTransactions as $detailTransaction) {
                    $item_detail = ItemDetail::find($detailTransaction->item_detail_id);
                    $item_detail->status = 1;
                    $item_detail->save();
                }

                if ($outgoing_transaction->save()) {
                    return true;
                }
            });
            if ($result) {
                return response()->json([
                    'status' => true,
                    'text' => 'Pesanan ditolak!',
                    'redirect' => route('admin-outgoing-transaction-rejected')
                ]);
            }
        }
    }

    public function approved()
    {
        return view('admin.outgoing-transaction.approved', [
            'link' => 'TransactionOut',
            'transactions' => OutgoingTransaction::with(['detailTransactions', 'user', 'detailTransactions.itemDetail'])->where('status', '2')->get()
        ]);
    }

    public function scan($action, Request $request)
    {
        if ($action === 'item') {
            $validator = Validator::make($request->all(), [
                'item_unique_code' => 'required|digits:10'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'validator',
                    'errors' => $validator->errors()->toArray()
                ]);
            } else {
                $item_detail = ItemDetail::firstWhere('unique_code', $request->item_unique_code);
                if ($item_detail) {
                    if ($item_detail->id == $request->item_id) {
                        $detail_transaction = DetailTransaction::where('transaction_id', $request->item_transaction_id)->where('item_detail_id', $request->item_id);
                        if ($detail_transaction->update(['is_scanned' => true])) {
                            return response()->json([
                                'status' => true,
                                'text' => 'Barang teridentifikasi!',
                            ]);
                        }
                    } else {
                        return response()->json([
                            'status' => false,
                            'text' => 'Kode unik barang tidak sesuai!'
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'text' => 'Barang tidak ditemukan!'
                    ]);
                }
            }
        } else if ($action === 'user') {
            $validator = Validator::make($request->all(), [
                'user_unique_code' => 'required|digits:9'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'validator',
                    'errors' => $validator->errors()->toArray()
                ]);
            } else {
                $transaction = OutgoingTransaction::find($request->user_transaction_id);
                if ($transaction->items_are_scanned === 0) {
                    return response()->json([
                        'status' => false,
                        'text' => 'Mohon pindai barang telebih dahulu!'
                    ]);
                } else {
                    $user = User::firstWhere('unique_code', $request->user_unique_code);
                    if ($user) {
                        if ($user->id == $request->user_id) {
                            $result = DB::transaction(function () use ($request) {
                                $outgoing_transaction = OutgoingTransaction::find($request->user_transaction_id);
                                $outgoing_transaction->status = 3;
                                $outgoing_transaction->description = 'Sedang dipinjam';

                                if ($outgoing_transaction->save() && DetailTransaction::where('transaction_id', $request->user_transaction_id)->update(['is_scanned' => false])) {
                                    return true;
                                }
                            });
                            if ($result) {
                                return response()->json([
                                    'status' => true,
                                    'text' => 'Pengguna teridentifikasi!',
                                    'redirect' => route('admin-outgoing-transaction-ongoing')
                                ]);
                            }
                        } else {
                            return response()->json([
                                'status' => false,
                                'text' => 'Kode unik pengguna tidak sesuai!'
                            ]);
                        }
                    } else {
                        return response()->json([
                            'status' => false,
                            'text' => 'Pengguna tidak ditemukan!'
                        ]);
                    }
                }
            }
        } else if ($action === 'invoice') {
            $validator = Validator::make($request->all(), [
                'invoice' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'validator',
                    'errors' => $validator->errors()->toArray()
                ]);
            } else {
                $transaction = OutgoingTransaction::find($request->invoice_transaction_id);
                if ($transaction->items_are_scanned === 0) {
                    return response()->json([
                        'status' => false,
                        'text' => 'Mohon pindai barang telebih dahulu!'
                    ]);
                } else {
                    $invoice = OutgoingTransaction::firstwhere('invoice', $request->invoice);
                    if ($invoice) {
                        if ($invoice->id == $transaction->id) {
                            $result = DB::transaction(function () use ($invoice) {
                                $invoice->status = 4;
                                $invoice->description = 'Sudah dikembalikan';

                                $data = [];
                                foreach ($invoice->detailTransactions as $detail_transaction) {
                                    $data[] = $detail_transaction->item_detail_id;
                                }

                                if ($invoice->save() && ItemDetail::whereIn('id', $data)->update(['status' => 1]) && DetailTransaction::where('transaction_id', $invoice->id)->update(['is_scanned' => false])) {
                                    return true;
                                }
                            });
                            if ($result) {
                                return response()->json([
                                    'status' => true,
                                    'text' => 'Faktur teridentifikasi!',
                                    'redirect' => route('admin-outgoing-transaction-completed')
                                ]);
                            }
                        } else {
                            return response()->json([
                                'status' => false,
                                'text' => 'Faktur tidak sesuai!'
                            ]);
                        }
                    } else {
                        return response()->json([
                            'status' => false,
                            'text' => 'Faktur tidak ditemukan!'
                        ]);
                    }
                }
            }
        }
    }

    public function ongoing()
    {
        return view('admin.outgoing-transaction.ongoing', [
            'link' => 'TransactionOut',
            'transactions' => OutgoingTransaction::with('detailTransactions')->where('status', '3')->get()
        ]);
    }

    public function printInvoice(Request $request)
    {
        $outgoing_transaction = OutgoingTransaction::find($request->id);
        $code = explode('-', $outgoing_transaction->invoice);
        $count = $outgoing_transaction->detailTransactions->count();
        $html = "<barcode code='$code[1]' type='C128A' size='1.5' height='1' style='padding: 1mm; margin: 0;' />";

        $mpdf = new \Mpdf\Mpdf([
            'margin_left' => 15,
            'margin_top' => 15,
            'margin_right' => 15,
            'margin_bottom' => 15,
        ]);

        $mpdf->AddPage();
        // header
        $mpdf->SetFont('', 'B', 16);
        $mpdf->WriteCell(22, 10, 'E-Asset');
        $mpdf->WriteCell(100);
        $mpdf->WriteCell(58, 10, 'Faktur: ' . $outgoing_transaction->invoice, 0, 0, 'R');
        $mpdf->Ln(20);
        // barcode
        $mpdf->WriteCell(55);
        $mpdf->WriteHTML($html);
        $mpdf->Ln(10);
        //identity
        $mpdf->SetFont('', '', 14);
        $mpdf->WriteCell(50, 10, 'Nama');
        $mpdf->WriteCell(5, 10, ':');
        $mpdf->WriteCell(0, 10, $outgoing_transaction->user->name);
        $mpdf->Ln(10);
        $mpdf->WriteCell(50, 10, 'Nomor Induk');
        $mpdf->WriteCell(5, 10, ':');
        $mpdf->WriteCell(0, 10, $outgoing_transaction->user->unique_code);
        $mpdf->Ln(10);
        $mpdf->WriteCell(50, 10, 'Jumlah Barang');
        $mpdf->WriteCell(5, 10, ':');
        $mpdf->WriteCell(0, 10, "$count");
        $mpdf->Ln(10);
        $mpdf->WriteCell(50, 10, 'Admin');
        $mpdf->WriteCell(5, 10, ':');
        $mpdf->WriteCell(0, 10, session('user_name'));
        $mpdf->Ln(20);
        // items
        $mpdf->SetFont('', 'B', 14);
        $mpdf->WriteCell(0, 10, 'Data Peminjaman Barang');
        $mpdf->Ln();
        $mpdf->SetFont('', '', 14);
        $mpdf->WriteCell(30, 10, 'Nomor', 1, 0, 'C');
        $mpdf->WriteCell(110, 10, 'Nama Barang', 1, 0, 'C');
        $mpdf->WriteCell(40, 10, 'Kode Barang', 1, 0, 'C');
        $mpdf->Ln();
        foreach ($outgoing_transaction->detailTransactions as $no => $value) {
            $num = ++$no;
            $item = $value->itemDetail->codename;
            $item_code = $value->itemDetail->unique_code;
            $mpdf->WriteCell(30, 10, "$num", 1, 0, 'C');
            $mpdf->WriteCell(110, 10, "$item", 1, 0, 'C');
            $mpdf->WriteCell(40, 10, "$item_code", 1, 0, 'C');
            $mpdf->Ln();
        }
        $mpdf->Output('Faktur', 'I');
    }

    public function completed(Request $request)
    {
        if ($request->submit === 'search') {
            return view('admin.outgoing-transaction.completed', [
                'link' => 'TransactionOut',
                'transactions' => OutgoingTransaction::with('detailTransactions')->where('status', '4')->whereBetween('updated_at', [$request->start_date, $request->end_date])->get()
            ]);
        }

        return view('admin.outgoing-transaction.completed', [
            'link' => 'TransactionOut',
            'transactions' => OutgoingTransaction::with('detailTransactions')->where('status', '4')->latest('updated_at')->get()
        ]);
    }

    public function rejected()
    {
        return view('admin.outgoing-transaction.rejected', [
            'link' => 'TransactionOut',
            'transactions' => OutgoingTransaction::with(['detailTransactions', 'user', 'detailTransactions.itemDetail'])->where('status', '5')->latest('updated_at')->get()
        ]);
    }
}
