<?php

namespace App\enums;

use App\Enum\Enum;
use App\Models\User;


class UserStatus extends Enum
{
    private const NewReg  = 0b00001;
    private const GroupPastor    = 0b00010;

    public static function canCreate(User $user, $onCreate = false)
    {
        $types = static::toValueKeyArray()->reverse();

        if($user->type == static::ZonalAdministrationAdmin())
        {
            unset($types[0b0100]);

            return $types;
        }

        if($user->type == static::ZonalPartnershipAdmin() && $onCreate)
        {
            unset($types[0b0101]);
        }

        return $types->filter(function ($value) use ($user) {
            return $value->getValue() <= $user->type->getValue();
        });
    }
}
