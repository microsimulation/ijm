<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\CastsToArray;

final class Image implements CastsToArray, IsCaptioned
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $altText;
    private $defaultPath;
    private $srcset;

    public function __construct(string $defaultPath, array $srcset = [], string $altText = '')
    {
        Assertion::notBlank($defaultPath);
        Assertion::allNumeric(array_keys($srcset));
        if (!empty($srcset)) {
            Assertion::inArray(1, array_keys($srcset));
        }
        Assertion::allNotBlank($srcset);

        $this->defaultPath = $defaultPath;
        $this->srcset = [];
        if ($srcset) {
            foreach ($srcset as $dpiMultiple => $src) {
                $this->srcset[] = $src.' '.$dpiMultiple.'x';
            }
            $this->srcset = implode(', ', $this->srcset);
        }
        $this->altText = $altText;
    }
}
