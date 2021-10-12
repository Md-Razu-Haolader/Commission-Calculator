<?php

declare(strict_types=1);

namespace App\Facades;

use App\Helpers\Common;

class CommonHelper extends Facade
{
    /**
     * Get the registered name of the helper facade.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return new Common();
    }
}
