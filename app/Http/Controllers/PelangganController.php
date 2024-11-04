<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pelanggan;

use DataTables;

class PelangganController extends Controller{

    function index(){
        $pelanggan = Pelanggan::take(5)->get();
        $labels = [];
        $data = [];
        $data2 = [];
        $data3 = [];
        foreach($pelanggan as $v){
            $labels[] = $v->nama;
            $data[] = $v->alamat;
            $data2[] = $v->status;
            $data3[] = intval($v->paket);
        }
        return view('pelanggan.view', compact('data','labels','data2','data3'));
    }

    function data(Request $req){
        $data = Pelanggan::select('*');
        return DataTables::of($data)
                         ->addColumn('action',function($row){
                            $url = route('pelanggan.edit',$row->id);
                            $url_delete = route('pelanggan.delete',$row->id);
                            $url_detail = route('pelanggan.detail',$row->id);
                            $btn =  '<button type="button" class="btn btn-warning btn-sm btn-form" data-url="'.$url.'" title="Edit Data"><i class="fa fa-edit"></i></button>';
                            $btn.=  '<button type="button" class="btn btn-danger btn-sm btn-delete" data-url="'.$url_delete.'" title="Hapus Data"><i class="fa fa-trash"></i></button>';
                            $btn.=  '<button type="button" class="btn btn-primary btn-sm btn-detail" data-url="'.$url_detail.'" title="Detail Data"><i class="fa fa-eye"></i></button>';
                            return $btn; 
                         })
                         ->toJson();
    }

    function tambah(){
        return view('pelanggan.form');
    }
    function edit($id){
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggan.form',compact('pelanggan'));
    }
    function detail($id){
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggan.formdetail',compact('pelanggan'));
    }
    function hapus($id){
        $pelanggan = Pelanggan::findOrFail($id);
        if($pelanggan->delete()){
            return response()->json(['success'=>TRUE, 'message'=>'Data Berhasil dihapus']);
        }else{
            return response()->json(['success'=>FALSE, 'message'=>'Data gagal dihapus']);
        }
    }

    function simpan(Request $req){
        // validasi gwe dewe

        $id = $req->id;
        if(empty($id)){
            $pelanggan = new Pelanggan;
        }else{
            $pelanggan = Pelanggan::findOrFail($id);
        }

        $pelanggan->nama = $req->nama;
        $pelanggan->alamat = $req->alamat;
        $pelanggan->status = $req->status;
        $pelanggan->paket = $req->paket;

        if($pelanggan->save()){
            return response()->json(['success'=>TRUE, 'message'=>'Data Berhasil disimpan']);
        }else{
            return response()->json(['success'=>FALSE, 'message'=>'Data gagal disimpan']);
        }
    }
}
