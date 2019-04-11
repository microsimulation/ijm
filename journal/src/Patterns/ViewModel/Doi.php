<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class Doi implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    const ARTICLE_SECTION = 'article-section';
    const ASSET = 'asset';

    private $doi;

    public function __construct(string $doi)
    {
        Assertion::regex($doi, '~^10[.][0-9]{4,}[^\s"/<>]*/[^\s"]+$~');
        $this->doi = $doi;
    }

    public function getTemplateName() : string
    {
        return 'patterns/doi.mustache';
    }
}
