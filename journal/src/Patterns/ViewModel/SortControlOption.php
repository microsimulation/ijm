<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\CastsToArray;

final class SortControlOption implements CastsToArray
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    const ASC = 'ascending';
    const DESC = 'descending';

    private $option;
    private $url;
    private $sorting;

    public function __construct(Link $link, string $sorting = null)
    {
        Assertion::nullOrInArray($sorting, [self::ASC, self::DESC]);

        $this->option = $link['name'];
        $this->url = $link['url'];
        $this->sorting = $sorting;
    }
}
