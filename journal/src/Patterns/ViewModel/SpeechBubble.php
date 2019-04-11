<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use function Microsimulation\Journal\Patterns\mixed_accessibility_text;
use Microsimulation\Journal\Patterns\ViewModel;

final class SpeechBubble implements ViewModel
{
    const ELABORATELY_EMPTY = '&#8220;';
    const LITERALLY_EMPTY = '';

    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $text;
    private $isSmall;
    private $hasPlaceholder;
    private $behaviour;

    private function __construct(string $emptinessSignifier, bool $isSmall = false)
    {
        $visibleAnnotationCount = "<span data-visible-annotation-count>{$emptinessSignifier}</span>";
        $hiddenAccessibleText = 'Open annotations. The current annotation count on this page is <span data-hypothesis-annotation-count>being calculated</span>.';
        $this->text = mixed_accessibility_text($visibleAnnotationCount, $hiddenAccessibleText);
        if ($isSmall) {
            $this->isSmall = $isSmall;
        }
        if (self::ELABORATELY_EMPTY === $emptinessSignifier) {
            $this->hasPlaceholder = true;
        }
        $this->behaviour = 'HypothesisOpener';
    }

    public static function forArticleBody() : SpeechBubble
    {
        return new static(self::ELABORATELY_EMPTY);
    }

    public static function forContextualData() : SpeechBubble
    {
        return new static(self::LITERALLY_EMPTY, true);
    }

    public function getTemplateName() : string
    {
        return 'patterns/speech-bubble.mustache';
    }
}
