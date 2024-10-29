<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Redaman;
use App\Models\Pelanggan;
use DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Validator;
use Carbon\Carbon;

class RedamanController extends Controller
{
    function index(){
        $redaman = Redaman::take(10)->get();
        $labels = [];
        $labels1 = [];
        $labels2 = [];
        $labels3 = [];
        $labels4 = [];
        $labels5 = [];
        $data = [];
        foreach($redaman as $v){
            $labels[] = $v->port;
            $labels1[] = $v->redaman;
            $labels2[] = intval($v->id_pelanggan);
            $labels3[] = $v->nama;
            $labels4[] = $v->alamat;
            $labels5[] = $v->created_at;
            $data[] = intval($v->paket);
        }
        return view('redaman.view', compact('data','labels','labels1','labels2','labels3','labels4','labels5'));
    }

    function data(Request $req){
        $data = Redaman::select('*');
        return DataTables::of($data)
                         ->addColumn('action',function($row){
                         })
                         ->toJson();
    }

    function tambah(){
        return view('redaman.form');
    }

    function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            array_shift($rows); // Menghapus baris header
            
            $importedCount = 0;
            $skippedCount = 0;
            $processedPelangganIds = [];
            $datas=[];
            $indexsenderror=0;
            foreach ($rows as $i => $row) {
                $indexsenderror=$i;
                if (empty($row[0]) && empty($row[1]) && empty($row[2])) {
                    // dd($row);
                    continue; // Lewati baris kosong
                }
                
                // Menggunakan Carbon untuk memanipulasi tanggal
                $currentDate = Carbon::now();
                
                $data = [
                    'port'    => $row[0],
                    'redaman' => $row[1],
                    'id_pelanggan' => $row[2],
                    'nama'    => $row[3],
                    'alamat'  => $row[4],
                    'paket'   => $row[5],
                    'created_at' => $currentDate, // Menggunakan Carbon untuk mendapatkan tanggal saat ini
                ];
                // Validasi dan simpan data redaman
                $x = Redaman::create($data);
                $importedCount++;
                // Periksa apakah pelanggan sudah ada
                if (!in_array($data['id_pelanggan'], $processedPelangganIds)) {
                    $pelanggan = Pelanggan::find($data['id_pelanggan']);
                    if ($pelanggan) {
                        $pelanggan->update([
                            'nama' => $data['nama'],
                            'alamat' => $data['alamat'],
                            'paket' => $data['paket'],
                        ]);
                    } else {
                        // Pelanggan::create([
                        //     'id' => $data['id_pelanggan'],
                        //     'nama' => $data['nama'],
                        //     'alamat' => $data['alamat'],
                        //     'paket' => $data['paket'],
                        // ]);
                        $datas[] = $data;
                    }
                    $processedPelangganIds[] = $data['id_pelanggan'];
                }
            }
            dd('success',$datas);
            return redirect()->back()->with('success', "Berhasil mengimpor $importedCount data redaman.");
        } catch (\Exception $e) {
            dd('error',$e->getMessage(),$indexsenderror,$datas);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
}
