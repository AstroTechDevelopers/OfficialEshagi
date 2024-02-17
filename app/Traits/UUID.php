<?php
/**
 * Created by PhpStorm for eshagitwo
 * User: VinceGee
 * Date: 8/14/2022
 * Time: 5:24 PM
 */

namespace App\Traits;

use Illuminate\Support\Str;

trait UUID
{
    protected static function boot ()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->getKey() === null) {
                $model->setAttribute($model->getKeyName(), Str::uuid()->toString());
            }
        });
    }

    public function getIncrementing ()
    {
        return false;
    }

    public function getKeyType ()
    {
        return 'string';
    }
}

