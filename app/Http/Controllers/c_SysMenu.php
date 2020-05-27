<?php

namespace App\Http\Controllers;

use App\sysMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class c_SysMenu extends Controller
{
    public function dataset() {
        return DB::table('sys_menus')
            ->select('sys_menus.id','sys_menu_groups.name as group','sys_menus.name','sys_menus.segment_name','sys_menus.url','sys_menus.ord')
            ->join('sys_menu_groups','sys_menus.id_group','=','sys_menu_groups.id')
            ->get();
    }

    public function index() {
        $group = DB::table('sys_menu_groups')->select('id','name')->get();
        return view('dashboard.system-utility.menu.baru')->with('data',$group);
    }

    public function list() {
        return view('dashboard.system-utility.menu.list');
    }

    public function listData() {
        return json_encode($this->dataset());
    }

    public function edit($id) {
        $data = DB::table('sys_menus')->where('id','=',$id)->first();
        $group = DB::table('sys_menu_groups')->select('id','name')->get();
        return view('dashboard.system-utility.menu.edit')->with('data',$data)->with('group',$group);
    }

    public function submit(Request $request) {
        $type = $request->type;
        $id_group = $request->id_group;
        $name = $request->name;
        $segment_name = $request->segment_name;
        $url = $request->url;
        $ord = $request->ord;

        try {
            DB::beginTransaction();
            if ($type == 'baru') {
                $sysMenu = new sysMenu();
                $sysMenu->id_group = $id_group;
                $sysMenu->name = $name;
                $sysMenu->segment_name = $segment_name;
                $sysMenu->url = $url;
                $sysMenu->ord = $ord;
                $sysMenu->save();
            } elseif ($type == 'edit') {
                DB::table('sys_menus')
                    ->where('id','=',$request->id)
                    ->update([
                        'id_group' => $id_group,
                        'name' => $name,
                        'segment_name' => $segment_name,
                        'url' => $url,
                        'ord' => $ord,
                    ]);
            }
            DB::commit();
            return 'success';
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json($ex);
        }
    }

    public function reorder(Request $request) {
        $id = $request->id;
        $ord = $request->ord;
        try {
            DB::beginTransaction();
            DB::table('sys_menus')
                ->where('id','=',$id)
                ->update([
                    'ord' => $ord,
                ]);
            DB::commit();
            return 'success';
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([$ex]);
        }
    }
}
