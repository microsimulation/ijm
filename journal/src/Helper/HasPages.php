<?php

namespace Microsimulation\Journal\Helper;

use eLife\ApiSdk\Collection\Sequence;
use Microsimulation\Journal\Pagerfanta\SequenceAdapter;
use GuzzleHttp\Promise\PromiseInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use function GuzzleHttp\Promise\promise_for;

trait HasPages
{
    final private function pagerfantaPromise(Sequence $sdkClient, int $page, int $perPage, callable $map = null) : PromiseInterface
    {
        return promise_for($sdkClient)
            ->then(function (Sequence $sequence) use ($page, $perPage, $map) {
                $pagerfanta = new Pagerfanta(new SequenceAdapter($sequence, $map));
                $pagerfanta->setMaxPerPage($perPage)->setCurrentPage($page);

                return $pagerfanta;
            });
    }

    final private function paginator(PromiseInterface $pagerfantaPromise, Request $request, string $title, string $route) : PromiseInterface
    {
        return $pagerfantaPromise
            ->then(function (Pagerfanta $pagerfanta) use ($request, $title, $route) {
                return new Paginator(
                    $title,
                    $pagerfanta,
                    function (int $page = null) use ($request, $route) {
                        $routeParams = $request->attributes->get('_route_params');
                        $routeParams['page'] = $page;

                        return $this->get('router')->generate($route, $routeParams);
                    }
                );
            });
    }
}
