<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class CaptionText implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $heading;
    private $standfirst;
    private $text;

    private function __construct(string $heading = null, string $standfirst = null, string $text = null)
    {
        $this->heading = $heading;
        $this->standfirst = $standfirst;
        $this->text = $text;
    }

    public static function withHeading(string $heading, string $standfirst = null, string $text = null) : CaptionText
    {
        Assertion::notBlank($heading);

        return new self($heading, $standfirst, $text);
    }

    public static function withStandFirst(string $standfirst, string $text = null) : CaptionText
    {
        Assertion::notBlank($standfirst);

        return new self(null, $standfirst, $text);
    }

    public static function withText(string $text) : CaptionText
    {
        Assertion::notBlank($text);

        return new self(null, null, $text);
    }

    public function getTemplateName() : string
    {
        return 'patterns/caption-text.mustache';
    }
}
