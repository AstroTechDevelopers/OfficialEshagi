<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'email_log';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

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
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'from',
        'to',
        'cc',
        'bcc',
        'subject',
        'body',
        'headers',
        'attachments',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'date'                              => 'datetime',
        'from'                              => 'string',
        'to'                              => 'string',
        'cc'                              => 'string',
        'bcc'                              => 'string',
        'subject'                              => 'string',
        'body'                              => 'text',
        'headers'                              => 'text',
        'attachments'                              => 'longtext',
    ];
}
