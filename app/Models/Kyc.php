<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kyc extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'kycs';

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
        'user_id',
        'natid',
        'status',
        'reviewer',
        'cbz_status', //0 = Not reviewed by CBZ; 1 = Approved By CBZ; 2 = Rejected;
        'cbz_evaluator',
        'kyc_notes',
        'national_pic',
        'national_stat',
        'passport',
        'passport_pic',
        'passport_stat',
        'dlicence',
        'dlicence_pic',
        'dlicence_stat',
        'proofres',
        'proofres_pic',
        'proofres_stat',
        'payslip_num',
        'payslip_pic',
        'payslip_stat',
        'sign_id',
        'sign_pic',
        'sign_stat',
		'emp_approval_letter',
        'emp_approval_stat',
        'kin_title',
        'kin_fname',
        'kin_lname',
        'kin_email',
        'kin_work',
        'kin_number',
		'relationship',
        'bank',
        'bank_acc_name',
        'branch',
        'branch_code',
        'acc_number',
		'house_num',
        'street',
        'surburb',
        'city',
        'province',
        'country',
		'home_type',
		'res_duration',
		'reject_reason',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'natid'                              => 'string',
        'status'                              => 'boolean',
        'reviewer'                              => 'string',
        'cbz_status'                              => 'string',
        'cbz_evaluator'                              => 'string',
        'kyc_notes'                              => 'string',
        'national_pic'                              => 'string',
        'national_stat'                              => 'boolean',
        'passport'                              => 'string',
        'passport_pic'                              => 'string',
        'passport_stat'                              => 'boolean',
        'dlicence'                              => 'string',
        'dlicence_pic'                              => 'string',
        'dlicence_stat'                              => 'boolean',
        'proofres'                              => 'string',
        'proofres_pic'                              => 'string',
        'proofres_stat'                              => 'boolean',
        'payslip_num'                              => 'string',
        'payslip_pic'                              => 'string',
        'payslip_stat'                              => 'boolean',
        'sign_id'                              => 'string',
        'sign_pic'                              => 'string',
        'sign_stat'                              => 'string',
		'emp_approval_letter' => 'string',
        'emp_approval_stat' => 'boolean',
        'kin_title'                              => 'string',
        'kin_fname'                              => 'string',
        'kin_lname'                              => 'string',
        'kin_email'                              => 'string',
        'kin_work'                              => 'string',
        'kin_number'                              => 'string',
		'relationship' => 'string',
        'bank'                              => 'string',
        'bank_acc_name'                              => 'string',
        'branch'                              => 'string',
        'branch_code'                              => 'string',
        'acc_number'                              => 'string',
		'house_num'                          => 'string',
        'street'                          => 'string',
        'surburb'                          => 'string',
        'city'                          => 'string',
        'province'                          => 'string',
        'country'                          => 'string',
		'home_type'                          => 'string',
		'res_duration'                          => 'integer',
		'reject_reason' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
  public function bankName()
  {
      return $this->belongsTo(Bank::class, 'bank', 'id');
  }
}
