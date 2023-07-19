<?php

namespace Rafi021\RepositoryPattern\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Rafi021\RepositoryPattern\RepositoryPattern
 */
class RepositoryPattern extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Rafi021\RepositoryPattern\RepositoryPattern::class;
    }
}
