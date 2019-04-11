<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\CastsToArray;

final class TableFootnote implements CastsToArray
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $text;
    private $footnoteId;
    private $footnoteLabel;

    public function __construct(string $text, string $id = null, string $label = null)
    {
        Assertion::notBlank($text);

        $this->text = $text;
        $this->footnoteId = $id;
        $this->footnoteLabel = $label;
    }
}
