<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ebook extends Model
{
    protected $fillable = [
        'title',
        'description',
        'author',
        'release_date',
        'cover_path',
        'pdf_path',
    ];

    // ✅ Tambahkan casting ke date
    protected $casts = [
        'release_date' => 'date',
    ];

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }
}
