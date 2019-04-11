<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\CastsToArray;

final class ReferenceListItem implements CastsToArray
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $bibId;
    private $reference;

    public function __construct(string $id, int $ordinal, Reference $reference)
    {
        Assertion::notBlank($id);
        Assertion::min($ordinal, 1);

        $this->bibId = [
            'full' => $id,
            'ordinal' => $ordinal,
        ];
        $this->reference = $reference;
    }
}
