<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApproval extends Model
{
    use HasFactory, UUID;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'loan_approvals';

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
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'loan_id',
        'user_id',
        'locale',
        'otp',
        'verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'loan_id' => 'string',
        'user_id' => 'string',
        'locale' => 'string',
        'otp' => 'string',
        'verified' => 'string',
    ];
}
