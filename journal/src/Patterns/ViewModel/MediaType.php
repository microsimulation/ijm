<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\CastsToArray;

final class MediaType implements CastsToArray
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $forMachine;
    private $forHuman;

    public function __construct(string $mediaType)
    {
        Assertion::regex($mediaType,
            '/^([a-zA-Z0-9!#$%^&\*_\-\+{}\|\'.`~]+\/[a-zA-Z0-9!#$%^&\*_\-\+{}\|\'.`~]+)(; *[a-zA-Z0-9!#$%^&\*_\-\+{}\|\'.`~]+=(([a-zA-Z0-9\.\-]+)|(".+")))*$/');

        $this->forMachine = $mediaType;
        $this->forHuman = $this->guessHumanType();
    }

    private function guessHumanType()
    {
        $parts = explode(';', $this->forMachine);
        $parts = explode('+', $parts[0]);

        switch ($parts[0]) {
            case 'image/pjpeg':
                return 'JPEG';
            case 'audio/mp4':
            case 'video/mp4':
                return 'MPEG-4';
            case 'application/ogg':
            case 'audio/ogg':
            case 'video/ogg':
                return 'Ogg';
            case 'audio/webm':
            case 'video/webm':
                return 'WebM';
            case 'image/webp':
                return 'WebP';
        }

        $parts = explode('/', $parts[0]);

        return strtoupper($parts[1]);
    }
}
