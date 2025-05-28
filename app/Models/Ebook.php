<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
  protected $fillable = ['title', 'author', 'published_at', 'cover', 'file'];

}
