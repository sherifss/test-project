<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'content',
        'abbreviation',
    ];

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the post that owns the comment.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
