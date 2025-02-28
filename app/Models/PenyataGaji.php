<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyataGaji extends Model {
    use HasFactory;

    protected $table = 'penyata_gaji';
    protected $fillable = [
        'nama_pegawai',
        'pinjaman_peribadi_bsn',
        'pinjaman_perumahan', 
        'bayaran_balik_itp', 
        'bayaran_balik_bsh', 
        'ptptn',
        'kutipan_semula_emolumen', 
        'arahan_potongan_nafkah', 
        'komputer', 
        'pcb', 
        'lain_lain_potongan_pembentungan',
        'koperasi', 
        'berkat', 
        'angkasa',
        'potongan_lembaga_th', 
        'amanah_saham_nasional', 
        'zakat_yayasan_wakaf', 
        'insuran', 
        'kwsp', 
        'i_destinasi',
        'angkasa_bukan_pinjaman'
    ];
}
