<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Subreddit;

class Subscriber extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'idsub';
}
