<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'localels';

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
        'country',
        'country_short',
        'currency_code',
        'currency_name',
        'symbol',
        'country_code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'country'                              => 'string',
        'country_short'                              => 'string',
        'currency_code'                        => 'string',
        'currency_name'                         => 'string',
        'symbol'                             => 'string',
        'country_code'                             => 'string',
    ];

    public function bank(){
        return $this->hasMany('App\Models\Bank', 'locale_id','id');
    }

}
