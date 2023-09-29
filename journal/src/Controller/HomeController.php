<?php

namespace Microsimulation\Journal\Controller;

use eLife\ApiSdk\Collection\Sequence;
use eLife\ApiSdk\Model\Subject;
use Microsimulation\Journal\Helper\Callback;
use Microsimulation\Journal\Helper\Paginator;
use Microsimulation\Journal\Pagerfanta\SequenceAdapter;
use Microsimulation\Journal\Patterns\ViewModel\CategoryGroup;
use Microsimulation\Journal\Patterns\ViewModel\Code;
use Microsimulation\Journal\Patterns\ViewModel\ContentHeader;
use Microsimulation\Journal\Patterns\ViewModel\ContentHeaderImage;
use Microsimulation\Journal\Patterns\ViewModel\Image;
use Microsimulation\Journal\Patterns\ViewModel\LeadPara;
use Microsimulation\Journal\Patterns\ViewModel\LeadParas;
use Microsimulation\Journal\Patterns\ViewModel\Link;
use Microsimulation\Journal\Patterns\ViewModel\ListHeading;
use Microsimulation\Journal\Patterns\ViewModel\ListingTeasers;
use Microsimulation\Journal\Patterns\ViewModel\Picture;
use Microsimulation\Journal\Patterns\ViewModel\SectionListing;
use Microsimulation\Journal\Patterns\ViewModel\SectionListingLink;
use Microsimulation\Journal\Patterns\ViewModel\SeeMoreLink;
use Microsimulation\Journal\Patterns\ViewModel\Teaser;
use Microsimulation\Journal\Patterns\ViewModel\Meta;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function GuzzleHttp\Promise\promise_for;

final class HomeController extends Controller
{
    public function homeAction(Request $request) : Response
    {
        $page = (int) $request->query->get('page', 1);
        $perPage = 10;

        $arguments = $this->defaultPageArguments($request);

        $arguments['collections'] = $this->get('elife.api_sdk.collections')
            ->slice(0);

        $latestResearch = $arguments['collections']
            ->then(function($collections) use ($page, $perPage) {
                return promise_for(
                    $this->get('elife.api_sdk.search')
                        ->forType(
                            'research-article',
                            'research-communication',
                            'registered-report',
                            'scientific-correspondence',
                            'short-report',
                            'tools-resources',
                            'feature'
                        )
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

        $arguments['title'] = 'Latest research';

        $arguments['paginator'] = $latestResearch
            ->then(
                function (Pagerfanta $pagerfanta) use ($request) {
                    return new Paginator(
                        'Browse our latest research',
                        $pagerfanta,
                        function (int $page = null) use ($request) {
                            $routeParams = $request->attributes->get('_route_params');
                            $routeParams['page'] = $page;

                            return $this->get('router')->generate('home', $routeParams);
                        }
                    );
                }
            );

        $arguments['listing'] = $arguments['paginator']
            ->then($this->willConvertTo(ListingTeasers::class, ['heading' => 'Latest research', 'type' => 'articles']));

        if (1 === $page) {
            return $this->createFirstPage($arguments);
        }

        return $this->createSubsequentPage($request, $arguments);
    }

    private function createFirstPage(array $arguments) : Response
    {
        $arguments['contentHeader'] = new ContentHeader(
            'International Journal of Microsimulation',
            new ContentHeaderImage(
                new Picture([], new Image($this->get('elife.assets.packages')->getUrl('assets/header.jpg'))),
                null,
                true
            )
        );

        $arguments['leadParas'] = new LeadParas(
            [
                new LeadPara(
                    'The International Journal of Microsimulation (IJM) is the official online peer-reviewed journal of the International Microsimulation Association',
                    'strapline'
                ),
            ]
        );

        $arguments['subjectsLink'] = new SectionListingLink('All research categories', 'subjects');

        $arguments['subjects'] = $this->get('elife.api_sdk.subjects')
            ->reverse()
            ->slice(1, 100)
            ->map(
                function (Subject $subject) {
                    return new Link($subject->getName(), $this->get('router')->generate('subject', [$subject]));
                }
            )
            ->then(
                function (Sequence $links) {
                    return new SectionListing(
                        'subjects',
                        $links->toArray(),
                        new ListHeading('Research categories'),
                        false,
                        'strapline',
                        [ 'renderArticleTypes' => true ]
                    );
                }
            )
            ->otherwise($this->softFailure('Failed to load subjects list'));


        $arguments['collections'] = $arguments['collections']
            ->then($this->willConvertTo(CategoryGroup::class, ['heading' => 'Issues group']))
            ->otherwise($this->softFailure('Failed to load collections list'));

        return new Response($this->get('templating')->render('::home.html.twig', $arguments));
    }
}
