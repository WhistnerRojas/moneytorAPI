<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Logs\LogsController;
use App\Http\Controllers\Products\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    //
    public function fetch()
    {
        $results = DB::select('SELECT * FROM transactions');
        $res = (array)$results;
        $tempItem = [];
        foreach ($res as $r) {
            $r->items_array = json_decode($r->items_array, true);
            foreach ($r->items_array as $item) {
                $item['product_details'] = (new ProductsController())->fetchSingleProduct($item['id']);
                $tempItem[] = $item;
            }
            $r->items_array = $tempItem;
            $tempItem = [];
        }

        return response()->json(['success' => true, 'responsedata' => $res]);
    }

    public function add(Request $request)
    {
        DB::insert('INSERT INTO transactions (transaction,items_array,transaction_total,merchant_id) VALUES(?,?,?,?)', [$request->input('transaction'), json_encode($request->input('items_array')), $request->input('transaction_total'), $request->input('merchant_id')]);
        $log_id = (new LogsController())->add(['user_id' => 1, 'log_details' => 'added new transaction', 'id' => DB::getPdo()->lastInsertId(), 'table' => 'transactions']);
        return response()->json(['success' => true, 'response_row_id' => DB::getPdo()->lastInsertId(), 'log_id' => $log_id['log_id']]);
    }

    public function edit()
    {
    }
}
