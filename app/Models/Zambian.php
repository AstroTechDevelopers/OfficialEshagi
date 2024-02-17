<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;

class Zambian extends Model
{
    use HasRoleAndPermission;
    use Notifiable;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'zambians';

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
        'password',
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
        'creator',
        'ld_borrower_id',
        'officer_stat',
        'officer',
        'manager_stat',
        'manager',
        'title',
        'first_name',
        'middle_name',
        'last_name',
        'nrc',
        'email',
        'mobile',
        'dob',
        'gender',
        'business_employer',
        'address',
        'city',
        'province',
        'zip_code',
        'landline',
        'work_status',
        'credit_score',
        'pass_photo',
        'nrc_pic',
        'por_pic',
        'pslip_pic',
        'sign_pic',
        'description',
        'files',
        'loan_officer_access',
        'institution',
        'ec_number',
        'department',
        'kin_name',
        'kin_relationship',
        'kin_address',
        'kin_number',
        'bank_name',
        'bank_account',
        'branch',
        'cred_limit',
        'otp',
        'savings_acc',
        'savings_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'creator'                              => 'string',
        'ld_borrower_id'                              => 'string',
        'officer_stat'                              => 'boolean',
        'officer'                              => 'string',
        'manager_stat'                              => 'boolean',
        'manager'                              => 'string',
        'title'                              => 'integer',
        'first_name'                        => 'string',
        'middle_name'                        => 'string',
        'last_name'                         => 'string',
        'nrc'                             => 'string',
        'email'                             => 'string',
        'mobile'                             => 'string',
        'dob'                             => 'date',
        'gender'                          => 'string',
        'business_employer'                          => 'string',
        'address'                          => 'string',
        'city'                          => 'string',
        'province'                          => 'string',
        'zip_code'                          => 'string',
        'landline'                          => 'string',
        'work_status'                          => 'string',
        'credit_score'                          => 'string',
        'pass_photo'                          => 'string',
        'nrc_pic'                          => 'string',
        'por_pic'                          => 'string',
        'pslip_pic'                          => 'string',
        'sign_pic'                          => 'string',
        'description'                          => 'string',
        'files'                          => 'string',
        'loan_officer_access'                          => 'string',
        'institution'                          => 'string',
        'ec_number'                          => 'string',
        'department'                          => 'string',
        'kin_name'                          => 'string',
        'kin_relationship'                          => 'string',
        'kin_address'                          => 'string',
        'kin_number'                          => 'string',
        'bank_name'                          => 'string',
        'bank_account'                          => 'string',
        'branch'                          => 'string',
        'cred_limit'                          => 'double',
        'otp'                          => 'string',
        'savings_acc'                          => 'string',
        'savings_id'                          => 'string',

    ];
}
