<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\Subscriber;
use App\Models\Post;


class Subreddit extends Model
{
    public $timestamps = false;
    public $incrementing = false;          // $primaryKey won't modify Eloquent's queries
    protected $primaryKey = 'idint';

    public function scopeSortedTopSubreddits($query, $orderBy, $desc, $perPage, $limit=0, $offset=0){
        //Select just the first 1500 subreddits, ordered by subscribers desc
        $subQuery = DB::table('subreddits')
                      ->selectRaw('*')
                      ->skip(0)
                      ->take(1500)
                      ->orderBy('subscribers', 'desc');

        //Query on the prior subquery
        return $query->selectRaw('*')
            ->from(\DB::raw(' ( ' . $subQuery->toSql() . ' ) AS top1500 '))
            ->orderBy($orderBy, $desc)
            ->paginate($perPage);
    }

    public function subscribersProgression(){
        //Model, FK on subscribers table, local key
        return $this->hasMany('App\Models\Subscriber', 'idsub', 'idint');
    }

    public function posts(){
        //Model, FK on subscribers table, local key
        return $this->hasMany('App\Models\Posts', 'idsub', 'idint');
    }
}
