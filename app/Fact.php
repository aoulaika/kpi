<?php
/**
 * Created by PhpStorm.
 * User: AyoubOlk
 * Date: 27/07/2015
 * Time: 09:58
 */

namespace App;


class Fact
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fact';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fk_agent',
        'fk_ticket',
        'fk_ci',
        'fk_contact',
        'fk_kb',
        'fk_time',
        'Handling_time'
    ];

    /**
     * @return the fact for the fact.
     */
    public function agent()
    {
        return $this->hasOne('App\Agent');
    }

    /**
     * @return the ticket for the fact.
     */
    public function ticket()
    {
        return $this->hasOne('App\Ticket');
    }

    /**
     * @return the ci for the fact.
     */
    public function ci()
    {
        return $this->hasOne('App\Ci');
    }

    /**
     * @return the contact for the fact.
     */
    public function contact()
    {
        return $this->hasOne('App\Contact');
    }

    /**
     * @return the kb for the fact.
     */
    public function kb()
    {
        return $this->hasOne('App\Kb');
    }

    /**
     * @return the time for the fact.
     */
    public function time()
    {
        return $this->hasOne('App\Time');
    }

    public $timestamps = false;

    protected $primaryKey = 'Id';
} 