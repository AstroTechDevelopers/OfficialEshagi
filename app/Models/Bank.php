<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'banks';

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
        'locale_id',
        'bank',
        'bank_short',
        'bank_post_address',
        'bank_city',
        'bank_telephone',

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'locale_id'                              => 'string',
        'bank'                              => 'string',
        'bank_short'                              => 'string',
        'bank_post_address'                              => 'string',
        'bank_city'                              => 'string',
        'bank_telephone'                              => 'string',
    ];

    public function localel() {
        return $this->belongsTo('App\Models\Localel', 'id', 'locale_id');
    }
}
