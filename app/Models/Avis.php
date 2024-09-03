<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avis extends Model
{
    use HasFactory;

    protected $fillable = [
         'user_id',
          'programme_id',
          'note',
          'commentaire',
          'date_creation',
          'statut'
    ];
    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function Programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }
}
