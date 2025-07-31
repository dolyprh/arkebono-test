<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'master_karyawan';
    protected $primaryKey = 'npk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'npk',
        'nama',
        'alamat'
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'npk', 'npk');
    }
}
