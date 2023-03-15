<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;
    protected $fillable =['titre'];
    public function plant(){
        return $this->hasMany(Plant::class);
    }
    
}
