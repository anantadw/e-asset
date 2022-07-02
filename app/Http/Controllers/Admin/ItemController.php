<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\IncomingTransaction;
use App\Models\Item;
use App\Models\ItemDetail;
use App\Models\User;
use App\Imports\ItemsImport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index()
    {
        return view('admin.item-add', [
            'link' => 'ItemAdd',
            'categories' => Category::all()
        ]);
    }

    public function downloadTemplate()
    {
        $filename = 'E-Asset Templat Excel.xlsx';
        $path = storage_path('app/public/' . $filename);
        return response()->download($path);
    }

    public function storeItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admin_id' => 'required',
            'name' => 'bail|required|unique:items,name',
            'category' => 'required',
            'stock' => 'bail|required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->toArray()
            ]);
        } else {
            DB::transaction(function () use ($request) {
                $item = new Item;
                $item->name = $request->name;
                $item->slug = Str::of($request->name)->slug();
                $item->category_id = $request->category;
                $item->stock = $request->stock;
                $item->admin_id = $request->admin_id;

                if ($item->save()) {
                    $item_detail = new ItemDetail;
                    for ($i = 1; $i <= $item->stock; $i++) {
                        $data[] = [
                            'item_id' => $item->id,
                            'codename' => $item->name . ' ' . $i,
                            'status' => 1,
                            'unique_code' => rand(1000000000, 9999999999),
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                    }

                    $incoming_transaction = new IncomingTransaction;
                    $incoming_transaction->admin_name = User::find($request->admin_id)->name;
                    $incoming_transaction->item_name = $request->name;
                    $incoming_transaction->item_category = Category::find($request->category)->name;
                    $incoming_transaction->item_stock = $request->stock;
                    $incoming_transaction->status = 1;

                    if ($item_detail::insert($data) && $incoming_transaction->save()) {
                        return response()->json([
                            'status' => true,
                            'redirect' => route('admin-index')
                        ]);
                    }
                }
            });
        }
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fileimport' => 'bail|required|file|mimes:xlsx',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin-item-add')->with('failed', $validator->errors()->first('fileimport'));
        } else {
            $rows = Excel::toArray(new ItemsImport, $request->file('fileimport'), null, \Maatwebsite\Excel\Excel::XLSX);

            // check if item is existed or category is not existed
            foreach ($rows[0] as $data) {
                $item = Item::firstWhere('name', $data['nama_barang']);
                $category = Category::firstWhere('name', $data['kategori']);
                if ($item) {
                    return redirect()->route('admin-item-add')->with('failed', 'Salah satu barang atau lebih sudah terdaftar!');
                } else if (!$category) {
                    return redirect()->route('admin-item-add')->with('failed', 'Salah satu kategori atau lebih tidak terdaftar!');
                }
            }

            // insert datas
            $inserted = 0;
            DB::transaction(function () use ($rows, $inserted) {
                // loop through each row and insert the data
                foreach ($rows[0] as $data) {
                    $category = Category::firstWhere('name', $data['kategori']);

                    $item = new Item;
                    $item->name = $data['nama_barang'];
                    $item->slug = Str::of($data['nama_barang'])->slug();
                    $item->category_id = $category->id;
                    $item->stock = $data['stok'];
                    $item->admin_id = session()->get('user_id');

                    if ($item->save()) {
                        $item_detail = new ItemDetail;
                        $data_details = [];
                        for ($i = 1; $i <= $item->stock; $i++) {
                            $data_details[] = [
                                'item_id' => $item->id,
                                'codename' => $item->name . ' ' . $i,
                                'status' => 1,
                                'unique_code' => rand(1000000000, 9999999999),
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                        }

                        $incoming_transaction = new IncomingTransaction;
                        $incoming_transaction->admin_name = User::find(session()->get('user_id'))->name;
                        $incoming_transaction->item_name = $item->name;
                        $incoming_transaction->item_category = $category->name;
                        $incoming_transaction->item_stock = $item->stock;
                        $incoming_transaction->status = 1;

                        if ($item_detail::insert($data_details) && $incoming_transaction->save()) {
                            $inserted++;
                        }
                    }
                }
            });

            if ($inserted === count($rows[0])) {
                return redirect()->route('admin-index')->with('success', 'Barang ditambahkan!');
            }
        }
    }

    public function category()
    {
        return view('admin.category', [
            'link' => 'ItemCategory',
            'categories' => Category::all()
        ]);
    }

    public function storeCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->toArray()
            ]);
        } else {
            $category = new Category;
            $category->name = $request->name;

            if ($category->save()) {
                return response()->json([
                    'status' => true,
                ]);
            }
        }
    }

    public function deleteCategory(Request $request)
    {
        $category = Category::find($request->id);

        if ($category->delete()) {
            return response()->json([
                'status' => true,
                'deleted' => 'Kategori'
            ]);
        }
    }
}
