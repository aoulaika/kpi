<?php
/**
 * Created by PhpStorm.
 * User: AyoubOlk
 * Date: 27/07/2015
 * Time: 09:57
 */

namespace App;


class Ci {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'di_dim';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'Name',
    'Class_Filter',
    ];

    /**
     * @return the facts for the ci.
     */
    public function facts()
    {
        return $this->hasMany('App\Fact');
    }

    public $timestamps=false;

    protected $primaryKey = 'Id';
} 