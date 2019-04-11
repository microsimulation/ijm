<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class Video implements ViewModel, IsCaptioned
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $posterFrame;
    /** @var MediaSource[] */
    private $sources;
    private $autoplay;
    private $loop;

    public function __construct(array $sources, string $posterFrame = null, bool $autoplay = false, bool $loop = false)
    {
        Assertion::notEmpty($sources);
        Assertion::allIsInstanceOf($sources, MediaSource::class);
        Assertion::allTrue(array_map(function (MediaSource $mediaSource) {
            return 0 === strpos($mediaSource['mediaType']['forMachine'], 'video');
        }, $sources), 'All sources must be video types.');

        $this->posterFrame = $posterFrame;
        $this->sources = $sources;
        if ($autoplay) {
            $this->autoplay = $autoplay;
        }
        if ($loop) {
            $this->loop = $loop;
        }
    }

    public function getTemplateName() : string
    {
        return 'patterns/video.mustache';
    }
}
