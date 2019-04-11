<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class Box implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $id;
    private $label;
    private $title;
    private $headingLevel;
    private $doi;
    private $content;

    public function __construct(string $id = null, string $label = null, string $title, int $headingLevel, Doi $doi = null, string $content)
    {
        Assertion::notBlank($title);
        Assertion::range($headingLevel, 1, 6);
        Assertion::notBlank($content);

        $this->id = $id;
        $this->label = $label;
        $this->title = $title;
        $this->headingLevel = $headingLevel;
        $this->doi = $doi;
        $this->content = $content;
    }

    public function getTemplateName() : string
    {
        return 'patterns/box.mustache';
    }
}
