<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon;
use Mail;
use Adldap\Laravel\Facades\Adldap;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Helpers\DatabaseConnection;

class AppConnection
{
    public static function connect()
    {
        $params = [
            'driver' => 'sqlsrv',
            'host' => '10.8.1.147\sql2008r2',
            'database' => 'ILO_live',
            'username' => 'sa',
            'password' => 'mgr',
        ];
        $connection = DatabaseConnection::setConnection($params);
        
        return $connection;
    }

}