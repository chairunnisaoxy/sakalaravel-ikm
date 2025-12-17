<?php
// app/Models/Karyawan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'm_karyawan';
    protected $primaryKey = 'id_karyawan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_karyawan',
        'nama_karyawan',
        'jabatan',
        'gaji_harian',
        'email',
        'password',
        'no_telp',
        'alamat',
        'status_karyawan',
        'jml_target',
        'total_hadir'
    ];

    protected $hidden = [
        'password'
    ];

    // TIDAK GUNAKAN timestamps karena tabel tidak punya created_at/updated_at
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($karyawan) {
            if (!$karyawan->id_karyawan) {
                $karyawan->id_karyawan = 'KRY-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            }
            if ($karyawan->password) {
                $karyawan->password = Hash::make($karyawan->password);
            }
        });

        static::updating(function ($karyawan) {
            if ($karyawan->isDirty('password') && $karyawan->password) {
                $karyawan->password = Hash::make($karyawan->password);
            }
        });
    }

    // Scope untuk karyawan aktif
    public function scopeAktif($query)
    {
        return $query->where('status_karyawan', 'aktif');
    }

    // Helper methods
    public function isPemilik()
    {
        return $this->jabatan === 'pemilik';
    }

    public function isSupervisor()
    {
        return $this->jabatan === 'supervisor';
    }

    public function isOperator()
    {
        return $this->jabatan === 'operator';
    }
}
