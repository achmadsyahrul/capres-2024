<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self PRESIDEN()
 * @method static self WAKIL_PRESIDEN()
 */
class PosisiEnum extends Enum
{
    const PRESIDEN = 'PRESIDEN';

    const WAKIL_PRESIDEN = 'WAKIL_PRESIDEN';
}
