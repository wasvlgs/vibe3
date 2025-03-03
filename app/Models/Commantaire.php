<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commantaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'post_id', 'commantaire', 'date',
    ];

    // Relation Commentaire -> User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relation Commentaire -> Post
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
