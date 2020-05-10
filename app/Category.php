<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $with = ['questions'];
    protected $fillable = ['name', 'slug'];

    public function questions() {
        return $this->hasMany(Question::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
