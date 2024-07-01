<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::all();
        return $this->success($kategori);
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
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'deskripsi' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'invalid field', 'error' => $validator->errors()], 422);
        }

        $kategori = Kategori::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);
        return $this->message('Berhasil tambah kategori baru');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kategori $kategori)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $validator = Validator::make($request->all(), [
        //     'nama' => 'required',
        //     'deskripsi' => 'required'
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['message' => 'invalid field', 'error' => $validator->errors()], 422);
        // }

        $kategori = Kategori::find($id);
        if (!$kategori) return $this->fail('Kategori tidak ditemukan', 404);
        $kategori->update($request->all());
        return $this->message('Berhasil update data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kategori = Kategori::where('id', $id)->first();
        if (!$kategori) return $this->fail('Kategori tidak ditemukan', 404);
        $kategori->delete();
        return $this->message('Berhasil hapus data kategori');
    }
}
