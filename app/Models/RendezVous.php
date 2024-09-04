<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RendezVous extends Model
{
    use HasFactory;

    protected $fillable = [
         'user_id',
         'dateRdv',
         'heureDebut',
         'heureFin',
         'raison',
         'statut',
        ];

    public function User(): BelongsTo

    {
        return $this->belongsTo(User::class);
    }
}
