<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\CastsToArray;

final class ReferenceAuthorList implements CastsToArray
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $authors;
    private $suffix;

    public function __construct(array $authors, string $suffix)
    {
        //Assertion::notEmpty($authors);
        Assertion::allIsInstanceOf($authors, Author::class);
        // suffix will be blank when no date is available
        //Assertion::notBlank($suffix);

        $this->authors = $authors;
        $this->suffix = $suffix;
    }
}
