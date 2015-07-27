<?php
/**
 * Created by PhpStorm.
 * User: AyoubOlk
 * Date: 27/07/2015
 * Time: 09:58
 */

namespace App;


class Contact {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contact_dim';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'Contact_type',
    'Contact_language'
    ];

    /**
     * @return the facts for the contact.
     */
    public function facts()
    {
        return $this->hasMany('App\Fact');
    }

    public $timestamps=false;

    protected $primaryKey = 'Id';
} 