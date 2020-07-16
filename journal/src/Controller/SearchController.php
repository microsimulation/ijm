<?php

namespace Microsimulation\Journal\Controller;

use eLife\ApiSdk\Client\Search;
use eLife\ApiSdk\Collection\Sequence;
use GuzzleHttp\Promise\PromiseInterface;
use Microsimulation\Journal\Helper\Callback;
use Microsimulation\Journal\Helper\Paginator;
use Microsimulation\Journal\Pagerfanta\SequenceAdapter;
use Microsimulation\Journal\Patterns\ViewModel\Button;
use Microsimulation\Journal\Patterns\ViewModel\CompactForm;
use Microsimulation\Journal\Patterns\ViewModel\Filter;
use Microsimulation\Journal\Patterns\ViewModel\FilterGroup;
use Microsimulation\Journal\Patterns\ViewModel\FilterPanel;
use Microsimulation\Journal\Patterns\ViewModel\Form;
use Microsimulation\Journal\Patterns\ViewModel\Input;
use Microsimulation\Journal\Patterns\ViewModel\Link;
use Microsimulation\Journal\Patterns\ViewModel\ListingTeasers;
use Microsimulation\Journal\Patterns\ViewModel\MessageBar;
use Microsimulation\Journal\Patterns\ViewModel\SearchBox;
use Microsimulation\Journal\Patterns\ViewModel\SortControl;
use Microsimulation\Journal\Patterns\ViewModel\SortControlOption;
use Microsimulation\Journal\Patterns\ViewModel\Teaser;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function GuzzleHttp\Promise\promise_for;

final class SearchController extends Controller
{
    public function queryAction(Request $request) : Response
    {
        $page = (int) $request->query->get('page', 1);
        $perPage = 10;

        $arguments = $this->defaultPageArguments($request);

        $arguments['query'] = $query = [
            'for' => trim($request->query->get('for')),
            'subjects' => $request->query->get('subjects', []),
            'sort' => $request->query->get('sort', 'relevance'),
            'order' => $request->query->get('order', SortControlOption::DESC),
        ];

        $search = $this->get('elife.api_sdk.search')
            ->forQuery($arguments['query']['for'])
            ->forSubject(...$arguments['query']['subjects'])
            ->sortBy($arguments['query']['sort']);

        if (SortControlOption::ASC === $arguments['query']['order']) {
            $search = $search->reverse();
        }

        $search = promise_for($search);

        $arguments['collections'] = $this->get('elife.api_sdk.collections')
            ->slice(0);

        $pagerfanta = $arguments['collections']
            ->then(function($collections) use ($page, $perPage, $search) {
                return $search
                    ->then(
                        function (Sequence $sequence) use ($page, $perPage, $collections) {
                            $pagerfanta = new Pagerfanta(new SequenceAdapter($sequence, $this->willConvertTo(Teaser::class, ['collections' => $collections])));
                            $pagerfanta->setMaxPerPage($perPage)->setCurrentPage($page);

                            return $pagerfanta;
                        }
                    );
            });

        $arguments['title'] = 'Search';

        $arguments['searchBox'] = new SearchBox(
            new CompactForm(
                new Form($this->get('router')->generate('search'), 'search', 'GET'),
                new Input(
                    'Search by keyword or author',
                    'search',
                    'for',
                    $arguments['query']['for'],
                    'Search by keyword or author'
                ),
                'Search'
            )
        );

        $arguments['paginator'] = $pagerfanta
            ->then(
                function (Pagerfanta $pagerfanta) use ($request, $query) {
                    return new Paginator(
                        'Browse the search results',
                        $pagerfanta,
                        function (int $page = null) use ($request, $query) {
                            $routeParams = $query + $request->attributes->get('_route_params');
                            $routeParams['page'] = $page;

                            return $this->get('router')->generate('search', $routeParams);
                        }
                    );
                }
            );

        $arguments['listing'] = $arguments['paginator']
            ->then(Callback::methodEmptyOr('getTotal', $this->willConvertTo(ListingTeasers::class, ['heading' => ''])));

        if (1 === $page) {
            return $this->createFirstPage($search, $arguments);
        }

        return $this->createSubsequentPage($request, $arguments);
    }

    private function createFirstPage(PromiseInterface $search, array $arguments) : Response
    {
        $arguments['messageBar'] = $arguments['paginator']
            ->then(
                function (Paginator $paginator) {
                    if (1 === $paginator->getTotal()) {
                        return new MessageBar('1 result found');
                    }

                    return new MessageBar('<b>'.number_format($paginator->getTotal()).'</b> results found');
                }
            );

        $currentOrder = SortControlOption::ASC === $arguments['query']['order'] ? SortControlOption::ASC : SortControlOption::DESC;
        $inverseOrder = SortControlOption::ASC === $arguments['query']['order'] ? SortControlOption::DESC : SortControlOption::ASC;

        $relevanceQuery = array_merge(
            $arguments['query'],
            [
                'sort' => 'relevance',
                'order' => 'relevance' === $arguments['query']['sort'] ? $inverseOrder : SortControlOption::DESC,
            ]
        );

        $dateQuery = array_merge(
            $arguments['query'],
            [
                'sort' => 'date',
                'order' => 'date' === $arguments['query']['sort'] ? $inverseOrder : SortControlOption::DESC,
            ]
        );

        $arguments['sortControl'] = new SortControl(
            [
                new SortControlOption(
                    new Link('Relevance', $this->get('router')->generate('search', $relevanceQuery)),
                    'relevance' === $arguments['query']['sort'] ? $currentOrder : null
                ),
                new SortControlOption(
                    new Link('Date', $this->get('router')->generate('search', $dateQuery)),
                    'date' === $arguments['query']['sort'] ? $currentOrder : null
                ),
            ]
        );

        $arguments['filterPanel'] = $search
            ->then(
                function (Search $search) use ($arguments) {
                    $filterGroups = [];

                    if (count($search->subjects())) {
                        $subjectFilters = [];
                        foreach ($search->subjects() as $subject => $results) {
                            $subjectFilters[] = new Filter(
                                in_array($subject->getId(), $arguments['query']['subjects']),
                                $subject->getName(),
                                $results,
                                'subjects[]',
                                $subject->getId()
                            );
                        }

                        usort(
                            $subjectFilters,
                            function (Filter $a, Filter $b) {
                                return $a['label'] <=> $b['label'];
                            }
                        );

                        $filterGroups[] = new FilterGroup('Research categories', $subjectFilters);
                    }

                    return new FilterPanel(
                        'Refine your results by:',
                        $filterGroups,
                        Button::form('Refine results', Button::TYPE_SUBMIT)
                    );
                }
            );

        return new Response($this->get('templating')->render('::search.html.twig', $arguments));
    }
}
