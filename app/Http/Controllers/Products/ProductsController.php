<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class ProductsController extends Controller
{
    //
    public function add(Request $request)
    {
        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            $filename = $file->getClientOriginalName();
            $file->storeAs('product_images', $filename, 's3');
        }
        DB::insert('INSERT INTO products (product_name,category_id,product_image,product_price) VALUES (?,?,?,?)', [$request->input('product_name'), $request->input('category_id'), Storage::disk('s3')->url('product_images/' . $request->file('product_image')->getClientOriginalName()), $request->input('product_price')]);
        return response()->json(['success' => true, 'row' => DB::getPdo()->lastInsertId()]);
    }
    public function fetch()
    {
        $results =   DB::select('SELECT * FROM products');
        return response()->json(['success' => true, 'responsedata' => (array)$results]);
    }
    public function fetchSingleProduct($id)
    {
        $res = DB::select('SELECT * FROM products where product_id = ?', [$id]);
        return (array)$res[0];
    }
}
