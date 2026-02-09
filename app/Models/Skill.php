<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Pitches that have this skill.
     *
     * @return BelongsToMany<Pitch, $this>
     */
    public function pitches(): BelongsToMany
    {
        return $this->belongsToMany(Pitch::class, 'pitch_skill')->withTimestamps();
    }
}
