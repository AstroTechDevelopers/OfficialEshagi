<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MusoniRecord extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'musoni_records';

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
        'user_id',
        'natid',
        'status',
        'reviewer',
        'authorizer',
        'business_type',
        'business_start',
        'bus_address',
        'bus_city',
        'bus_country',
        'job_title',
        'kin_address',
        'kin_city',
        'kin_relationship',
        'notes',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'natid'                              => 'string',
        'status'                              => 'boolean',
        'reviewer'                              => 'string',
        'authorizer'                              => 'string',
        'business_type'                              => 'string',
        'business_start'                              => 'date',
        'bus_address'                              => 'string',
        'bus_city'                              => 'string',
        'bus_country'                              => 'string',
        'job_title'                              => 'string',
        'kin_address'                              => 'string',
        'kin_city'                              => 'string',
        'kin_relationship'                              => 'string',
        'notes'                              => 'string',
    ];
}
