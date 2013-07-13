<?php

use Varloc\Framework\Kernel as FrameworkKernel;

class Kernel extends FrameworkKernel
{
    /**
     * {@inheritDoc}
     */
    public function getNamespacesToLoad()
    {
        return array(
            'Cms'       => __DIR__ . '/../src',
            'Home'      => __DIR__ . '/../src',
            'Lesson'    => __DIR__ . '/../src',
        );
    }
}