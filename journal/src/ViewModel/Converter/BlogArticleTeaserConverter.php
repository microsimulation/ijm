<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\BlogArticle;
use Microsimulation\Journal\Patterns\ViewModel;
use Microsimulation\Journal\Patterns\ViewModel\Link;
use Microsimulation\Journal\Patterns\ViewModel\Meta;
use Microsimulation\Journal\Patterns\ViewModel\Teaser;
use Microsimulation\Journal\Patterns\ViewModel\TeaserFooter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class BlogArticleTeaserConverter implements ViewModelConverter
{
    use CreatesContextLabel;
    use CreatesDate;

    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param BlogArticle $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        return Teaser::main(
            $object->getTitle(),
            $this->urlGenerator->generate('inside-elife-article', [$object]),
            $object->getImpactStatement(),
            null,
            $this->createContextLabel($object),
            null,
            TeaserFooter::forNonArticle(
                Meta::withLink(
                    new Link('Inside eLife', $this->urlGenerator->generate('inside-elife')),
                    $this->simpleDate($object, $context)
                )
            )
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof BlogArticle && ViewModel\Teaser::class === $viewModel && empty($context['variant']);
    }
}
