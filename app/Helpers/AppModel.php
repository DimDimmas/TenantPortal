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

class AppModel
{
  // set database name from config
  public static $db_connection = 'sqlsrv_dev';
  
  public static function autonumber($tabel, $kolom, $lebar=0, $awalan='')
  {
    $hasil = DB::connection(self::$db_connection)->table($tabel)
              ->select($kolom)
              ->orderBy($kolom, 'desc')
              ->groupBy($kolom)
              ->orWhere($kolom, 'like', $awalan.'%')
              ->limit(1)
              ->get();

    $jumlahrecord = count($hasil);
    if($jumlahrecord == 0)
    $nomor=1;
    else
    {
      $nomor=intval(substr($hasil[0]->$kolom,strlen($awalan)))+1;
    }
    if($lebar>0)
    $angka = $awalan.str_pad($nomor,$lebar,"0",STR_PAD_LEFT);
    else
    $angka = $awalan.$nomor;
    return $angka;
  }

  public static function autonumberback($tabel, $kolom, $lebar=0, $akhiran='')
  {
    $hasil = DB::connection(self::$db_connection)->table($tabel)
              ->select($kolom)
              ->orderBy($kolom, 'desc')
              ->groupBy($kolom)
              ->orWhere($kolom, 'like', '%'.$akhiran)
              ->limit(1)
              ->get();
    $jumlahrecord = count($hasil);
    if($jumlahrecord == 0)
    $nomor=1;
    else
    {
      $nomor=intval(substr($hasil[0]->$kolom,0,-(strlen($akhiran))))+1;
    }
    if($lebar>0)
    $angka = str_pad($nomor,$lebar,"0",STR_PAD_LEFT).$akhiran;
    else
    $angka = $nomor.$awalan;
    return $angka;
  }

  public static function selectRaw($query)
  {
    $sql = DB::connection(self::$db_connection)->select($query);
    return $sql;
  }
  
  public static function selectAll($table)
  {
    $sql = DB::connection(self::$db_connection)->table($table)
          ->get();
    return $sql;
  }

  public static function selectAllOrder($table, $order, $type)
  {
    $sql = DB::connection(self::$db_connection)->table($table)
          ->orderBy($order,$type)
          ->get();
    return $sql;
  }

  public static function selectAllMultipleOrder($table, $order)
  {
    $sql = DB::connection(self::$db_connection)->table($table)
          ->orderByRaw($order)
          ->get();
    return $sql;
  }

  public static function selectConditionMultipleOrder($table, $condition, $order)
  {
    $sql = DB::connection(self::$db_connection)->table($table)
          ->whereRaw($condition)
          ->orderByRaw($order)
          ->get();
    return $sql;
  }

  public static function selectCondition($table, $condition)
  {
    $sql = DB::connection(self::$db_connection)->table($table)
          ->whereRaw($condition)
          ->get();
    return $sql;
  }

  public static function singleSelect($table, $condition, $id)
  {
    $sql = DB::connection(self::$db_connection)->table($table)
          ->where($condition,$id)
          ->first();
    return $sql;
  }

  public static function singleSelectCondition($table, $condition)
  {
    $sql = DB::connection(self::$db_connection)->table($table)
          ->whereRaw($condition)
          ->first();
    return $sql;
  }

  public static function selectCountAll($table)
  {
    $sql = DB::connection(self::$db_connection)->table($table)
          ->count();
    return $sql;
  }

  public static function selectCount($table, $condition, $id)
  {
    $sql = DB::connection(self::$db_connection)->table($table)
          ->where($condition,$id)
          ->count();
    return $sql;
  }

  public static function selectIN($table, $condition, $array)
  {
    $sql = DB::connection(self::$db_connection)->table($table)
          ->whereIN($condition,$array)
          ->first();
    return $sql;
  }

  public static function selectCountIn($table, $condition, $array)
  {
    $sql = DB::connection(self::$db_connection)->table($table)
          ->whereIn($condition, $array)
          ->count();
    return $sql;
  }

  public static function selectCountCondition($table, $condition)
  {
    $sql = DB::connection(self::$db_connection)->table($table)
          ->whereRaw($condition)
          ->count();
    return $sql;
  }

  public static function insert($table, $data)
  {
    $sql = DB::connection(self::$db_connection)->table($table)
            ->insert($data);
    return $sql;
  }

  public static function update($table, $condition, $id, $data)
  {
    $sql = DB::connection(self::$db_connection)->table($table)
          ->where($condition,$id)
          ->update($data);
    return $sql;
  }

  // change raw by zakki
  public static function updateRaw($query)
  {
    $sql = DB::connection(self::$db_connection)->update($query);
    return $sql;
  }

  // change raw by zakki
  public static function deleteRaw($query)
  {
    $sql = DB::connection(self::$db_connection)->delete($query);
    return $sql;
  }

  public static function delete($table, $condition, $id)
  {
    $sql = DB::connection(self::$db_connection)->table($table)
            ->where($condition,$id)
            ->delete();
    return $sql;
  }

  public static function logDelete($table, $ArrayData) {
    DB::connection(self::$db_connection)->table($table)->insert($ArrayData);
    return false;
  }
  
}