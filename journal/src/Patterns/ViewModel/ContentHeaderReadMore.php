<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class ContentHeaderReadMore implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $title;
    private $longTitle;
    private $url;
    private $hasSubjects;
    private $subjects;
    private $authorLine;
    private $meta;

    public function __construct(
        string $title,
        string $url,
        array $subjects = [],
        string $authorLine = null,
        Meta $meta = null
    ) {
        Assertion::notBlank($title);
        Assertion::notBlank($url);
        Assertion::allIsInstanceOf($subjects, Link::class);

        $this->title = $title;
        if (strlen(strip_tags($title)) >= 20) {
            $this->longTitle = true;
        }
        $this->url = $url;
        if ($subjects) {
            $this->hasSubjects = true;
            $this->subjects = $subjects;
        }
        $this->authorLine = $authorLine;
        $this->meta = $meta;
    }

    public function getTemplateName() : string
    {
        return 'patterns/content-header-read-more.mustache';
    }
}
