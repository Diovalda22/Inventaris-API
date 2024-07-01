<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $item = Item::all();
        return $this->success($item);
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
            'kategori_id' => 'required',
            'nama' => 'required',
            'deskripsi' => 'required',
            'lokasi' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'invalid field', 'error' => $validator->errors()], 422);
        }

        $user = $request->user();
        $image = $request->file('image');
        $fileName = time() . '_' . $image->getClientOriginalName();
        $filePath = $image->storeAs('uploads', $fileName, 'public');

        $imageUpload = Image::create([
            'file_name' => $fileName,
            'file_path' => '/storage/app/public/' . $filePath
        ]);

        $item = Item::create([
            'kategori_id' => $request->kategori_id,
            'image_id' => $imageUpload->id,
            'jurusan_id' => $user->jurusan_id,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
        ]);
        return $this->message('Berhasil Tambah Item Baru');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $validator = Validator::make($request->all(), [
        //     'kategori_id' => 'required',
        //     'nama' => 'required',
        //     'deskripsi' => 'required',
        //     'lokasi' => 'required',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['message' => 'invalid field', 'error' => $validator->errors()], 422);
        // }

        $item = Item::find($id);
        $item->update($request->all());
        return $this->message('Berhasil Update Item');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $item = Item::find($id);
        if (!$item) {
            return response()->json(['message' => 'Item tidak ditemukan'], 404);
        }
        $item->delete();
        return $this->message('Berhasil Hapus Item');
    }
}
