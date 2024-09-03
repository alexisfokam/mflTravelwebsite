<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Programme extends Model
{
    use HasFactory;
     
    protected $fillable =[
         'pays_id',
         'titre',
         'type',
         'description',
         'conditions',
         'frais',
         'duree',
         'delais',
         'statut',
         'nbr_place'

    ];

    public function Pays(): BelongsTo

    {
        return $this->belongsTo(Pays::class);
    }

    public function Procedure(): HasOne
    {
        return $this->hasOne(Procedure::class);
    } 
    
    public function Avis():HasMany
    {
        return $this->hasMany(Avis::class);
    }
}
