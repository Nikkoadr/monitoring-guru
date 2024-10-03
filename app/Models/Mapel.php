<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'mapel';

    public function guru()
    {
        return $this->belongsToMany(Guru::class, 'guru_mapel', 'id_mapel', 'id_guru');
    }
}
