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

class AppAction
{
  public static function autonumber($tabel, $kolom, $lebar=0, $awalan='')
  {
    $hasil = DB::table($tabel)
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
    $hasil = DB::table($tabel)
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
}