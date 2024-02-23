<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;

class Partner extends Model
{

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected function localel()
    {
       return $this->belongsTo(Localel::class, 'locale_id', 'id');
    }

    use HasRoleAndPermission;
    use Notifiable;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'partners';

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
        'partner_name',
        'partner_type',
        'merchantname',
        'business_type',
        'partnerDesc',
        'yearsTrading',
        'regNumber',
        'bpNumber',
		'is_vat_registered',
        'propNumber',
        'street',
        'suburb',
        'city',
        'province',
        'country',
        'locale_id',
        'cfullname',
        'cdesignation',
        'telephoneNo',
        'cemail',
        'bank',
        'branch',
        'acc_number',
        'branch_code',
        'password',
		'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'partner_name'                              => 'string',
        'partner_type'                        => 'string',
        'merchantname'                         => 'string',
        'business_type'                             => 'string',
        'partnerDesc'                             => 'string',
        'yearsTrading'                             => 'string',
        'regNumber'                             => 'string',
        'bpNumber'                          => 'string',
		'is_vat_registered'                          => 'string',
        'propNumber'                          => 'string',
        'street'                          => 'string',
        'city'                          => 'string',
        'province'                          => 'string',
        'country'                          => 'string',
        'locale_id'                          => 'string',
        'cfullname'                          => 'string',
        'cdesignation'                          => 'string',
        'telephoneNo'                          => 'string',
        'cemail'                          => 'string',
        'bank'                          => 'string',
        'branch'                          => 'string',
        'acc_number'                          => 'string',
        'branch_code'                          => 'string',
        'password'                          => 'string',
		'status' => 'boolean',

    ];
}
