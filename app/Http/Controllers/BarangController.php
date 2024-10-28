<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Barang;

use DataTables;

class BarangController extends Controller{

    function index(){
        $barang = Barang::take(5)->get();
        $labels = [];
        $data = [];
        $data2 = [];
        foreach($barang as $v){
            $labels[] = $v->nama;
            $data[] = intval($v->stok);
            $data2[] = intval(rand(1,$v->stok));
        }
        return view('barang.view', compact('data','labels','data2'));
    }

    function data(Request $req){
        $data = Barang::select('*');
        return DataTables::of($data)
                         ->addColumn('action',function($row){
                            $url = route('barang.edit',$row->id);
                            $url_delete = route('barang.delete',$row->id);
                            $btn =  '<button type="button" class="btn btn-warning btn-sm btn-form" data-url="'.$url.'" title="Edit Data"><i class="fa fa-edit"></i></button>';
                            $btn.=  '<button type="button" class="btn btn-danger btn-sm btn-delete" data-url="'.$url_delete.'" title="Hapus Data"><i class="fa fa-trash"></i></button>';
                            return $btn; 
                         })
                         ->toJson();
    }

    function tambah(){
        return view('barang.form');
    }
    function edit($id){
        $barang = Barang::findOrFail($id);
        return view('barang.form',compact('barang'));
    }
    function hapus($id){
        $barang = Barang::findOrFail($id);
        if($barang->delete()){
            return response()->json(['success'=>TRUE, 'message'=>'Data Berhasil dihapus']);
        }else{
            return response()->json(['success'=>FALSE, 'message'=>'Data gagal dihapus']);
        }
    }

    function simpan(Request $req){
        // validasi gwe dewe

        $id = $req->id;
        if(empty($id)){
            $barang = new Barang;
        }else{
            $barang = Barang::findOrFail($id);
        }

        $barang->nama = $req->nama;
        $barang->deskripsi = $req->desc;
        $barang->harga = $req->hrg;
        $barang->stok = $req->stok;

        if($barang->save()){
            return response()->json(['success'=>TRUE, 'message'=>'Data Berhasil disimpan']);
        }else{
            return response()->json(['success'=>FALSE, 'message'=>'Data gagal disimpan']);
        }
    }
}
