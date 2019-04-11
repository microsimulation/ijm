<?php

namespace Microsimulation\Journal\Patterns;

interface ViewModel extends CastsToArray
{
    public function getTemplateName() : string;
}
