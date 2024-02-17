<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'leads';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that are hidden.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'assignedOn',
        'completedOn',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'natid',
        'mobile',
        'password',
        'token',
        'ecnumber',
        'address',
        'signup_ip_address',
        'activated',
        'locale',
        'isContacted',
        'isSMSed',
        'isSale',
        'agent',
        'assignedOn',
        'completedOn',
        'notes',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'name'                              => 'string',
        'first_name'                        => 'string',
        'last_name'                         => 'string',
        'email'                             => 'string',
        'natid'                             => 'string',
        'mobile'                             => 'string',
        'password'                             => 'string',
        'token'                             => 'string',
        'ecnumber'                             => 'string',
        'address'                             => 'string',
        'signup_ip_address'                    => 'string',
        'activated'                             => 'boolean',
        'locale'                             => 'string',
        'isContacted'                             => 'boolean',
        'isSMSed'                             => 'boolean',
        'isSale'                             => 'boolean',
        'agent'                             => 'string',
        'notes'                             => 'string',
    ];
}
