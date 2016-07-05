<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'idstr';

    public function scopeOrderedPaginatedPosts($query, $idint, $orderBy, $desc, $perPage){
        //"Illuminate\Database\Eloquent\Builder"
        return $query->where('idsub', $idint)->orderBy($orderBy, $desc)->paginate($perPage);
    }
}
