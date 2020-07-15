<?php

namespace Microsimulation\Journal\Controller;

use eLife\ApiSdk\Collection\Sequence;
use eLife\ApiSdk\Model\Subject;
use Microsimulation\Journal\Exception\EarlyResponse;
use Microsimulation\Journal\Helper\Callback;
use Microsimulation\Journal\Helper\CreatesIiifUri;
use Microsimulation\Journal\Helper\Paginator;
use Microsimulation\Journal\Pagerfanta\SequenceAdapter;
use Microsimulation\Journal\Patterns\ViewModel\ContentHeader;
use Microsimulation\Journal\Patterns\ViewModel\ListingTeasers;
use Microsimulation\Journal\Patterns\ViewModel\Teaser;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function GuzzleHttp\Promise\all;
use function GuzzleHttp\Promise\promise_for;

final class SubjectsController extends Controller
{
    use CreatesIiifUri;

    public function subjectAction(Request $request, string $id) : Response
    {
        $page = (int) $request->query->get('page', 1);
        $perPage = 10;

        $item = $this->get('elife.api_sdk.subjects')
            ->get($id)
            ->otherwise($this->mightNotExist())
            ->then(function (Subject $subject) use ($id) {
                if ($subject->getId() !== $id) {
                    throw new EarlyResponse(new RedirectResponse($this->get('router')->generate('subject', [$subject])));
                }

                return $subject;
            });

        $arguments = $this->defaultPageArguments($request, $item);

        $arguments['id'] = $id;

        $arguments['collections'] = $this->get('elife.api_sdk.collections')
            ->slice(0);

        $latestArticles = $arguments['collections']
            ->then(function($collections) use ($page, $perPage, $id) {
                return promise_for(
                    $this->get('elife.api_sdk.search')
                        ->forSubject($id)
                        ->forType(
                            'research-article', 
                            'research-advance', 
                            'research-communication', 
                            'scientific-correspondence', 
                            'short-report', 
                            'tools-resources', 
                            'replication-study', 
                            'editorial', 
                            'insight', 
                            'feature', 
                            'collection'
                        )
                        ->sortBy('date')
                )
                    ->then(
                        function (Sequence $sequence) use ($page, $perPage, $collections) {
                            $pagerfanta = new Pagerfanta(new SequenceAdapter($sequence, $this->willConvertTo(Teaser::class, ['collections' => $collections])));
                            $pagerfanta->setMaxPerPage($perPage)->setCurrentPage($page);
            
                            return $pagerfanta;
                    });
            });

        $arguments['title'] = $arguments['item']
            ->then(Callback::method('getName'));

        $arguments['paginator'] = all(['item' => $arguments['item'], 'latestArticles' => $latestArticles])
            ->then(function (array $parts) use ($request) {
                $item = $parts['item'];
                $latestArticles = $parts['latestArticles'];

                return new Paginator(
                    sprintf('Browse our latest %s articles', $item->getName()),
                    $latestArticles,
                    function (int $page = null) use ($request) {
                        $routeParams = $request->attributes->get('_route_params');
                        $routeParams['page'] = $page;

                        return $this->get('router')->generate('subject', $routeParams);
                    }
                );
            });

        $arguments['listing'] = $arguments['paginator']
            ->then($this->willConvertTo(ListingTeasers::class, ['type' => 'articles']));

        if (1 === $page) {
            return $this->createFirstPage($arguments);
        }

        return $this->createSubsequentPage($request, $arguments);
    }

    private function createFirstPage(array $arguments) : Response
    {
        $arguments['contentHeader'] = $arguments['item']
            ->then($this->willConvertTo(ContentHeader::class));

        return new Response($this->get('templating')->render('::subject.html.twig', $arguments));
    }
}
