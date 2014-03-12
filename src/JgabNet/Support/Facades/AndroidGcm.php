<?php namespace JgabNet\Support\Facades;
/**
 * User: jgab
 * Date: 10/03/14
 * Time: 22:13
 */

use Illuminate\Support\Facades\Facade;

class AndroidGcm extends Facade{

    protected static function getFacadeAccessor(){
        return 'androidgcm';
    }

} 