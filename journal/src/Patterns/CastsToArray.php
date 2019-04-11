<?php

namespace Microsimulation\Journal\Patterns;

use ArrayAccess;

interface CastsToArray extends ArrayAccess
{
    public function toArray() : array;
}
