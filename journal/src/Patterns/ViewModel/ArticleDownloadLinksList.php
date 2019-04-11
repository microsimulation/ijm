<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class ArticleDownloadLinksList implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $id;
    private $description;
    private $groups;

    public function __construct(string $id, string $description, array $groups)
    {
        Assertion::notBlank($id);
        Assertion::notBlank($description);
        Assertion::notEmpty($groups);

        $this->id = $id;
        $this->description = $description;
        $this->groups = array_map(function (string $title, array $items) {
            Assertion::notBlank($title);
            Assertion::notEmpty($items);
            Assertion::allIsInstanceOf($items, Link::class);

            return [
                'title' => $title,
                'items' => $items,
            ];
        }, array_keys($groups), array_values($groups));
    }

    public function getTemplateName() : string
    {
        return 'patterns/article-download-links-list.mustache';
    }
}
