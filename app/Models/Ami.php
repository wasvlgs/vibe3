<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ami extends Model
{
    use HasFactory;

    protected $table = 'ami';

    protected $fillable = [
        'id_sender',
        'id_receiver',
        'status',
    ];

    /**
     * L'expéditeur de la demande d'ami.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_sender');
    }

    /**
     * Le destinataire de la demande d'ami.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_receiver');
    }
}
