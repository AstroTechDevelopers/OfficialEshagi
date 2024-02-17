<?php

namespace App\Models;

//use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    //use Searchable;
    //use SoftDeletes;

    protected $fillable = [
        'category_name',
		'category_slug',
		'material_group_id',
		'added_by',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'product_categories';

    protected $hidden = ['user_id'];    
}