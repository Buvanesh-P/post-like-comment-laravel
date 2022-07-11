<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Action;
class Post extends Model
{
    use HasFactory;
    protected $fillable=[
        "post_desc",
        "users_id",
    ];

    public function user()
    {

        return $this->belongsTo(User::class,'users_id');
    }

    public function action()
    {
        return $this->hasMany(Action::class,'id');
    }

}
