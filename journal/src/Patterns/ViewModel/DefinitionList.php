<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use function array_values;
use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class DefinitionList implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $items;
    private $inline;

    public function __construct(array $items, bool $inline = false)
    {
        Assertion::notEmpty($items);
        Assertion::allNotEmpty($items);

        $this->items = array_map(function (string $term, $descriptors) {
            $descriptors = (array) $descriptors;

            Assertion::allString($descriptors);

            return [
                'term' => $term,
                'descriptors' => $descriptors,
            ];
        }, array_keys($items), array_values($items));
        if ($inline) {
            $this->inline = $inline;
        }
    }

    public function getTemplateName() : string
    {
        return 'patterns/definition-list.mustache';
    }
}
