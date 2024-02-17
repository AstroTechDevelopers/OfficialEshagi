<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Astrogent extends Model
{
    use Notifiable;
    use SoftDeletes;
    use HasApiTokens;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'astrogents';

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
        'activated',
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
        'title',
        'name',
        'first_name',
        'last_name',
        'gender',
        'email',
        'natid',
        'mobile',
        'otp',
        'bank_acc_name',
        'bank',
        'branch',
        'branch_code',
        'accountNumber',
        'address',
        'reviewer',
        'locale',
        'natidUpload',
        'natid',
        'signUpload',
        'signature',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'title'                              => 'string',
        'name'                              => 'string',
        'first_name'                        => 'string',
        'last_name'                         => 'string',
        'gender'                         => 'string',
        'email'                             => 'string',
        'natid'                             => 'string',
        'mobile'                             => 'string',
        'otp'                          => 'string',
        'bank_acc_name'                          => 'string',
        'bank'                          => 'string',
        'branch'                          => 'string',
        'branch_code'                          => 'string',
        'accountNumber'                          => 'string',
        'address'                          => 'string',
        'activated'                         => 'boolean',
        'reviewer'                         => 'string',
        'locale'                             => 'string',
        'natidUpload'                   => 'boolean',
        'natidPic'                      => 'string',
        'signUpload'                    => 'boolean',
        'signaturePic'                  => 'string',
    ];

}
