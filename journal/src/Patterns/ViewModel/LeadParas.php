<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class LeadParas implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    protected $paras;

    /**
     * @param $leadParas LeadPara[]
     */
    public function __construct(array $leadParas)
    {
        Assertion::notEmpty($leadParas);
        Assertion::allIsInstanceOf($leadParas, LeadPara::class);

        $this->paras = $leadParas;
    }

    public function getTemplateName() : string
    {
        return 'patterns/lead-paras.mustache';
    }
}
