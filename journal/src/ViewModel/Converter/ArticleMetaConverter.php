<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\ArticleVersion;
use eLife\ApiSdk\Model\ArticleVoR;
use eLife\ApiSdk\Model\Subject;
use Microsimulation\Journal\Helper\ModelName;
use Microsimulation\Journal\Patterns\ViewModel;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ArticleMetaConverter implements ViewModelConverter
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param ArticleVersion $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        $tags = [
            new ViewModel\Link(
                ModelName::singular($object->getType()),
                $this->urlGenerator->generate('article-type', ['type' => $object->getType()])
            ),
        ];

        $tags = array_merge($tags, $object->getSubjects()->map(function (Subject $subject) {
            return new ViewModel\Link(
                $subject->getName(),
                $this->urlGenerator->generate('subject', [$subject])
            );
        })->toArray());

        if ($object instanceof ArticleVoR) {
            $tags = array_merge($tags, $object->getKeywords()->map(function (string $keyword) {
                return new ViewModel\Link(
                    $keyword,
                    $this->urlGenerator->generate('search', ['for' => strip_tags($keyword)])
                );
            })->toArray());
        }

        $groups = ['Categories and tags' => $tags];

        if ($object->getResearchOrganisms()) {
            $title = 'Research organism';
            if (count($object->getResearchOrganisms()) > 1) {
                $title .= 's';
            }

            $groups[$title] = array_map(function (string $keyword) {
                return new ViewModel\Link(
                    $keyword,
                    $this->urlGenerator->generate('search', ['for' => strip_tags($keyword)])
                );
            }, $object->getResearchOrganisms());
        }

        return new ViewModel\ArticleMeta($groups);
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof ArticleVersion && ViewModel\ArticleMeta::class === $viewModel;
    }
}
