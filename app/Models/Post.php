<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use Searchable;
    use HasFactory;

    protected $fillable = ["title", "body", "user_id"];

    public function toSearchableArray() //toSearchableArray() function name is fix it cannot be change
    {
        // the term that we are searching for
        return [
            "title"=> $this->title,
            "body"=> $this->body
        ];
    }

    public function user(){
        // Query using ORM or Eloquent
        return $this->belongsTo(User::class, 'user_id');
    }
}
