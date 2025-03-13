<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamanPerumahan extends Model
{
    use HasFactory;
    
    protected $table = 'pinjaman_perumahan';

    protected $fillable = [
        'nama_pegawai',
        'no_ic',
        'jawatan',
        'gred',
        'tempat_bertugas',
        'jumlah_pendapatan',
        'jumlah_potongan',
        'agregat_keterhutangan',
        'jumlah_pinjaman_perumahan',
        'agregat_bersih',
    ];
        // Define the relationship to the User model
        public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
    
        }
}
