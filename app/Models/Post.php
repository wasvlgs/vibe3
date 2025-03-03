<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'titre', 'description', 'content', 'image', 'date',
    ];

    // Relation Post -> User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function commantaires(): HasMany
    {
        return $this->hasMany(Commantaire::class);
    }
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }
}

