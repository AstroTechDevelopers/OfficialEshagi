<?php

namespace App\Models;

use App\Scopes\ProductScope;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    protected static function booted()
    {
        static::addGlobalScope(new ProductScope());
    }
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class,'product_category_id', 'id');
    }
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';
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
        'creator',
        'loandevice', //is this a device available for loan
        'pcode', //Product code
        'serial',
        'pname', //Product name
        'model',
        'descrip',
        'price',
        'partner_id',
		'product_category_id',
		'product_image',
        'img_1',
        'img_2',
        'img_3',
        'img_4'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                                => 'integer',
        'loandevice'                              => 'boolean',
        'creator'                              => 'string',
        'pcode'                        => 'string',
        'serial'                         => 'string',
        'pname'                             => 'string',
        'model'                             => 'string',
        'descrip'                             => 'string',
        'price'                             => 'double',
        'partner_id' => 'integer',
		'product_category_id' => 'integer',
		'product_image'=> 'string',
    ];
    public function getPriceWithCurrencyAttribute()
    {
        return $this->partner->localel->symbol . ' ' . $this->price;
    }
    public static function getOrderTotal(Array $products){
        $sum = 0 ;
        foreach ($products as $product)
            $sum += $product->price;

        return $sum;
    }
    public static function getCommission(Array $products)
    {
        return 0.03 * self::getOrderTotal($products);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class,'id', 'product_id');
    }
    public function locale()
    {
        return $this->belongsTo(Partner::class,'locale_id','id');
    }
}

