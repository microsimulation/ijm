<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class ProfileSnippet implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $image;
    private $title;
    private $name;

    public function __construct(string $name, string $title, Picture $image = null)
    {
        Assertion::notBlank($name);
        Assertion::notBlank($title);

        $this->name = $name;
        $this->title = $title;
        $this->image = $image;
    }

    public function getTemplateName() : string
    {
        return 'patterns/profile-snippet.mustache';
    }
}
