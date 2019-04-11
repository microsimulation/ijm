<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\HasDoi;
use Microsimulation\Journal\Patterns\ViewModel;

trait CreatesDoi
{
    /**
     * @return ViewModel\Doi|null
     */
    final private function createDoi(HasDoi $object)
    {
        return $object->getDoi() ? new ViewModel\Doi($object->getDoi()) : null;
    }
}
