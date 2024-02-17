<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SsbDetail extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ssb_details';

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
    protected $hidden = [];

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
     * Fillable fields for a Profile.
     *
     * @var array
     */
    protected $fillable = [
        'natid',
        'profession',
        'sourcesOfIncome',
        'currentNetSalary',
        'grossSalary',
        'hr_contact_name',
        'hr_position',
        'hr_email',
        'hr_telephone',
        'ecnumber',
        'payrollAreaCode',
        'dateJoined',
        'accountType',
        'yearsWithCurrentBank',
        'otherBankAccountName',
        'otherBankAccountNumber',
        'otherBankName',
        'bankStatement',
        'highestQualification',
        'maidenSurname',
        'offerLetter',
        'proofOfRes',
        'proofOfResStatus',
        'spouseEmployer',
        'spouseName',
        'spousePhoneNumber',

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'            => 'integer',
        'natid'          => 'string',
        'profession'          => 'string',
        'sourcesOfIncome'         => 'string',
        'currentNetSalary'        => 'double',
        'grossSalary'     => 'double',
        'hr_contact_name'   => 'string',
        'hr_position' => 'string',
        'hr_email' => 'string',
        'hr_telephone' => 'string',
        'ecnumber' => 'string',
        'payrollAreaCode' => 'string',
        'dateJoined' => 'date',
        'accountType' => 'string',
        'yearsWithCurrentBank' => 'string',
        'otherBankAccountName' => 'string',
        'otherBankAccountNumber' => 'string',
        'otherBankName' => 'string',
        'bankStatement' => 'string',
        'highestQualification' => 'string',
        'maidenSurname' => 'string',
        'offerLetter' => 'string',
        'proofOfRes' => 'string',
        'proofOfResStatus' => 'boolean',
        'spouseEmployer' => 'string',
        'spouseName' => 'string',
        'spousePhoneNumber' => 'string',
    ];
}
