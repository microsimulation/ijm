<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class FormFieldInfoLink implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $name;
    private $url;

    public function __construct(
        string $name,
        string $url
    ) {
        Assertion::notBlank($name);
        Assertion::notBlank($url);

        $this->name = $name;
        $this->url = $url;
    }

    public function getTemplateName() : string
    {
        return 'patterns/form-field-info-link.mustache';
    }
}
