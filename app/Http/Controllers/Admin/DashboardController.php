<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\IncomingTransaction;
use App\Models\Item;
use App\Models\ItemDetail;
use App\Models\User;
use App\Models\OutgoingTransaction;
use App\Exports\ItemsExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.index', [
            'link' => 'Dashboard',
            'items' => Item::with('category', 'user')->get(),
            'total_items' => ItemDetail::count(),
            'total_users' => User::where('is_admin', false)->get()->count(),
            'incoming_transactions' => IncomingTransaction::count(),
            'outgoing_transactions' => OutgoingTransaction::count()
        ]);
    }

    public function show(Item $item)
    {
        return view('admin.item-detail', [
            'link' => 'Dashboard',
            'item' => $item,
            'item_details' => $item->itemDetails,
            'broken_items' => ItemDetail::where(['item_id' => $item->id, 'status' => '3'])->get()
        ]);
    }

    public function updateStatus($slug, Request $request)
    {
        $item_detail = ItemDetail::find($request->id);

        if ($item_detail->status === '1') {
            $item_detail->status = 3;
        } else {
            $item_detail->status = 1;
        }

        if ($item_detail->save()) {
            return response()->json([
                'status' => true,
            ]);
        }
    }

    public function delete(Request $request)
    {
        DB::transaction(function () use ($request) {
            $item = Item::find($request->id);

            $incoming_transaction = new IncomingTransaction;
            $incoming_transaction->admin_name = session()->get('user_name');
            $incoming_transaction->item_name = $item->name;
            $incoming_transaction->item_category = $item->category->name;
            $incoming_transaction->item_stock = $item->stock;
            $incoming_transaction->status = 3;

            if ($incoming_transaction->save() && $item->delete()) {
                return response()->json([
                    'status' => true,
                ]);
            }
        });
    }

    // public function edit(Item $item)
    // {
    //     return view('admin.item-edit', [
    //         'link' => 'Dashboard',
    //         'item' => $item,
    //         'categories' => Category::all()
    //     ]);
    // }

    // public function update(Item $item, Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'category' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'errors' => $validator->errors()->toArray()
    //         ]);
    //     } else {
    //         $item->category_id = $request->category;
    //     }
    // }

    public function export()
    {
        $date = Carbon::now()->format('d-m-Y');
        return Excel::download(new ItemsExport, "Laporan_Barang_$date.xlsx");
    }
}
