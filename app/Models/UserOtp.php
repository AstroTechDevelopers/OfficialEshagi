<?php

namespace App\Models;

//use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserOtp extends Model
{
    use HasFactory;
    //use Searchable;
    //use SoftDeletes;

    protected $fillable = [
        'user_otp',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'users_otp';

    protected $hidden = ['user_id'];    
}