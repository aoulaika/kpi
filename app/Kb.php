<?php
/**
 * Created by PhpStorm.
 * User: AyoubOlk
 * Date: 27/07/2015
 * Time: 09:58
 */

namespace App;


class Kb {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'kb_dim';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'EKMS_knowledge_Id',
    'Knowledge_Id'
    ];

    /**
     * @return the facts for the kb.
     */
    public function facts()
    {
        return $this->hasMany('App\Fact');
    }

    public $timestamps=false;

    protected $primaryKey = 'Id';
} 