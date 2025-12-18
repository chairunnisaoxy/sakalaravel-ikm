<?php
// app/Models/Produk.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'm_produk';
    protected $primaryKey = 'id_produk';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;
    
    protected $fillable = [
        'id_produk',
        'nama_produk',
        'status_produk',
        'satuan'
    ];
}
