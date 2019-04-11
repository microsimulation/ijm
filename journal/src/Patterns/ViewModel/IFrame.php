<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class IFrame implements ViewModel, IsCaptioned
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $src;
    private $allowFullScreen;
    private $paddingBottom;

    public function __construct(string $src, int $width, int $height, bool $allowFullScreen = true)
    {
        Assertion::notBlank($src);
        Assertion::min($width, 1);
        Assertion::min($height, 1);

        $this->src = $src;
        $this->paddingBottom = ($height / $width) * 100;
        $this->allowFullScreen = $allowFullScreen;
    }

    public function getTemplateName() : string
    {
        return 'patterns/iframe.mustache';
    }
}
