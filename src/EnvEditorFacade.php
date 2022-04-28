<?php

namespace Kineticamobile\EnvEditor;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kineticamobile\EnvEditor\Skeleton\SkeletonClass
 */
class EnvEditorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-env-editor';
    }
}
