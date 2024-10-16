<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;
    protected $table = 'guru';
    protected $guarded = [];
    
    // public function mapel()
    // {
    //     return $this->belongsToMany(Mapel::class, 'guru_mapel', 'id_guru', 'id_mapel');
    // }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

}
