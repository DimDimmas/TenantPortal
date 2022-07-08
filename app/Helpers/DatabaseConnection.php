<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Config;

class DatabaseConnection
{
    public static function setConnection($params)
    {
        config(['database.connections.onthefly' => [
            'driver' => $params['driver'],
            'host' => $params['host'],
            'database' => $params['database'],
            'username' => $params['username'],
            'password' => $params['password']
        ]]);

        return DB::connection('onthefly');
    }
}
