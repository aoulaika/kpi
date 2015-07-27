<?php
/**
 * Created by PhpStorm.
 * User: AyoubOlk
 * Date: 27/07/2015
 * Time: 09:59
 */

namespace App;


class Time {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'time_dim';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'CreatedYear',
    'CreatedMonth',
    'CreatedDay',
    'CreatedHour',
    'CreatedMinute',
    'CreatedSecond',
    'UpdatedYear',
    'UpdatedMonth',
    'UpdatedDay',
    'UpdatedHour',
    'UpdatedMinute',
    'UpdatedSecond',
    'ClosedYear',
    'ClosedMonth',
    'ClosedDay',
    'ClosedHour',
    'ClosedMinute',
    'ClosedSecond'
    ];

    /**
     * @return the fact for the time.
     */
    public function fact()
    {
        return $this->hasOne('App\Fact');
    }

    public $timestamps=false;

    protected $primaryKey = 'Id';
} 