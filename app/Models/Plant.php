<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;
    protected $fillable = ['name','prix','categorie_id','user_id'];
    public function categorie(){
        return $this->belongsTo(Categorie::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
