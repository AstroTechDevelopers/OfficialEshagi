<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Query extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'queries';

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
        'opened_on',
        'resolved_on',
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
        'medium',
        'first_name',
        'last_name',
        'natid',
        'mobile',
        'status',
        'query',
        'agent',
        'opened_on',
        'action_taken',
        'resolved_on',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'medium'                              => 'string',
        'first_name'                        => 'string',
        'last_name'                         => 'string',
        'natid'                             => 'string',
        'mobile'                             => 'string',
        'status'                             => 'string',
        'query'                             => 'string',
        'agent'                             => 'string',
        'action_taken'                             => 'string',

    ];
}
