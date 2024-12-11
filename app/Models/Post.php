<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'topic',
    ];

    /**
     * @var bool
     */
    public $timestamps = true;

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
