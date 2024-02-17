<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZwmbOtherBanking extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'zwmb_other_bankings';

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
        'user_id',
        'zwmb_id',
        'bank',
        'branch',
        'account_number',
        'years',
        'notes',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'user_id'                              => 'string',
        'zwmb_id'                              => 'string',
        'bank'                              => 'string',
        'branch'                              => 'string',
        'account_number'                              => 'string',
        'years'                              => 'string',
        'notes'                              => 'string',
    ];
}
