<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\PressPackage;
use eLife\ApiSdk\Model\Subject;
use Microsimulation\Journal\Helper\LicenceUri;
use Microsimulation\Journal\Patterns\ViewModel;
use Microsimulation\Journal\Patterns\ViewModel\Link;
use Microsimulation\Journal\Patterns\ViewModel\Meta;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function strip_tags;

final class PressPackageContentHeaderConverter implements ViewModelConverter
{
    use CreatesDate;

    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param PressPackage $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        $subjects = $object->getSubjects()->map(function (Subject $subject) {
            return new ViewModel\Link($subject->getName(), $this->urlGenerator->generate('subject', [$subject]));
        })->toArray();

        return new ViewModel\ContentHeader($object->getTitle(), null, $object->getImpactStatement(), true,
            $subjects, null, [], [], null,
            new ViewModel\SocialMediaSharers(
                strip_tags($object->getTitle()),
                $this->urlGenerator->generate('press-pack', [$object], UrlGeneratorInterface::ABSOLUTE_URL)
            ),
            null,
            Meta::withLink(
                new Link('Press pack', $this->urlGenerator->generate('press-packs')),
                $this->simpleDate($object, ['date' => 'published'] + $context)
            ), LicenceUri::default()
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof PressPackage && ViewModel\ContentHeader::class === $viewModel;
    }
}
