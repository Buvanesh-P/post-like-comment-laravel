<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Action extends Model
{
    use HasFactory;

    protected $fillable=[
        "posts_id",
        "users_id",
        "type",
        "value",
    ];

    public function post()
    {
        return $this->belongsTo(Post::class,'posts_id');
    }
}
