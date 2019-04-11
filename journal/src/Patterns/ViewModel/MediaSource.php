<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class MediaSource implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $src;
    private $mediaType;
    private $fallback;

    public function __construct(string $src, MediaType $mediaType, MediaSourceFallback $fallback = null)
    {
        Assertion::notBlank($src);

        $this->src = $src;
        $this->mediaType = $mediaType;
        $this->fallback = $fallback;
    }

    public function getTemplateName() : string
    {
        return 'patterns/media-source.mustache';
    }
}
