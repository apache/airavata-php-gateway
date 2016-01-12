<?php

namespace Airavata\DataManager\Facades;

use Illuminate\Support\Facades\Facade;

class DataManager extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'data-manager'; }

}