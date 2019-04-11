<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class Meta implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $url;
    private $text;
    private $date;

    private function __construct(string $url = null, string $text = null, Date $date = null)
    {
        if ($date instanceof Date) {
            Assertion::false($date['isExpanded']);
        }

        $this->url = $url ?? false;
        $this->text = $text;
        $this->date = $date;
    }

    public static function withLink(Link $link, Date $date = null) : Meta
    {
        return new self($link['url'], $link['name'], $date);
    }

    public static function withText(string $text, Date $date = null) : Meta
    {
        Assertion::minLength($text, 1);

        return new self(null, $text, $date);
    }

    public static function withDate(Date $date) : Meta
    {
        return new self(null, null, $date);
    }

    public function getTemplateName() : string
    {
        return 'patterns/meta.mustache';
    }
}
