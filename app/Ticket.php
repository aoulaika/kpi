<?php
/**
 * Created by PhpStorm.
 * User: AyoubOlk
 * Date: 27/07/2015
 * Time: 09:59
 */

namespace App;


class Ticket {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tickets_dim';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'Number',
    'Short_Description',
    'Priority',
    'State',
    'Assignment_group',
    'Assigned_to',
    'FCR_resolved',
    'FCR_resolvable',
    'Category',
    'Sub_category',
    'Area',
    'Description',
    'Reassignment_count',
    'Reopen_count',
    'Location',
    'Parent_incident',
    'Closure_code',
    'Task_type',
    'Work_note',
    'Closed_by',
    'Closure_method',
    'Service_level',
    'SLA_due'
    ];

    /**
     * @return the fact for the ticket.
     */
    public function fact()
    {
        return $this->hasOne('App\Fact');
    }

    public $timestamps=false;

    protected $primaryKey = 'Id';
} 