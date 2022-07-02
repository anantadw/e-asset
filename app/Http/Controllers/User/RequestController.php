<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\DetailTransaction;
use App\Models\Item;
use App\Models\ItemDetail;
use App\Models\OutgoingTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index()
    {
        return view('user.request', [
            'link' => 'Request',
            'items' => Item::with('category')->get(),
            'total_cart' => Cart::where('user_id', session()->get('user_id'))->get()->count()
        ]);
    }

    public function request(Item $item)
    {
        return view('user.request-item', [
            'link' => 'Request',
            'item_name' => $item->name,
            'item_slug' => $item->slug,
            'item_details' => ItemDetail::where('item_id', $item->id)->get(),
            'total_cart' => Cart::where('user_id', session()->get('user_id'))->get()->count()
        ]);
    }

    public function addToCart($slug, Request $request)
    {
        $item_detail = ItemDetail::find($request->id);
        if ($item_detail->status !== '1') {
            return response()->json([
                'status' => false,
                'text' => 'Barang sedang tidak dapat dipinjam!',
            ]);
        } else {
            $result = DB::transaction(function () use ($item_detail, $request) {
                $cart = new Cart;
                $cart->user_id = session()->get('user_id');
                $cart->item_detail_id = $request->id;

                $item_detail->status = 2;

                if ($cart->save() && $item_detail->save()) {
                    return true;
                }
            });
            if ($result) {
                return response()->json([
                    'status' => true,
                    'text' => 'Barang ditambah ke keranjang!',
                    'redirect' => route('user-request-item', ['item' => $slug])
                ]);
            }
        }
    }

    public function showCart()
    {
        return view('user.request-cart', [
            'link' => 'Request',
            'carts' => Cart::with('itemDetail')->where('user_id', session()->get('user_id'))->get(),
            'user_unique_code' => User::find(session()->get('user_id'))->unique_code
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $cart = Cart::find($request->id);
            $item_detail = ItemDetail::find($cart->item_detail_id);

            $item_detail->status = 1;

            if ($cart->delete() && $item_detail->save()) {
                return true;
            }
        });
        if ($result) {
            return response()->json([
                'status' => true,
                'text' => 'Barang dihapus dari keranjang!',
                'redirect' => route('user-request-cart')
            ]);
        }
    }

    public function store(Request $request)
    {
        if (empty($request->except('_token'))) {
            return response()->json([
                'status' => false,
                'text' => 'Mohon pilih barang terlebih dahulu!',
            ]);
        } else {
            $result = DB::transaction(function () use ($request) {
                $outgoing_transaction = new OutgoingTransaction;
                $outgoing_transaction->user_id = session()->get('user_id');
                $outgoing_transaction->status = 1;
                $outgoing_transaction->invoice = 'INV-' . rand(100000, 999999);
                $outgoing_transaction->description = 'Menunggu persetujuan';
                $outgoing_transaction->items_are_scanned = false;

                if ($outgoing_transaction->save()) {
                    $detail_transaction = new DetailTransaction;
                    foreach ($request->carts as $cart) {
                        $data[] = [
                            'transaction_id' => $outgoing_transaction->id,
                            'item_detail_id' => $cart,
                            'is_scanned' => 0,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                    }

                    if ($detail_transaction::insert($data)) {
                        $cart = Cart::where('user_id', session()->get('user_id'));

                        if ($cart->delete()) {
                            return true;
                        }
                    }
                }
            });
            if ($result) {
                return response()->json([
                    'status' => true,
                    'text' => 'Pesanan dibuat!',
                    'redirect' => route('user-history')
                ]);
            }
        }
    }
}
