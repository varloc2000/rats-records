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
            'Home'      => __DIR__ . '/../src',
            // 'Lesson'    => __DIR__ . '/../src',
        );
    }
}