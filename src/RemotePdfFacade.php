<?php

namespace Meevo\RemotePdf;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Meevo\RemotePdf\Skeleton\SkeletonClass
 */
class RemotePdfFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'remote-pdf';
    }
}
