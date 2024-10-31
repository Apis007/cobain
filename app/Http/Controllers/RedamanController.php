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
        $redaman = Redaman::all();
        return view('redaman.view');
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

    public function import(Request $request)
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

        foreach ($rows as $row) {
            if (empty($row[0]) && empty($row[1]) && empty($row[2])) {
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
                'created_at' => $currentDate,
                'updated_at' => $currentDate
            ];

            // Simpan data redaman
            Redaman::create($data);
            $importedCount++;

            // Cek apakah pelanggan sudah ada di tabel pelanggan
        //     $pelanggan = Pelanggan::find($data['id']);
        //     if (!$pelanggan) {
        //         // Jika pelanggan belum ada, buat pelanggan baru
        //         Pelanggan::create([
        //             'id' => $data['id_pelanggan'],
        //             'nama' => $data['nama'],
        //             'alamat' => $data['alamat'],
        //             'paket' => $data['paket'],
        //             'status' => $data['status'],
        //             'created_at' => $currentDate,
        //             'updated_at' => $currentDate
        //         ]);
        //     }
        }

        return redirect()->back()->with('success', "Berhasil mengimpor $importedCount data redaman dan pelanggan.");
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
}
