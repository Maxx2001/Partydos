<?php

namespace Support\Traits;

trait Creatable
{
	/**
	 * @param mixed ...$args
	 * @return static
	 */
	public static function create(...$args): static {
        /** @phpstan-ignore-next-line */
        return new static(...$args);
    }
}
