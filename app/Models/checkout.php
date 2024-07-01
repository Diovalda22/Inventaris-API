<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class checkout extends Model
{
    use HasFactory;
    protected $table = 'checkout';
    protected $fillable = ['item_id', 'user_id', 'tanggal_pinjam', 'tanggal_kembali', 'jadwal_kembali', 'catatan', 'status'];
    protected $with = ['item', 'user'];
    protected $hidden = ['item_id', 'user_id'];

    public $timestamps = false;

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
