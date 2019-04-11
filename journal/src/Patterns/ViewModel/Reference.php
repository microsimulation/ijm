<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class Reference implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $abstracts;
    private $authorLists;
    private $origin;
    private $doi;
    private $title;
    private $titleLink;
    private $hasAuthors;
    private $hasAbstracts;

    private function __construct(
        string $title,
        array $origin,
        string $titleLink = null,
        Doi $doi = null,
        array $authorLists = [],
        array $abstracts = []
    ) {
        Assertion::notBlank($title);
        Assertion::allString($origin);
        Assertion::allIsInstanceOf($authorLists, ReferenceAuthorList::class);
        Assertion::allIsInstanceOf($abstracts, Link::class);

        $this->titleLink = $titleLink;
        $this->title = $title;
        $this->doi = $doi;
        $this->origin = empty($origin) ? null : implode('. ', $origin).'.';
        $this->authorLists = $authorLists;
        $this->hasAuthors = !empty($authorLists);
        $this->abstracts = $abstracts;
        $this->hasAbstracts = !empty($abstracts);
    }

    public static function withDoi(
        string $title,
        Doi $doi,
        array $origin = [],
        array $authorLists = [],
        array $abstracts = []
    ) : Reference {
        return new self($title, $origin, null, $doi, $authorLists, $abstracts);
    }

    public static function withOutDoi(
        Link $title,
        array $origin = [],
        array $authorLists = [],
        array $abstracts = []
    ) : Reference {
        return new self($title['name'], $origin, $title['url'], null, $authorLists, $abstracts);
    }

    public function getTemplateName() : string
    {
        return 'patterns/reference.mustache';
    }
}
