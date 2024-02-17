<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zwmb extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'zwmbs';

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
        'reviewer',
        'checked_by',
        'fcb_stat',
        'authorized',
        'customer_number',
        'account_number',
        'user_id',
        'natid',
        'agent',
        'account_type',
        'maiden_name',
        'passport_number',
        'driver_licence',
        'race',
        'occupation',
        'employer_name',
        'employer_contact_person',
        'designation',
        'nature_employer',
        'kin_relationship',
        'kin_address',
        'mobile_banking_num',
        'mobile_banking',
        'internet_banking',
        'sms_alerts',
        'bank_card_local',
        'proof_of_res',
        'proof_of_res_stat',
        'proof_of_income',
        'proof_of_income_stat',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'reviewer'                              => 'string',
        'checked_by'                              => 'string',
        'fcb_stat'                              => 'string',
        'authorized'                              => 'string',
        'customer_number'                              => 'string',
        'account_number'                              => 'string',
        'user_id'                              => 'string',
        'natid'                        => 'string',
        'agent'                        => 'string',
        'account_type'                         => 'string',
        'maiden_name'                             => 'string',
        'passport_number'                             => 'string',
        'driver_licence'                             => 'string',
        'race'                             => 'string',
        'occupation'                          => 'string',
        'employer_name'                          => 'string',
        'employer_contact_person'                          => 'string',
        'designation'                          => 'string',
        'nature_employer'                          => 'string',
        'kin_relationship'                          => 'string',
        'kin_address'                          => 'string',
        'mobile_banking_num'                          => 'string',
        'mobile_banking'                          => 'boolean',
        'internet_banking'                          => 'boolean',
        'sms_alerts'                          => 'boolean',
        'bank_card_local'                          => 'boolean',
        'proof_of_res'                          => 'string',
        'proof_of_res_stat'                          => 'boolean',
        'proof_of_income'                          => 'string',
        'proof_of_income_stat'                          => 'boolean',

    ];
}
