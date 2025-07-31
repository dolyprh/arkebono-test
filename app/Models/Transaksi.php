<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi_koperasi';

    protected $fillable = [
        'npk',
        'kode',
        'tanggal_transaksi',
        'qty',
        'harga',
        'bayar'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'bayar' => 'boolean',
        'harga' => 'decimal:2'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'npk', 'npk');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'kode', 'kode');
    }
}
