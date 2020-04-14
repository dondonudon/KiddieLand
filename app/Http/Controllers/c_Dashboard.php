<?php

namespace App\Http\Controllers;

use App\master_siswa;
use App\master_thn_ajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class c_Dashboard extends Controller
{
 public static function sidebar()
 {
  $username = request()->session()->get('username');

  if ($username == 'dev') {
   $group = DB::table('sys_menus')
    ->select('sys_menu_groups.id', 'sys_menu_groups.name', 'sys_menu_groups.segment_name', 'sys_menu_groups.icon', 'sys_menu_groups.ord', 'sys_menu_groups.status', 'sys_menu_groups.created_at', 'sys_menu_groups.updated_at')
    ->join('sys_menu_groups', 'sys_menus.id_group', '=', 'sys_menu_groups.id')
    ->orderBy('sys_menu_groups.ord', 'asc')
    ->distinct()
    ->get();

   $dtMenu = DB::table('sys_menus')
    ->select('sys_menus.id', 'sys_menus.id_group', 'sys_menus.name', 'sys_menus.segment_name', 'sys_menus.url', 'sys_menus.ord', 'sys_menus.status', 'sys_menus.created_at', 'sys_menus.updated_at')
    ->orderBy('sys_menus.ord', 'asc')
    ->get();

   $menu = [];
   foreach ($dtMenu as $m) {
    $menu[$m->id_group][] = [
     'id'           => $m->id,
     'id_group'     => $m->id_group,
     'name'         => $m->name,
     'segment_name' => $m->segment_name,
     'url'          => $m->url,
     'ord'          => $m->ord,
     'created_at'   => $m->created_at,
     'updated_at'   => $m->updated_at,
    ];
   }
  } else {
   $group = DB::table('sys_permission')
    ->select('sys_menu_groups.id', 'sys_menu_groups.name', 'sys_menu_groups.segment_name', 'sys_menu_groups.icon', 'sys_menu_groups.ord', 'sys_menu_groups.created_at', 'sys_menu_groups.status', 'sys_menu_groups.updated_at')
    ->join('sys_menus', 'sys_permission.id_menu', '=', 'sys_menus.id')
    ->join('sys_menu_groups', 'sys_menus.id_group', '=', 'sys_menu_groups.id')
    ->where('sys_permission.username', '=', $username)
    ->where('sys_menu_groups.status', '<>', 1)
    ->orderBy('sys_menu_groups.ord', 'asc')
    ->distinct()
    ->get();

   $dtMenu = DB::table('sys_permission')
    ->select('sys_menus.id', 'sys_menus.id_group', 'sys_menus.name', 'sys_menus.segment_name', 'sys_menus.url', 'sys_menus.ord', 'sys_menus.status', 'sys_menus.created_at', 'sys_menus.updated_at')
    ->join('sys_menus', 'sys_permission.id_menu', '=', 'sys_menus.id')
    ->where('sys_permission.username', '=', $username)
    ->where('sys_menus.status', '<>', 1)
    ->orderBy('sys_menus.ord', 'asc')
    ->get();

   $menu = [];
   foreach ($dtMenu as $m) {
    $menu[$m->id_group][] = [
     'id'           => $m->id,
     'id_group'     => $m->id_group,
     'name'         => $m->name,
     'segment_name' => $m->segment_name,
     'url'          => $m->url,
     'ord'          => $m->ord,
     'status'       => $m->status,
     'created_at'   => $m->created_at,
     'updated_at'   => $m->updated_at,
    ];
   }
  }

  $i       = 0;
  $sidebar = [];
  foreach ($group as $g) {
   $sidebar[$i]['group'] = [
    'name'         => $g->name,
    'segment_name' => $g->segment_name,
    'icon'         => $g->icon,
    'status'       => $g->status,
   ];
   $sidebar[$i]['menu'] = $menu[$g->id];
   $i++;
  }
  return $sidebar;
 }

 public function siswaID($kode_siswa)
 {
  return master_siswa::where('kode_siswa', $kode_siswa)->first();
 }

 public function thnajaranID($id)
 {
  return master_thn_ajaran::find($id);
 }

 public function csrf()
 {
  return csrf_token();
 }

 public function kelas(Request $request)
 {
  $kelas = [];
  if (isset($_GET['search'])) {
   $kelas['results'] = DB::table('master_kelas')
    ->select('id', 'kelas as text', 'nama as nama')
    ->where('kelas', 'like', '%' . $_GET['search'] . '%')
   // ->where('status', '=', '1')
    ->orderBy('kelas', 'asc')
    ->get();
  } else {
   $kelas['results'] = DB::table('master_kelas')
    ->select('id', 'kelas as text', 'nama as nama')
   // ->where('status', '=', '1')
    ->orderBy('kelas', 'asc')
    ->limit(10)
    ->get();
  }
  return $kelas;
 }

 public function extra(Request $request)
 {
  $extra = [];
  if (isset($_GET['search'])) {
   $extra['results'] = DB::table('master_extrakurikuler')
    ->select('id', 'nama as text')
    ->where('nama', 'like', '%' . $_GET['search'] . '%')
   // ->where('status', '=', '1')
    ->orderBy('id', 'asc')
    ->get();
  } else {
   $extra['results'] = DB::table('master_extrakurikuler')
    ->select('id', 'nama as text')
   // ->where('status', '=', '1')
    ->orderBy('id', 'asc')
    ->limit(10)
    ->get();
  }
  return $extra;
 }

 public function thnajaran(Request $request)
 {
  $thnajaran = [];
  if (isset($_GET['search'])) {
   $thnajaran['results'] = DB::table('master_thn_ajaran')
    ->select('id', 'periode as text', 'bulan as bulan')
    ->where('periode', 'like', '%' . $_GET['search'] . '%')
   // ->where('status', '=', '1')
    ->orderBy('periode', 'asc')
    ->get();
  } else {
   $thnajaran['results'] = DB::table('master_thn_ajaran')
    ->select('id', 'periode as text', 'bulan as bulan')
   // ->where('status', '=', '1')
    ->orderBy('periode', 'asc')
    ->limit(10)
    ->get();
  }
  return $thnajaran;
 }

 public function siswaD(Request $request)
 {
  $siswa = [];
  if (isset($_GET['search'])) {
   $siswa['results'] = DB::table('master_siswa')
    ->select('id', 'nama as text')
    ->where('periode', 'like', '%' . $_GET['search'] . '%')
   // ->where('status', '=', '1')
    ->orderBy('kode_siswa', 'asc')
    ->get();
  } else {
   $siswa['results'] = DB::table('master_siswa')
    ->select('id', 'nama as text')
   // ->where('status', '=', '1')
    ->orderBy('kode_siswa', 'asc')
    ->limit(10)
    ->get();
  }
  return $siswa;
 }

 public function siswa(Request $request)
 {
  $siswa = [];
  if (isset($_GET['search'])) {
   $siswa['results'] = DB::table('master_siswa')
    ->select('kode_siswa as id', 'nama as text')
    ->where('periode', 'like', '%' . $_GET['search'] . '%')
   // ->where('status', '=', '1')
    ->orderBy('kode_siswa', 'asc')
    ->get();
  } else {
   $siswa['results'] = DB::table('master_siswa')
    ->select('kode_siswa as id', 'nama as text')
   // ->where('status', '=', '1')
    ->orderBy('kode_siswa', 'asc')
    ->limit(10)
    ->get();
  }
  return $siswa;
 }
}
