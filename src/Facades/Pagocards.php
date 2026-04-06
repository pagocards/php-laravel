
<?php

namespace Pagocards\SDK\Facades;

use Illuminate\Support\Facades\Facade;

class Pagocards extends Facade
{
    /**
     * Get the registered name of the component
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pagocards';
    }
}

