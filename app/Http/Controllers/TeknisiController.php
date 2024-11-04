<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teknisi;
use DataTables;

class TeknisiController extends Controller
{
    function index(){
        $teknisi = Teknisi::take(5)->get();
        $labels = [];
        $label = [];
        $data = [];
        foreach($teknisi as $v){
            $labels[] = $v->nama;
            $data[] = intval($v->no_hp);
            $label[] = $v->alamat;
        }
        return view('teknisi.view', compact('data','labels','label'));
    }

    function data(Request $req){
        $data = Teknisi::select('*');
        return DataTables::of($data)
                         ->addColumn('action',function($row){
                            $url = route('teknisi.edit',$row->id);
                            $url_delete = route('teknisi.delete',$row->id);
                            $btn =  '<button type="button" class="btn btn-warning btn-sm btn-form" data-url="'.$url.'" title="Edit Data"><i class="fa fa-edit"></i></button>';
                            $btn.=  '<button type="button" class="btn btn-danger btn-sm btn-delete" data-url="'.$url_delete.'" title="Hapus Data"><i class="fa fa-trash"></i></button>';
                            return $btn; 
                         })
                         ->toJson();
    }

    function tambah(){
        return view('teknisi.form');
    }
    function edit($id){
        $teknisi = Teknisi::findOrFail($id);
        return view('teknisi.form',compact('teknisi'));
    }
    function hapus($id){
        $teknisi = Teknisi::findOrFail($id);
        if($teknisi->delete()){
            return response()->json(['success'=>TRUE, 'message'=>'Data Berhasil dihapus']);
        }else{
            return response()->json(['success'=>FALSE, 'message'=>'Data gagal dihapus']);
        }
    }

    function simpan(Request $req){
        // validasi gwe dewe

        $id = $req->id;
        if(empty($id)){
            $teknisi = new Teknisi;
        }else{
            $teknisi = Teknisi::findOrFail($id);
        }

        $teknisi->nama = $req->nama;
        $teknisi->no_hp = $req->no_hp;
        $teknisi->alamat = $req->alamat;

        if($teknisi->save()){
            return response()->json(['success'=>TRUE, 'message'=>'Data Berhasil disimpan']);
        }else{
            return response()->json(['success'=>FALSE, 'message'=>'Data gagal disimpan']);
        }
    }
}
