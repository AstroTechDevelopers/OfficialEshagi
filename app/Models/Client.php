<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;

class Client extends Authenticatable
{
    use HasRoleAndPermission;
    use Notifiable;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'clients';

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
        'title',
        'first_name',
        'last_name',
        'natid',
        'email',
        'mobile',
        'dob',
        'gender',
        'marital_state',
        'dependants',
		'children',
        'nationality',
        'employer_id',
        'emp_sector',
        'employer',
        'ecnumber',
		'designation',
        'gross',
        'salary',
		'emp_nature',
        'cred_limit',
        'usd_gross',
        'usd_salary',
        'usd_cred_limit',
        'house_num',
        'street',
        'surburb',
        'city',
        'province',
        'country',
		'res_duration',
        'locale_id',
        'home_type',
		'loan_purpose',
        'otp',
        'flag',
        'fsb_score',
        'fsb_status',
        'fsb_rating',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'creator'                              => 'string',
        'title'                              => 'string',
        'first_name'                        => 'string',
        'last_name'                         => 'string',
        'natid'                             => 'string',
        'email'                             => 'string',
        'mobile'                             => 'string',
        'dob'                             => 'date',
        'gender'                          => 'string',
        'marital_state'                          => 'string',
        'dependants'                          => 'integer',
		'children'                          => 'integer',
        'nationality'                          => 'string',
        'employer_id'                          => 'string',
        'emp_sector'                          => 'string',
        'employer'                          => 'string',
        'ecnumber'                          => 'string',
		'designation'                          => 'string',
        'gross'                          => 'double',
        'salary'                          => 'double',
		'emp_nature' => 'string',
        'cred_limit'                          => 'double',
        'usd_gross'                          => 'double',
        'usd_salary'                          => 'double',
        'usd_cred_limit'                          => 'double',
        'house_num'                          => 'string',
        'street'                          => 'string',
        'surburb'                          => 'string',
        'city'                          => 'string',
        'province'                          => 'string',
        'country'                          => 'string',
		'res_duration'                          => 'integer',
        'locale_id'                          => 'string',
        'home_type'                          => 'string',
		'loan_purpose'	=> 'string',
        'otp'                          => 'string',
        'flag'                          => 'string',
        'fsb_score'                          => 'string',
        'fsb_status'                          => 'string',
        'fsb_rating'                          => 'string',
    ];

    public function kyc()
    {
        return $this->hasOne(Kyc::class, 'user_id');
    }

}
