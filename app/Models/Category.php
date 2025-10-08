<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public function repositories()
    {
        return $this->hasMany(Repository::class, 'category_id', 'id');
    }
}
