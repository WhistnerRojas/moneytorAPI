<?php

namespace App\Http\Controllers\Logs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogsController extends Controller
{
    //
    public function fetch()
    {
    }
    public function add($params)
    {
        DB::insert('INSERT INTO logs (user_id,log_details) VALUES (?,?)', [$params['user_id'], json_encode(['details' => $params['log_details'], 'id' => $params['id'], 'table' => $params['table']])]);
        return ['log_id' => DB::getPdo()->lastInsertId()];
    }
}
