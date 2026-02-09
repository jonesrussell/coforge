<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PitchInterest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'pitch_id',
        'message',
        'status',
    ];

    /**
     * The user who expressed interest.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The pitch that received the interest.
     *
     * @return BelongsTo<Pitch, $this>
     */
    public function pitch(): BelongsTo
    {
        return $this->belongsTo(Pitch::class);
    }
}
