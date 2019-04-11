<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

trait HasTitleLength
{
    private static $titleLengthLimits = [
        19 => 'xx-short',
        35 => 'x-short',
        46 => 'short',
        57 => 'medium',
        80 => 'long',
        120 => 'x-long',
        null => 'xx-long',
    ];

    private function determineTitleLength($title) : string
    {
        $charCount = mb_strlen(strip_tags($title));

        foreach (self::$titleLengthLimits as $maxLength => $value) {
            if ($charCount <= $maxLength) {
                return $value;
            }
        }

        return end(self::$titleLengthLimits);
    }
}
