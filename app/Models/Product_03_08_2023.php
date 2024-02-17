<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

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
        'loandevice', //is this a device available for loan
        'pcode', //Product code
        'serial',
        'pname', //Product name
        'model',
        'descrip',
        'price',
        'usd_price',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'loandevice'                              => 'boolean',
        'creator'                              => 'string',
        'pcode'                        => 'string',
        'serial'                         => 'string',
        'pname'                             => 'string',
        'model'                             => 'string',
        'descrip'                             => 'string',
        'price'                             => 'double',
        'usd_price'                             => 'double',
    ];
}
