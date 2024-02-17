<?php

namespace App\Models;

//use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialsGroup extends Model
{
    use HasFactory;
    //use Searchable;
    //use SoftDeletes;

    protected $fillable = [
        'material_group_name',
		'material_slug',
		'material_group_image',
		'added_by',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'product_materials_group';

    protected $hidden = ['id'];    
}