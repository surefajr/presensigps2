<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\dd;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DepartemenController extends Controller
{
    public function index(Request $request)
    {
        $nama_dept =$request->nama_dept;
        $query = Departemen::query();
        $query->select('*');
        if(!empty($nama_dept)){
            $query->where('nama_dept','like','%'.$nama_dept.'%');
        }
        $departemen = $query->get();
        //$departemen = DB::table('departemen')->orderBy('kode_dept')->get();
        return view ('departemen.index',compact('departemen'));
    }

    public function store(Request $request)
{
    $request->validate([
        'kode_dept' => 'required|max:6|unique:departemen,kode_dept',
        'nama_dept' => 'required|max:15',
    ]);

    $kode_dept = $request->kode_dept;
    $nama_dept = $request->nama_dept;

    $data = [
        'kode_dept' => $kode_dept,
        'nama_dept' => $nama_dept
    ];

    $simpan = DB::table('departemen')->insert($data);

    if ($simpan) {
        return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan']);
    } else {
        return Redirect::back()->with(['warning' => 'Data Gagal Di Simpan']);
    }
}


public function edit(Request $request)
{
    $kode_dept = $request->kode_dept;
    $departemen = DB::table('departemen')->where('kode_dept', $kode_dept)->first();
    return view('departemen.edit', compact('departemen'));
}



public function update(Request $request)
{
    $request->validate([
        'kode_dept' => 'required|max:6',
        'nama_dept' => 'required|max:15',
    ]);

    $old_kode_dept = $request->old_kode_dept;
    $kode_dept = $request->kode_dept;
    $nama_dept = $request->nama_dept;

    $data = [
        'kode_dept' => $kode_dept,
        'nama_dept' => $nama_dept
    ];

    if ($old_kode_dept != $kode_dept) {
        // Check if the new kode_dept already exists
        $cek = DB::table('departemen')->where('kode_dept', $kode_dept)->count();
        if ($cek > 0) {
            return Redirect::back()->with(['warning' => 'Data dengan kode ' . $kode_dept . ' sudah ada']);
        }
    }
    Log::info('Updating Departemen: ', $data);

    $update = DB::table('departemen')->where('kode_dept', $old_kode_dept)->update($data);

    if ($update) {
        return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
    } else {
        return Redirect::back()->with(['warning' => 'Data Gagal Di Update']);
    }
}




    public function delete($kode_dept)
    {
        $hapus = DB::table('departemen')->where('kode_dept', $kode_dept)->delete();
        if ($hapus) {
            return Redirect::back()->with(['success'=> 'Data Berhasil Di Hapus']);
        }else{
            return Redirect::back()->with(['warning'=> 'Data Gagal Di Hapus']);
        }
    }
}