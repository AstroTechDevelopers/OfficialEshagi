<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZambiaPayment extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payments';

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
        'creator',
        'locale',
        'ld_repayment_id',
        'ld_loan_id',
        'loan_id',
        'amount',
        'payment_method',
        'collector_id',
        'collection_date',
        'rem_schedule',
        'description',
        'reference_num',
        'isValid',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'creator'                              => 'string',
        'locale'                              => 'string',
        'ld_repayment_id'                              => 'string',
        'ld_loan_id'                              => 'string',
        'loan_id'                              => 'string',
        'amount'                        => 'decimal:2',
        'payment_method'                         => 'string',
        'collector_id'                             => 'string',
        'collection_date'                             => 'date',
        'rem_schedule'                             => 'string',
        'description'                             => 'string',
        'reference_num'                             => 'string',
        'isValid'                             => 'boolean',
    ];
}
