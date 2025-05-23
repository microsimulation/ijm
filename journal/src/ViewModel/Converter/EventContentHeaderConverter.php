<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\Event;
use Microsimulation\Journal\Helper\LicenceUri;
use Microsimulation\Journal\Patterns\ViewModel;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function strip_tags;

final class EventContentHeaderConverter implements ViewModelConverter
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
        return new ViewModel\ContentHeader(
            $object->getTitle(),
            null,
            $object->getImpactStatement(),
            true,
            [],
            null,
            [],
            [],
            null,
            new ViewModel\SocialMediaSharers(
                strip_tags($object->getTitle()),
                $this->urlGenerator->generate('event', [$object], UrlGeneratorInterface::ABSOLUTE_URL)
            ),
            null,
            ViewModel\Meta::withLink(new ViewModel\Link('Event', $this->urlGenerator->generate('events')), ViewModel\Date::simple($object->getStarts())),
            LicenceUri::default()
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Event && ViewModel\ContentHeader::class === $viewModel;
    }
}
