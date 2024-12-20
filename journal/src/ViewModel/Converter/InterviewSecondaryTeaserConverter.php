<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\Interview;
use Microsimulation\Journal\Patterns\ViewModel;
use Microsimulation\Journal\Patterns\ViewModel\Link;
use Microsimulation\Journal\Patterns\ViewModel\Meta;
use Microsimulation\Journal\Patterns\ViewModel\Teaser;
use Microsimulation\Journal\Patterns\ViewModel\TeaserFooter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class InterviewSecondaryTeaserConverter implements ViewModelConverter
{
    use CreatesDate;
    use CreatesTeaserImage;

    private $viewModelConverter;
    private $urlGenerator;

    public function __construct(ViewModelConverter $viewModelConverter, UrlGeneratorInterface $urlGenerator)
    {
        $this->viewModelConverter = $viewModelConverter;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Interview $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        return Teaser::secondary(
            $object->getTitle(),
            $this->urlGenerator->generate('interview', [$object]),
            null,
            null,
            $object->getThumbnail() ? $this->smallTeaserImage($object) : null,
            TeaserFooter::forNonArticle(
                Meta::withLink(
                    new Link('Interview', $this->urlGenerator->generate('interviews')),
                    $this->simpleDate($object, ['date' => 'published'] + $context)
                )
            )
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Interview && ViewModel\Teaser::class === $viewModel && 'secondary' === ($context['variant'] ?? null);
    }

    protected function getViewModelConverter() : ViewModelConverter
    {
        return $this->viewModelConverter;
    }
}
