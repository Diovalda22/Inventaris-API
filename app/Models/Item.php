<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table = 'item';
    protected $fillable = ['kategori_id', 'jurusan_id', 'image_id', 'nama', 'deskripsi', 'lokasi', 'status'];
    protected $with = ['kategori', 'image', 'jurusan'];
    protected $hidden = ['kategori_id', 'image_id', 'jurusan_id', 'created_at', 'updated_at'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }

    public function jurusan()
    {
        return $this->belongsTo(jurusan::class, 'jurusan_id');
    }
}
