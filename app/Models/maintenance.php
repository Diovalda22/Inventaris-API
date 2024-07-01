<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class maintenance extends Model
{
    use HasFactory;
    protected $table = 'maintenance';
    protected $fillable = ['item_id', 'user_id', 'deskripsi', 'tanggal_dijadwalkan', 'tanggal_proses', 'tanggal_selesai', 'status'];
    protected $with = ['item', 'user'];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
