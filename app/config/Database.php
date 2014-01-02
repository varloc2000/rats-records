<?php

namespace Configurations;

abstract class DatabaseConfig
{
    public function getDBName()
    {
        return 'ratsreco_storage';
    }

    public function getDBUser()
    {
        return 'varloc2000';
    }

    public function getDBPassword()
    {
        return '';
    }
}