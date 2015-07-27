<?php
/**
 * Created by PhpStorm.
 * User: AyoubOlk
 * Date: 27/07/2015
 * Time: 09:56
 */

namespace App;


class Agent {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'agent_dim';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'Code',
    'Name'
    ];

    /**
     * @return the facts for the agent.
     */
    public function facts()
    {
        return $this->hasMany('App\Fact');
    }

    public $timestamps=false;

    protected $primaryKey = 'Id';
} 