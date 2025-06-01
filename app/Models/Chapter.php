<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends Model
{
    protected $fillable = [
        'ebook_id',
        'title',
    ];

    public function ebook(): BelongsTo
    {
        return $this->belongsTo(Ebook::class);
    }

    public function subchapters(): HasMany
    {
        return $this->hasMany(Subchapter::class);
    }
}
