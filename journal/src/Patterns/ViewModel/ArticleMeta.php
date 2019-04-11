<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class ArticleMeta implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $groups;

    public function __construct(array $groups)
    {
        Assertion::notEmpty($groups);
        Assertion::allIsArray($groups);

        $this->groups = array_values(array_map(function (string $title, array $items) {
            Assertion::notEmpty($items);
            Assertion::allIsInstanceOf($items, Link::class);

            return [
                'title' => $title,
                'items' => $items,
            ];
        }, array_keys($groups), array_values($groups)));
    }

    public function getTemplateName() : string
    {
        return 'patterns/article-meta.mustache';
    }
}
