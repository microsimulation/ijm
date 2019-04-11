<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\Subject;
use Microsimulation\Journal\Patterns\ViewModel\ContextLabel;
use Microsimulation\Journal\Patterns\ViewModel\Link;

trait CreatesContextLabel
{
    /**
     * @return ContextLabel|null
     */
    final private function createContextLabel($item)
    {
        if (!method_exists($item, 'getSubjects') || $item->getSubjects()->isEmpty()) {
            return null;
        }

        return new ContextLabel(...$item->getSubjects()->map(function (Subject $subject) {
            return new Link(
                $subject->getName(),
                $this->urlGenerator->generate('subject', [$subject])
            );
        }));
    }
}
