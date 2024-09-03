<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Pays extends Model
{
    use HasFactory;
    protected $fillable =[
         'name',
         'superficie',
         'nbr_habitant',
         'langue',
         'description',
         'photo'
    ];

    public function Programme() : HasMany
    {
        return $this->hasMany(Programme::class);
    }
}
