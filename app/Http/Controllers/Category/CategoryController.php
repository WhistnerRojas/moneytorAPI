<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    //
    public function add(Request $request)
    {
        DB::insert('INSERT INTO category (category_name) VALUES (?)', [$request->input('category_name')]);
        return response()->json(['success' => true]);
    }
    public function fetch()
    {
        $result = DB::select('SELECT * FROM category');
        return response()->json(['success' => true, 'responsedata' => (array)$result]);
    }
}
