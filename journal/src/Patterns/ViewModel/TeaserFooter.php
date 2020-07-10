<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\CastsToArray;

final class TeaserFooter implements CastsToArray
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $meta;
    private $formats;

    private function __construct(
        Meta $meta,
        bool $html = false,
        bool $pdf = false,
        Meta $articleIssue = null
    ) {
        $this->meta = $meta;
        $this->formats = array_filter(compact('html', 'pdf'));
        $this->articleIssue = $articleIssue;
    }

    public static function forArticle(
        Meta $meta,
        bool $html = false,
        bool $pdf = false,
        Meta $articleIssue = null
    ) {
        return new static($meta, $html, $pdf, $articleIssue);
    }

    public static function forNonArticle(
        Meta $meta
    ) {
        return new static($meta);
    }
}
