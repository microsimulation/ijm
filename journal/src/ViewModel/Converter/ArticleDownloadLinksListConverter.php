<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\ArticleVersion;
use eLife\ApiSdk\Model\ArticleVoR;
use Microsimulation\Journal\Helper\DownloadLink;
use Microsimulation\Journal\Helper\DownloadLinkUriGenerator;
use Microsimulation\Journal\Patterns\ViewModel;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function Microsimulation\Journal\Patterns\mixed_visibility_text;

final class ArticleDownloadLinksListConverter implements ViewModelConverter
{
    private $urlGenerator;
    private $downloadLinkUriGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator, DownloadLinkUriGenerator $downloadLinkUriGenerator)
    {
        $this->urlGenerator = $urlGenerator;
        $this->downloadLinkUriGenerator = $downloadLinkUriGenerator;
    }

    /**
     * @param ArticleVersion $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        $groups = [];

        if ($object->getPdf()) {
            $items = [
                new ViewModel\Link(
                    'Article PDF',
                    $this->downloadLinkUriGenerator->generate(DownloadLink::fromUri($object->getPdf())),
                    false,
                    ['article-identifier' => $object->getDoi(), 'download-type' => 'pdf-article']
                ),
            ];

            if ($object instanceof ArticleVor && $object->getFiguresPdf()) {
                $items[] = new ViewModel\Link(
                    'Figures PDF',
                    $this->downloadLinkUriGenerator->generate(DownloadLink::fromUri($object->getFiguresPdf())),
                    false,
                    ['article-identifier' => $object->getDoi(), 'download-type' => 'pdf-figures']
                );
            }

            $groups[mixed_visibility_text('', 'Downloads', '(link to download the article as PDF)')] = $items;
        }

        $articleUri = $this->urlGenerator->generate('article', [$object], UrlGeneratorInterface::ABSOLUTE_URL);

        return new ViewModel\ArticleDownloadLinksList('downloads', 'A two-part list of links to download the article, or parts of the article, in various formats.', $groups);
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof ArticleVersion && ViewModel\ArticleDownloadLinksList::class === $viewModel;
    }
}
