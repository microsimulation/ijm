<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class ViewSelector implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $articleUrl;
    private $jumpLinks;
    private $figureUrl;
    private $figureIsActive;
    private $sideBySideUrl;

    public function __construct(
        string $articleUrl,
        array $jumpLinks = [],
        string $figureUrl = null,
        bool $figureIsActive = false,
        string $sideBySideUrl = null
    ) {
        Assertion::notBlank($articleUrl);
        Assertion::allIsInstanceOf($jumpLinks, Link::class);
        if (count($jumpLinks) > 0) {
            Assertion::min(count($jumpLinks), 2);
        }

        $this->articleUrl = $articleUrl;
        if (count($jumpLinks) > 0) {
            $this->jumpLinks = ['links' => $jumpLinks];
        }
        $this->figureUrl = $figureUrl;
        if ($this->figureUrl && $figureIsActive) {
            $this->figureIsActive = $figureIsActive;
        }
        $this->sideBySideUrl = $sideBySideUrl;
    }

    public function getTemplateName() : string
    {
        return 'patterns/view-selector.mustache';
    }
}
