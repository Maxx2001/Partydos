<?php

namespace Support\Traits;

trait Creatable
{
	public static function create(...$args){
        return new static(...$args);
    }
}
