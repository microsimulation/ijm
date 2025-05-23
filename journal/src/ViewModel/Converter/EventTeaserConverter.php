<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\Event;
use Microsimulation\Journal\Patterns\ViewModel;
use Microsimulation\Journal\Patterns\ViewModel\Date;
use Microsimulation\Journal\Patterns\ViewModel\Teaser;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class EventTeaserConverter implements ViewModelConverter
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Event $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        return Teaser::event(
            $object->getTitle(),
            $object->getUri() ?? $this->urlGenerator->generate('event', [$object]),
            null,
            Date::expanded($object->getStarts()),
            'secondary' === ($context['variant'] ?? null)
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Event && ViewModel\Teaser::class === $viewModel;
    }
}
