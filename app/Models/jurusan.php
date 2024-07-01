<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jurusan extends Model
{
    use HasFactory;
    protected $table = 'jurusan';
    protected $fillable = ['nama', 'deskripsi', 'kode_jurusan'];
    protected $hidden = ['id', 'created_at', 'updated_at'];
}
