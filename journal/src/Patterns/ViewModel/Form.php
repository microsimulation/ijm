<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\CastsToArray;

final class Form implements CastsToArray
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $action;
    private $id;
    private $method;

    public function __construct(string $action, string $id, string $method)
    {
        Assertion::notBlank($action);
        Assertion::notBlank($id);
        Assertion::inArray($method, ['GET', 'POST']);

        $this->action = $action;
        $this->id = $id;
        $this->method = $method;
    }
}
