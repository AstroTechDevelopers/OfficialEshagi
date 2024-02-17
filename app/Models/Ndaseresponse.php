<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ndaseresponse extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ndaseresponses';

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
        'recid',
        'deductioncode',
        'reference',
        'idnumber',
        'ecnumber',
        'type',
        'status',
        'startdate',
        'enddate',
        'amount',
        'total',
        'surname',
        'bank',
        'bankacc',
        'message',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'recid'                              => 'string',
        'deductioncode'                       => 'string',
        'reference'                              => 'string',
        'idnumber'                              => 'string',
        'ecnumber'                              => 'string',
        'type'                              => 'string',
        'status'                              => 'string',
        'startdate'                              => 'string',
        'enddate'                              => 'string',
        'amount'                              => 'string',
        'total'                              => 'string',
        'surname'                              => 'string',
        'bank'                              => 'string',
        'bankacc'                              => 'string',
        'message'                              => 'string',
    ];
}
