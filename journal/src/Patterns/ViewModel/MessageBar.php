<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class MessageBar implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $message;

    public function __construct(string $message)
    {
        Assertion::notBlank($message);

        $this->message = $message;
    }

    public function getTemplateName() : string
    {
        return 'patterns/message-bar.mustache';
    }
}
