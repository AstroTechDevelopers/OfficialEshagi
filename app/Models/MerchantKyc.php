<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchantKyc extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'merchant_kycs';

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
        'partner_id',
        'natid',
        'loan_officer',
        'approver',
        'manager',
        'manager_approver',
        'cert_incorp',
        'cert_incorp_stat',
		'cr14',
        'cr14_stat',
        'bus_licence',
        'bus_licence_stat',
        'other',
        'other_stat',
        'notes',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'partner_id'                              => 'string',
        'natid'                              => 'string',
        'loan_officer'                              => 'boolean',
        'approver'                              => 'string',
        'manager'                              => 'boolean',
        'manager_approver'                              => 'string',
        'cert_incorp'                              => 'string',
        'cert_incorp_stat'                              => 'boolean',
		'cr14'                              => 'string',
        'cr14_stat'                              => 'boolean',
        'bus_licence'                              => 'string',
        'bus_licence_stat'                              => 'boolean',
        'other'                              => 'string',
        'other_stat'                              => 'boolean',
        'notes'                              => 'string',
    ];
}
