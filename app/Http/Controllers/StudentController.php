<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
//import                    
use App\Helpers\ApiFormatter;
use Exception;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //ambil data dari key search_nama bagian params nya postman
        $search = $request->search_nama;
        //ambil data dai key limit bagian params nya postman
        $limit = $request->limit;
        //cari data bedasarkan yang di searc
        $students = Student::where('nama', 'LIKE', '%' .$search. '%')->limit($limit)->get();

        // //ambil semua data melalui model
        // $students = Student::all();

        //ambil semua data berhasil diambil
        if ($students) {
            return ApiFormatter::createAPI(200, 'succes', $students);
        }else {
            //kalau data gagal diambil
            return ApiFormatter::createAPI(400, 'failed');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|min:3',
                'nis' => 'required|numeric',
                'rombel' => 'required',
                'rayon' => 'required',
            ]);

            //ngrim data yang baru ke table students lewat model student
            $student = Student::create([
                'nama' => $request->nama,
                'nis' => $request->nis,
                'rombel' => $request->rombel,
                'rayon' => $request->rayon,
            ]);
            //cari data yg baru yg berhasil di simpan, cari berdasarkan id lewat data id dari $student yg di atas
            $hasilTambahData = Student::where('id', $student->id)->first();
            if ($hasilTambahData) {
                return ApiFormatter::createAPI(200, 'succes', $student);
            }else {
                //kalau data gagal diambil
                return ApiFormatter::createAPI(400, 'failed');
            }
        }catch(Exception $error) {
            //munculin deskripsi error yg bakal tampil di propety
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function createToken()
    {
        return csrf_token();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            //ambil data dari table students yg id nya sama kaya $id dari path routenya
            //where & find fungsi mencari, fungsingnya, : where nyari berdasarkan column apa aja boleh 
            //kalu find cuman bisa cari berdasarkan id
            $student = Student::find($id);
            if ($student) {
                //kalau ada data berhasil diambil, tampilkan data dari $student nya dengan tanda status code 200
                return ApiFormatter::createAPI(200, 'succes', $student);
            }else {
                //kalau data gagal diambil/data ganda, yg dikembalikan status code 400
                return ApiFormatter::createAPI(400, 'failed');
            }
        }catch (Exception $error) {
            //kalau pas try  ada error, deskripsi errornya ditampilkan dengan status code 400
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try { 
            //cek validasi inputan pada body postman
            $request->validate([
                'nama' => 'required|min:3',
                'nis' => 'required|numeric',
                'rombel' => 'required',
                'rayon' => 'required',
            ]);
            //ambil data yang akan di ubah
            $student = Student::find($id);
            
            //update data yg telah diambil diatas
            $student->update([
                'nama' => $request->nama,
                'nis' => $request->nis,
                'rombel' => $request->rombel,
                'rayon' => $request->rayon,
            ]);
            $dataTerbaru =  Student::where('id', $student->id)->first();
            if ($dataTerbaru) {
                //jika update berhasil, ditampilkan data dari $updatestudent diatas (data yg sudah berhassil diubah)
                return ApiFormatter::createAPI(200, 'success', $dataTerbaru);
            }else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        }catch (Exception $error) {
            //jika di baris kode try ada trouble, error dimmunculkan engan decs err nya dengan status code 400
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            //ambil data yg mau dihapus
            $student = Student::find($id);
            //hapus data yg diambil diatas
            $cekBerhasil = $student->delete();
            if ($cekBerhasil) {
                //kalau berhasil hapus, data yg dimuculin teks konfirm dengan status code 200
                return ApiFormatter::createAPI(200, 'success', 'Data terhapus');
            }else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        }catch (Exception $error) {
            //kalau ada trouble di baris kode dalem try, error decs nya dimunculin
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function trash()
    {
        try {
            $students = Student::onlyTrashed()->get();

            if ($students) {
                return ApiFormatter::createAPI(200, 'success', $students);
            }else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        }catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $student = Student::onlyTrashed()->where('id', $id);

            $student->restore();
            $dataKembali = Student::where('id', $id)->first();
            if ($hasilTambahData) {
                return ApiFormatter::createAPI(200, 'success', $student);
            }else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        }catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    public function permanentDelete($id)
    {
        try {
            $student = Student::onlyTrashed()->where('id', $id);

            $proses = $student->forceDelete();
                return ApiFormatter::createAPI(200, 'success', 'Berhasil hapus permanent!');
        }catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }
}
