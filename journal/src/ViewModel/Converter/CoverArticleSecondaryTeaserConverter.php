<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\ArticleVersion;
use eLife\ApiSdk\Model\Cover;
use Microsimulation\Journal\Helper\ModelName;
use Microsimulation\Journal\Patterns\ViewModel;
use Microsimulation\Journal\Patterns\ViewModel\Meta;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CoverArticleSecondaryTeaserConverter implements ViewModelConverter
{
    use CreatesContextLabel;
    use CreatesDate;

    private $viewModelConverter;
    private $urlGenerator;

    public function __construct(ViewModelConverter $viewModelConverter, UrlGeneratorInterface $urlGenerator)
    {
        $this->viewModelConverter = $viewModelConverter;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Cover $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        /** @var ArticleVersion $article */
        $article = $object->getItem();

        return ViewModel\Teaser::secondary(
            $object->getTitle(),
            $this->urlGenerator->generate('article', [$article]),
            $article->getAuthorLine(),
            $this->createContextLabel($article),
            ViewModel\TeaserImage::small(
                $this->viewModelConverter->convert($object->getBanner(), null, ['width' => 72, 'height' => 72])
            ),
            ViewModel\TeaserFooter::forNonArticle(
                Meta::withLink(
                    new ViewModel\Link(
                        ModelName::singular($article->getType()),
                        $this->urlGenerator->generate('article-type', ['type' => $article->getType()])
                    ),
                    $this->simpleDate($article, $context)
                )
            )
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Cover && ViewModel\Teaser::class === $viewModel && 'secondary' === ($context['variant'] ?? null) && $object->getItem() instanceof ArticleVersion;
    }
}
