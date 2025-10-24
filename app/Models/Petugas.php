<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas';
    public $timestamps = false;
    
    protected $fillable = [
        'nama',
        'gender',
        'telp',
        'id_user'
    ];

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'id_petugas', 'id_petugas');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}