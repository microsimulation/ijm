<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class ContextualData implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $metricsData;
    private $citation;

    private function __construct(array $metrics, string $citeAs = null, Doi $doi = null, SpeechBubble $annotationCount = null)
    {
        Assertion::allString($metrics);

        if ($metrics) {
            $this->metricsData = [
                'data' => array_map(function (string $text) {
                    return compact('text');
                }, $metrics),
            ];
        }

        if ($annotationCount) {
            $this->metricsData['annotationCount'] = $annotationCount;
        }

        if ($citeAs && $doi) {
            $doi = FlexibleViewModel::fromViewModel($doi);
            $this->citation = [
                'citeAs' => $citeAs,
                'doi' => $doi->withProperty('isTruncated', true),
            ];
        }
    }

    public static function annotationsOnly(SpeechBubble $annotationCount)
    {
        return new self([], null, null, $annotationCount);
    }

    public static function withMetrics(
        array $metrics,
        string $citeAs = null,
        Doi $doi = null,
        SpeechBubble $annotationCount = null
    ) : ContextualData {
        Assertion::notEmpty($metrics);

        return new self($metrics, $citeAs, $doi, $annotationCount);
    }

    public static function withCitation(
        string $citeAs,
        Doi $doi,
        array $metrics = [],
        SpeechBubble $annotationCount = null
    ) : ContextualData {
        Assertion::notBlank($citeAs);

        return new self($metrics, $citeAs, $doi, $annotationCount);
    }

    public function getTemplateName() : string
    {
        return 'patterns/contextual-data.mustache';
    }
}
