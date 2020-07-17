<?php

namespace Microsimulation\Journal\Controller;

use eLife\ApiSdk\Collection\Sequence;
use InvalidArgumentException;
use Microsimulation\Journal\Helper\ModelName;
use Microsimulation\Journal\Helper\Paginator;
use Microsimulation\Journal\Pagerfanta\SequenceAdapter;
use Microsimulation\Journal\Patterns\ViewModel\ContentHeader;
use Microsimulation\Journal\Patterns\ViewModel\ListingTeasers;
use Microsimulation\Journal\Patterns\ViewModel\Teaser;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function GuzzleHttp\Promise\promise_for;

final class ArticleTypesController extends Controller
{
    public function listAction(Request $request, string $type) : Response
    {
        $page = (int) $request->query->get('page', 1);
        $perPage = 10;

        $arguments = $this->defaultPageArguments($request);

        try {
            $arguments['title'] = ModelName::plural($type);
        } catch (InvalidArgumentException $e) {
            throw new NotFoundHttpException('Unknown type '.$type, $e);
        }

        $arguments['collections'] = $this->get('elife.api_sdk.collections')
            ->slice(0);

        $latest = $arguments['collections']
            ->then(function($collections) use ($page, $perPage, $type) {
                return promise_for(
                    $this->get('elife.api_sdk.search')
                        ->forType($type)
                        ->sortBy('date')
                )
                    ->then(
                        function (Sequence $sequence) use ($page, $perPage, $collections) {
                            $pagerfanta = new Pagerfanta(new SequenceAdapter($sequence, $this->willConvertTo(Teaser::class, ['collections' => $collections])));
                            $pagerfanta->setMaxPerPage($perPage)->setCurrentPage($page);

                            return $pagerfanta;
                        }
                    );
            });

        $arguments['paginator'] = $latest
            ->then(
                function (Pagerfanta $pagerfanta) use ($request, $type) {
                    return new Paginator(
                        'Browse our '.ModelName::plural($type),
                        $pagerfanta,
                        function (int $page = null) use ($request) {
                            $routeParams = $request->attributes->get('_route_params');
                            $routeParams['page'] = $page;

                            return $this->get('router')->generate('article-type', $routeParams);
                        }
                    );
                }
            );

        $arguments['listing'] = $arguments['paginator']
            ->then($this->willConvertTo(ListingTeasers::class, ['type' => 'articles']));

        if (1 === $page) {
            return $this->createFirstPage($arguments, $type);
        }

        return $this->createSubsequentPage($request, $arguments);
    }

    private function createFirstPage(array $arguments, string $type) : Response
    {
        $arguments['contentHeader'] = new ContentHeader($arguments['title']);

        return new Response($this->get('templating')->render('::article-type.html.twig', $arguments));
    }
}
