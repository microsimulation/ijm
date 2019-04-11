<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\Collection;
use Microsimulation\Journal\Helper\ModelName;
use Microsimulation\Journal\Helper\ModelRelationship;
use Microsimulation\Journal\Patterns\ViewModel;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CollectionRelatedItemTeaserConverter implements ViewModelConverter
{
    use CreatesDate;

    private $viewModelConverter;
    private $urlGenerator;

    public function __construct(ViewModelConverter $viewModelConverter, UrlGeneratorInterface $urlGenerator)
    {
        $this->viewModelConverter = $viewModelConverter;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Collection $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        return ViewModel\Teaser::relatedItem(
            $object->getTitle(),
            $this->urlGenerator->generate('collection', [$object]),
            null,
            new ViewModel\ContextLabel(new ViewModel\Link(ModelRelationship::get($context['from'], 'collection', $context['related'] ?? false))),
            null,
            ViewModel\TeaserFooter::forNonArticle(
                ViewModel\Meta::withLink(
                    new ViewModel\Link(
                        ModelName::singular('collection'),
                        $this->urlGenerator->generate('collections')
                    )/*,
                    $this->simpleDate($object, $context)*/
                )
            )
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Collection && !empty($context['from']) && ViewModel\Teaser::class === $viewModel && 'relatedItem' === ($context['variant'] ?? null);
    }

    protected function getViewModelConverter() : ViewModelConverter
    {
        return $this->viewModelConverter;
    }
}
