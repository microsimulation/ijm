<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class InfoBar implements ViewModel
{
    const TYPE_ATTENTION = 'attention';
    const TYPE_INFO = 'info';
    const TYPE_SUCCESS = 'success';
    const TYPE_CORRECTION = 'correction';
    const TYPE_MULTIPLE_VERSIONS = 'multiple-versions';
    const TYPE_WARNING = 'warning';

    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $text;
    private $type;

    public function __construct(string $text, string $type = self::TYPE_INFO)
    {
        Assertion::notBlank($text);
        Assertion::choice($type, [
            self::TYPE_ATTENTION,
            self::TYPE_INFO,
            self::TYPE_SUCCESS,
            self::TYPE_CORRECTION,
            self::TYPE_MULTIPLE_VERSIONS,
            self::TYPE_WARNING,
        ]);

        $this->text = $text;
        $this->type = $type;
    }

    public function getTemplateName() : string
    {
        return 'patterns/info-bar.mustache';
    }
}
