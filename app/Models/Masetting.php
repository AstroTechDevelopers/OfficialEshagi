<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Masetting extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'masettings';

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
        'interest',
        'self_interest',
        'device_interest',
        'weekly_target',
        'creditRate',
        'usd_creditRate',
        'om_interest',
        'usd_interest',
        'loan_interest_method',
        'leads_allocation',
        'fcb_username',
        'fcb_password',
        'reds_username',
        'reds_password',
        'ndas_username',
        'ndas_password',
        'crb_infinity_code',
        'crb_username',
        'crb_password',
        'signing_ceo',
        'ceo_encoded_signature',
        'cbz_authorizer',
        'device_penalty',
        'loan_penalty',
        'zam_dev_upfront_fee',
        'bulksmsweb_baseurl',
        'last_changed_by',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'interest'                         => 'double',
        'self_interest'                         => 'double',
        'device_interest'                         => 'double',
        'creditRate'                         => 'double',
        'usd_creditRate'                         => 'double',
        'om_interest'                         => 'double',
        'usd_interest'                         => 'double',
        'loan_interest_method'                         => 'string',
        'weekly_target'                         => 'string',
        'leads_allocation'                         => 'string',
        'fcb_username'                         => 'string',
        'fcb_password'                         => 'string',
        'reds_username'                         => 'string',
        'reds_password'                         => 'string',
        'ndas_username'                         => 'string',
        'ndas_password'                         => 'string',
        'crb_infinity_code'                         => 'string',
        'crb_username'                         => 'string',
        'crb_password'                         => 'string',
        'signing_ceo'                         => 'string',
        'ceo_encoded_signature'                         => 'string',
        'cbz_authorizer'                         => 'string',
        'device_penalty'                         => 'double',
        'loan_penalty'                         => 'double',
        'zam_dev_upfront_fee'                         => 'double',
        'bulksmsweb_baseurl'                         => 'string',
        'last_changed_by'                         => 'string',
    ];
}
