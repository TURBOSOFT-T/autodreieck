<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'state_id'
    ];

    public function state(){
        return $this->belongsTo(State::class, 'city_id', 'id');
    }

    public function commandes(){
        return $this->hasMany(Commande::class, 'city_id', 'id');
    }
}
