<?php

namespace Microsimulation\Journal\Controller;

use eLife\ApiSdk\Collection\Sequence;
use eLife\ApiSdk\Model\Collection;
use eLife\ApiSdk\Model\Identifier;
use Microsimulation\Journal\Helper\Callback;
use Microsimulation\Journal\Helper\HasPages;
use Microsimulation\Journal\Helper\Paginator;
use Microsimulation\Journal\Patterns\ViewModel\ContentHeader;
use Microsimulation\Journal\Patterns\ViewModel\ContextualData;
use Microsimulation\Journal\Patterns\ViewModel\ListHeading;
use Microsimulation\Journal\Patterns\ViewModel\ListingProfileSnippets;
use Microsimulation\Journal\Patterns\ViewModel\ListingTeasers;
use Microsimulation\Journal\Patterns\ViewModel\ProfileSnippet;
use Microsimulation\Journal\Patterns\ViewModel\Teaser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CollectionsController extends Controller
{
    use HasPages;

    public function listAction(Request $request) : Response
    {
        $page = (int) $request->query->get('page', 1);
        $perPage = 10;

        $arguments = $this->defaultPageArguments($request);

        $latestResearch = $this->pagerfantaPromise(
            $this->get('elife.api_sdk.collections'),
            $page,
            $perPage,
            $this->willConvertTo(Teaser::class)
        );

        $arguments['title'] = 'Issues';

        $arguments['paginator'] = $this->paginator(
            $latestResearch,
            $request,
            'Browse our issues',
            'collections'
        );

        $arguments['listing'] = $arguments['paginator']
            ->then($this->willConvertTo(ListingTeasers::class, ['emptyText' => 'No issues available.']));

        if (1 === $page) {
            return $this->createFirstPage($arguments);
        }

        return $this->createSubsequentPage($request, $arguments);
    }

    private function createFirstPage(array $arguments) : Response
    {
        $arguments['contentHeader'] = new ContentHeader($arguments['title']);

        return new Response($this->get('templating')->render('::collections.html.twig', $arguments));
    }

    public function collectionAction(Request $request, string $id) : Response
    {
        $arguments['item'] = $this->get('elife.api_sdk.collections')
            ->get($id)
            ->otherwise($this->mightNotExist())
            ->then($this->checkSlug($request, Callback::method('getTitle')));

        $arguments = $this->defaultPageArguments($request, $arguments['item']);

        $arguments['title'] = $arguments['item']
            ->then(Callback::method('getTitle'));

        $arguments['contentHeader'] = $arguments['item']
            ->then($this->willConvertTo(ContentHeader::class));

        $arguments['body'] = $arguments['item']
            ->then(function (Collection $collection) {
                if ($collection->getSummary()->notEmpty()) {
                    yield from $collection->getSummary()->map($this->willConvertTo());
                }

                yield ListingTeasers::basic(
                    $collection->getContent()->map($this->willConvertTo(Teaser::class))->toArray(),
                    new ListHeading('Articles')
                );
            });

        $arguments['related'] = $arguments['item']
            ->then(Callback::method('getRelatedContent'))
            ->then(Callback::emptyOr(function (Sequence $relatedContent) {
                return ListingTeasers::basic(
                    $relatedContent->map($this->willConvertTo(Teaser::class, ['variant' => 'secondary']))->toArray(),
                    new ListHeading('Related')
                );
            }));

        return new Response($this->get('templating')->render('::collection.html.twig', $arguments));
    }
}
