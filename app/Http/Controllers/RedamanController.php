<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Redaman;
use App\Models\Pelanggan;
use DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        'importDate' => 'nullable|date',
    ]);

    try {
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        array_shift($rows); // Menghapus baris header

        $importedCount = 0;
        $skippedCount = 0;

        $selectedDate = $request->input('importDate') 
            ? Carbon::parse($request->input('importDate'))->startOfDay() 
            : Carbon::now();

        foreach ($rows as $row) {
            if (empty($row[0]) && empty($row[1]) && empty($row[2])) {
                $skippedCount++;
                continue;
            }

            // Jika kolom paket kosong, berikan nilai default 1
            $data = [
                'port'         => $row[0],
                'redaman'      => $row[1],
                'id_pelanggan' => $row[2],
                'nama'         => $row[3] ?? 'Tidak Diketahui',
                'alamat'       => $row[4] ?? 'Alamat Tidak Diketahui',
                'paket'        => $row[5] ?? 0, // Default ke 0 jika kosong
                'created_at'   => $selectedDate,
                'updated_at'   => $selectedDate
            ];

            // Cek apakah pelanggan sudah ada di tabel pelanggan berdasarkan kolom `id`
            $pelanggan = Pelanggan::find($row[2]);

            // Jika pelanggan belum ada, tambahkan pelanggan baru
            if (!$pelanggan) {
                $pelanggan = Pelanggan::create([
                    'nama'       => $row[3] ?? 'Tidak Diketahui',
                    'alamat'     => $row[4] ?? 'Alamat Tidak Diketahui',
                    'paket'      => $data['paket'], // Gunakan nilai paket yang sudah dicek
                    'created_at' => $selectedDate,
                    'updated_at' => $selectedDate
                ]);

                // Gunakan ID pelanggan baru untuk id_pelanggan
                $data['id_pelanggan'] = $pelanggan->id;
            }

            // Simpan data redaman
            Redaman::create($data);
            $importedCount++;
        }

        return response()->json([
            'success' => true, 
            'message' => "Berhasil mengimpor $importedCount data redaman, $skippedCount data dilewati karena kosong."
        ]);
    } catch (\Exception $e) {
        \Log::error('Kesalahan saat impor data redaman: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
}



    }
