<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\ArticleVersion;
use eLife\ApiSdk\Model\ArticleVoR;
use Microsimulation\Journal\Helper\ModelName;
use Microsimulation\Journal\Helper\ModelRelationship;
use Microsimulation\Journal\Patterns\ViewModel;
use Microsimulation\Journal\Patterns\ViewModel\Meta;
use Microsimulation\Journal\Patterns\ViewModel\Teaser;
use Microsimulation\Journal\Patterns\ViewModel\TeaserFooter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ArticleRelatedItemTeaserConverter implements ViewModelConverter
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
     * @param ArticleVersion $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        if ($object instanceof ArticleVoR && $object->getThumbnail()) {
            $image = $this->smallTeaserImage($object);
        } else {
            $image = null;
        }

        return Teaser::relatedItem(
            $object->getFullTitle(),
            $this->urlGenerator->generate('article', [$object]),
            $object->getAuthorLine(),
            new ViewModel\ContextLabel(new ViewModel\Link(ModelRelationship::get($context['from'], $object->getType(), $context['related'] ?? false))),
            $image,
            TeaserFooter::forNonArticle(
                Meta::withLink(
                    new ViewModel\Link(
                        ModelName::singular($object->getType()),
                        $this->urlGenerator->generate('article-type', ['type' => $object->getType()])
                    ),
                    $this->simpleDate($object, $context)
                )
            )
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof ArticleVersion && !empty($context['from']) && ViewModel\Teaser::class === $viewModel && 'relatedItem' === ($context['variant'] ?? null);
    }

    protected function getViewModelConverter() : ViewModelConverter
    {
        return $this->viewModelConverter;
    }
}
