<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodexchange extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'prodexchanges';

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
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usd_rate',
        'rand_rate',
        'rtgs_rate',
        'changed_by',
        'user_id',
        'partner_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'usd_rate'                              => 'double',
        'rand_rate'                        => 'double',
        'rtgs_rate'                         => 'double',
        'changed_by'                             => 'string',
        'user_id'                             => 'string',
        'partner_id'                             => 'string',
    ];
}
