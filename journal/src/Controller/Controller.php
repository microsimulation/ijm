<?php

namespace Microsimulation\Journal\Controller;

use eLife\ApiClient\Exception\BadResponse;
use eLife\ApiSdk\Model\Model;
use GuzzleHttp\Promise\PromiseInterface;
use Microsimulation\Journal\Exception\EarlyResponse;
use Microsimulation\Journal\Helper\CanCheckAuthorization;
use Microsimulation\Journal\Helper\CanConvertContent;
use Microsimulation\Journal\Helper\Paginator;
use Microsimulation\Journal\Patterns\ViewModel;
use Microsimulation\Journal\Patterns\ViewModel\ContentHeaderSimple;
use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use function GuzzleHttp\Promise\exception_for;
use function GuzzleHttp\Promise\promise_for;
use function GuzzleHttp\Promise\rejection_for;

abstract class Controller implements ContainerAwareInterface
{
    use CanConvertContent;

    /**
     * @var ContainerInterface
     */
    private $container;

    final public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    final protected function get(string $id)
    {
        return $this->container->get($id);
    }

    final protected function getParameter(string $id)
    {
        return $this->container->getParameter($id);
    }

    final protected function has(string $id) : bool
    {
        return $this->container->has($id);
    }

    final protected function getViewModelConverter() : ViewModelConverter
    {
        return $this->get('microsimulation.journal.view_model.converter');
    }

    final protected function getAuthorizationChecker() : AuthorizationCheckerInterface
    {
        return $this->get('security.authorization_checker');
    }

    final protected function render(ViewModel ...$viewModels) : string
    {
        return $this->get('microsimulation.journal.patterns.pattern_renderer')->render(...$viewModels);
    }

    final protected function willRender() : callable
    {
        return function (ViewModel $viewModel) {
            return $this->render($viewModel);
        };
    }

    final protected function checkSlug(Request $request, callable $toSlugify) : callable
    {
        return function ($object) use ($request, $toSlugify) {
            $slug = $request->attributes->get('_route_params')['slug'];
            $correctSlug = $this->get('elife.slugify')->slugify($toSlugify($object));

            if ($slug !== $correctSlug) {
                $route = $request->attributes->get('_route');
                $routeParams = $request->attributes->get('_route_params');
                $routeParams['slug'] = $correctSlug;
                $url = $this->get('router')->generate($route, $routeParams);
                if ($queryString = $request->server->get('QUERY_STRING')) {
                    $url .= "?{$queryString}";
                }

                throw new EarlyResponse(new RedirectResponse($url, Response::HTTP_MOVED_PERMANENTLY));
            }

            return $object;
        };
    }

    final protected function mightNotExist() : callable
    {
        return function ($reason) {
            if ($reason instanceof BadResponse) {
                switch ($reason->getResponse()->getStatusCode()) {
                    case Response::HTTP_GONE:
                    case Response::HTTP_NOT_FOUND:
                        throw new HttpException(
                            $reason->getResponse()->getStatusCode(), $reason->getMessage(), $reason
                        );
                }
            }

            return rejection_for($reason);
        };
    }

    final protected function softFailure(string $message = null, $default = null) : callable
    {
        return function ($reason) use ($message, $default) {
            //return new RejectedPromise($reason);
            $e = exception_for($reason);

            if (false === $e instanceof HttpException) {
                $this->get('elife.logger')->error($message ?? $e->getMessage(), ['exception' => $e]);
            }

            return $default;
        };
    }

    final protected function createSubsequentPage(Request $request, array $arguments) : Response
    {
        $arguments['listing'] = $arguments['listing']
            ->otherwise($this->mightNotExist());

        if ($request->isXmlHttpRequest()) {
            $response = new Response($this->render($arguments['listing']->wait()));
        } else {
            $arguments['contentHeader'] = $arguments['paginator']
                ->then(
                    function (Paginator $paginator) {
                        return new ContentHeaderSimple(
                            $paginator->getTitle(),
                            sprintf(
                                'Page %s of %s',
                                number_format($paginator->getCurrentPage()),
                                number_format(count($paginator))
                            )
                        );
                    }
                );

            $template = $arguments['listing']->wait(
            ) instanceof ViewModel\GridListing ? '::pagination-grid.html.twig' : '::pagination.html.twig';

            $response = new Response($this->get('templating')->render($template, $arguments));
        }

        $response->headers->set('Vary', 'X-Requested-With', false);

        return $response;
    }

    final protected function defaultPageArguments(Request $request, PromiseInterface $item = null) : array
    {
        return [
            'header' => promise_for($item)
                ->then(
                    function (Model $item = null) {
                        return $this->get('microsimulation.journal.view_model.factory.site_header')
                            ->createSiteHeader($item);
                    }
                ),
            'infoBars' => [],
            'footer' => $this->get('microsimulation.journal.view_model.factory.footer')->createFooter(),
            'item' => $item,
        ];
    }
}
