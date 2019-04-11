<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

/**
 * @SuppressWarnings(ForbiddenAbleSuffix)
 */
final class Table implements ViewModel, IsCaptioned
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $tables;
    private $hasFootnotes;
    private $footnotes;

    public function __construct(array $tables, array $footnotes = [])
    {
        Assertion::allRegex($tables, '/^<table>[\s\S]+<\/table>$/');
        Assertion::notEmpty($tables);
        Assertion::allIsInstanceOf($footnotes, TableFootnote::class);

        $this->tables = $tables;
        if (!empty($footnotes)) {
            $this->hasFootnotes = true;
            $this->footnotes = $footnotes;
        }
    }

    public function getTemplateName() : string
    {
        return 'patterns/table.mustache';
    }
}
