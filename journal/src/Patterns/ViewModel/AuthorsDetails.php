<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class AuthorsDetails implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $authorDetails;

    public function __construct(AuthorDetails ...$authorDetails)
    {
        Assertion::notBlank($authorDetails);

        $this->authorDetails = $authorDetails;
    }

    public function getTemplateName() : string
    {
        return 'patterns/authors-details.mustache';
    }
}
