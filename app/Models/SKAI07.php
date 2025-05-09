<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SKAI07 extends Model
{
    use HasFactory;

    protected $table = 'skai07';

    protected $fillable = [
        'nama',
        'no_kad_pengenalan',
        'no_badan',
        'gred',
        'jawatan',
        'gaji',
        'elaun',
        'sewa_rumah',
        'sewa_kenderaan',
        'sumbangan_suami_isteri',
        'lain_lain_pendapatan',
        'rumah',
        'kereta',
        'motorsikal',
        'komputer',
        'tabung_haji',
        'asb',
        'simpanan',
        'zakat',
        'lain2_bercagar',
        'pinjaman_peribadi',
        'kad_kredit',
        'lain2_tidak_bercagar',
        'jumlah_pendapatan',
        'jumlah_perbelanjaan',
        'lebihan_pendapatan',
        'percent_liabiliti_tidak_bercagar',
        'user_id'
    ];    

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

