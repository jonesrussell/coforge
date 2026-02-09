<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Pitch extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'tagline',
        'body',
        'pitch_type',
        'role_sought',
        'status',
    ];

    /**
     * Boot the model and register slug generation.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (Pitch $pitch): void {
            if ($pitch->isDirty('title')) {
                $pitch->slug = $pitch->generateUniqueSlug();
            }
        });
    }

    /**
     * Generate a unique slug from the title.
     */
    public function generateUniqueSlug(): string
    {
        $base = Str::slug($this->title ?? 'pitch');
        $slug = $base;
        $count = 0;

        $query = static::where('slug', $slug);
        if ($this->exists) {
            $query->where('id', '!=', $this->id);
        }
        while ($query->exists()) {
            $count++;
            $slug = $base.'-'.$count;
            $query = static::where('slug', $slug);
            if ($this->exists) {
                $query->where('id', '!=', $this->id);
            }
        }

        return $slug;
    }

    /**
     * User that owns the pitch.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Skills attached to the pitch.
     *
     * @return BelongsToMany<Skill, $this>
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'pitch_skill')->withTimestamps();
    }
}
