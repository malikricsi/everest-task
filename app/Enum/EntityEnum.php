<?php

namespace App\Enum;

class EntityEnum
{
    public const STATE_ACTIVE = 'active';
    public const STATE_DELETED = 'deleted';

    public const STATES = [
        self::STATE_ACTIVE,
        self::STATE_DELETED,
    ];

    public const OPERATION_TYPE_STORE = 'store';
    public const OPERATION_TYPE_UPDATE = 'update';
}
