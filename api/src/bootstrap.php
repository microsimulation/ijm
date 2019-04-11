<?php

use eLife\ApiProblem\Silex\ApiProblemProvider;
use eLife\Ping\Silex\PingControllerProvider;
use Silex\Application;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Application();

$app->register(new ApiProblemProvider());
$app->register(new PingControllerProvider());

$app['articles'] = static function () {
    $finder = (new Finder())->files()->name('*.json')->in(__DIR__.'/../data/articles');

    $articles = [];
    foreach ($finder as $file) {
        $json = json_decode($file->getContents(), true);
        $articles[$json['versions'][0]['id']] = $json;
    }

    uasort(
        $articles,
        static function (array $article1, array $article2) {
            $article1Dates = [
                'poa' => null,
                'vor' => null,
            ];

            $article2Dates = [
                'poa' => null,
                'vor' => null,
            ];

            foreach ($article1['versions'] as $version) {
                if (null === $article1Dates[$version['status']]) {
                    $article1Dates[$version['status']] = DateTimeImmutable::createFromFormat(
                        DATE_ATOM,
                        $version['published']
                    );
                }
            }

            foreach ($article2['versions'] as $version) {
                if (null === $article2Dates[$version['status']]) {
                    $article2Dates[$version['status']] = DateTimeImmutable::createFromFormat(
                        DATE_ATOM,
                        $version['published']
                    );
                }
            }

            $article1Date = $article1Dates['vor'] ?? $article1Dates['poa'];
            $article2Date = $article2Dates['vor'] ?? $article2Dates['poa'];

            return $article1Date <=> $article2Date;
        }
    );

    return $articles;
};

$app['collections'] = static function () {
    $finder = (new Finder())->files()->name('*.json')->in(__DIR__.'/../data/collections');

    $collections = [];
    foreach ($finder as $file) {
        $json = json_decode($file->getContents(), true);
        $json['selectedCurator'] = [
            'id' => 'id',
            'type' => [
                'id' => 'senior-editor',
                'label' => 'Senior Editor',
            ],
            'name' => [
                'preferred' => '',
                'index' => '',
            ],
        ];
        $json['curators'] = [];
        $collections[$json['id']] = $json;
    }

    uasort(
        $collections,
        static function (array $a, array $b) {
            return DateTimeImmutable::createFromFormat(
                    DATE_ATOM,
                    $b['updated'] ?? $b['published']
                ) <=> DateTimeImmutable::createFromFormat(DATE_ATOM, $a['updated'] ?? $a['published']);
        }
    );

    return $collections;
};

$app['subjects'] = static function () {
    $finder = (new Finder())->files()->name('*.json')->in(__DIR__.'/../data/subjects');

    $subjects = [];
    foreach ($finder as $file) {
        $json = json_decode($file->getContents(), true);
        $subjects[$json['id']] = $json;
    }

    ksort($subjects);

    return $subjects;
};

$app->get(
    '/articles',
    static function (Request $request) use ($app) {
        $articles = $app['articles'];

        $page = $request->query->get('page', 1);
        $perPage = $request->query->get('per-page', 10);

        if ($request->query->has('subject')) {
            $articles = array_filter(
                $articles,
                static function (array $article) use ($request) : bool {
                    $latestVersion = $article['versions'][count($article['versions']) - 1];

                    return count(
                        array_intersect(
                            (array) $request->query->get('subject'),
                            array_map(
                                static function (array $subject) {
                                    return $subject['id'];
                                },
                                $latestVersion['subjects'] ?? []
                            )
                        )
                    );
                }
            );
        }

        $content = [
            'total' => count($articles),
            'items' => [],
        ];

        if ('desc' === $request->query->get('order', 'desc')) {
            $articles = array_reverse($articles);
        }

        $articles = array_slice($articles, ($page * $perPage) - $perPage, $perPage);

        if ($page > 1 && 0 === count($articles)) {
            throw new NotFoundHttpException('No page '.$page);
        }

        foreach ($articles as $i => $article) {
            $latestVersion = $article['versions'][count($article['versions']) - 1];

            unset(
                $latestVersion['issue'],
                $latestVersion['copyright'],
                $latestVersion['authors'],
                $latestVersion['researchOrganisms'],
                $latestVersion['keywords'],
                $latestVersion['digest'],
                $latestVersion['body'],
                $latestVersion['decisionLetter'],
                $latestVersion['authorResponse'],
                $latestVersion['reviewers'],
                $latestVersion['references'],
                $latestVersion['ethics'],
                $latestVersion['funding'],
                $latestVersion['additionalFiles'],
                $latestVersion['dataSets'],
                $latestVersion['acknowledgements'],
                $latestVersion['appendices'],
                $latestVersion['image']['banner']
            );

            $content['items'][] = $latestVersion;
        }

        return new JsonResponse($content);
    }
);

$app->get(
    '/articles/{number}',
    static function (Request $request, string $number) use ($app) {
        if (false === isset($app['articles'][$number])) {
            throw new NotFoundHttpException('Article not found');
        }

        $latestVersion = count($app['articles'][$number]['versions']);

        $subRequest = Request::create(
            '/articles/'.$number.'/versions/'.$latestVersion,
            'GET',
            [],
            $request->cookies->all(),
            [],
            $request->server->all()
        );

        return $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);
    }
);

$app->get(
    '/articles/{number}/versions',
    static function (string $number) use ($app) {
        if (false === isset($app['articles'][$number])) {
            throw new NotFoundHttpException('Article not found');
        }

        $article = $app['articles'][$number];

        if (!empty($article['received'])) {
            $content = [
                'received' => $article['received'],
                'accepted' => $article['accepted'],
            ];
        }

        $content['versions'] = [];
        foreach ($article['versions'] as $articleVersion) {
            unset(
                $articleVersion['issue'],
                $articleVersion['copyright'],
                $articleVersion['authors'],
                $articleVersion['researchOrganisms'],
                $articleVersion['keywords'],
                $articleVersion['digest'],
                $articleVersion['body'],
                $articleVersion['decisionLetter'],
                $articleVersion['authorResponse'],
                $articleVersion['reviewers'],
                $articleVersion['references'],
                $articleVersion['ethics'],
                $articleVersion['funding'],
                $articleVersion['additionalFiles'],
                $articleVersion['dataSets'],
                $articleVersion['acknowledgements'],
                $articleVersion['appendices'],
                $articleVersion['image']['banner']
            );

            $content['versions'][] = $articleVersion;
        }

        return new JsonResponse($content);
    }
);

$app->get(
    '/articles/{number}/versions/{version}',
    static function (string $number, int $version) use ($app) {
        if (false === isset($app['articles'][$number])) {
            throw new NotFoundHttpException('Article not found');
        }

        $article = $app['articles'][$number];

        if (false === isset($article['versions'][$version - 1])) {
            throw new NotFoundHttpException('Version not found');
        }

        $articleVersion = $article['versions'][$version - 1];

        return new JsonResponse($articleVersion);
    }
);

$app->get(
    '/collections',
    static function (Request $request) use ($app) {
        $collections = $app['collections'];

        $page = $request->query->get('page', 1);
        $perPage = $request->query->get('per-page', 10);
        $subjects = (array) $request->query->get('subject', []);
        $containing = (array) $request->query->get('containing', []);

        if (false === empty($subjects)) {
            $collections = array_filter(
                $collections,
                static function ($collection) use ($subjects) {
                    $collectionSubjects = array_map(
                        static function (array $subject) {
                            return $subject['id'];
                        },
                        $collection['subjects'] ?? []
                    );

                    return count(array_intersect($subjects, $collectionSubjects));
                }
            );
        }

        if (false === empty($containing)) {
            $collections = array_filter(
                $collections,
                static function ($collection) use ($containing) {
                    foreach ($collection['content'] as $item) {
                        if (in_array("{$item['type']}/{$item['id']}", $containing, true)) {
                            return true;
                        }
                    }

                    return false;
                }
            );
        }

        $content = [
            'total' => count($collections),
            'items' => [],
        ];

        if ('asc' === $request->query->get('order', 'desc')) {
            $collections = array_reverse($collections);
        }

        $collections = array_slice($collections, ($page * $perPage) - $perPage, $perPage);

        if ($page > 1 && 0 === count($collections)) {
            throw new NotFoundHttpException('No page '.$page);
        }

        foreach ($collections as $i => $collection) {
            unset(
                $collection['curators'],
                $collection['summary'],
                $collection['content'],
                $collection['relatedContent'],
                $collection['podcastEpisodes'],
                $collection['image']['banner']
            );

            $content['items'][] = $collection;
        }

        return new JsonResponse($content);
    }
);

$app->get(
    '/collections/{id}',
    static function (string $id) use ($app) {
        if (false === isset($app['collections'][$id])) {
            throw new NotFoundHttpException('Not found');
        }

        $collection = $app['collections'][$id];

        return new JsonResponse($collection);
    }
);

$app->get(
    '/search',
    static function (Request $request) use ($app) {
        $page = $request->query->get('page', 1);
        $perPage = $request->query->get('per-page', 10);

        $for = strtolower(trim($request->query->get('for')));

        $useDate = $request->query->get('use-date', 'default');
        $sort = $request->query->get('sort', 'relevance');
        $subjects = (array) $request->query->get('subject', []);
        $types = (array) $request->query->get('type', []);

        $startDate = DateTimeImmutable::createFromFormat(
            'Y-m-d',
            $requestStartDate = $request->query->get('start-date', '2000-01-01'),
            new DateTimeZone('Z')
        );
        $endDate = DateTimeImmutable::createFromFormat(
            'Y-m-d',
            $requestEndDate = $request->query->get('end-date', '2999-12-31'),
            new DateTimeZone('Z')
        );

        if (!$startDate || $startDate->format('Y-m-d') !== $requestStartDate) {
            throw new BadRequestHttpException('Invalid start date');
        }

        if (!$endDate || $endDate->format('Y-m-d') !== $requestEndDate) {
            throw new BadRequestHttpException('Invalid end date');
        }

        $startDate = $startDate->setTime(0, 0);
        $endDate = $endDate->setTime(23, 59, 59);

        if ($endDate < $startDate) {
            throw new BadRequestHttpException('End date must be on or after start date');
        }

        $results = [];

        foreach ($app['articles'] as $result) {
            $result = $result['versions'][count($result['versions']) - 1];
            $result['_search'] = strtolower(json_encode($result));

            unset(
                $result['issue'],
                $result['copyright'],
                $result['authors'],
                $result['researchOrganisms'],
                $result['keywords'],
                $result['digest'],
                $result['body'],
                $result['decisionLetter'],
                $result['authorResponse'],
                $result['reviewers'],
                $result['references'],
                $result['ethics'],
                $result['funding'],
                $result['additionalFiles'],
                $result['dataSets'],
                $result['acknowledgements'],
                $result['appendices'],
                $result['image']['banner']
            );

            if ('published' === $useDate) {
                $result['_sort_date'] = DateTimeImmutable::createFromFormat(DATE_ATOM, $result['published']);
            } else {
                $result['_sort_date'] = DateTimeImmutable::createFromFormat(
                    DATE_ATOM,
                    $result['statusDate'] ?? date(DATE_ATOM)
                );
            }

            $results[] = $result;
        }

        foreach ($app['collections'] as $result) {
            $result['_search'] = strtolower(json_encode($result));
            unset(
                $result['curators'],
                $result['summary'],
                $result['content'],
                $result['relatedContent'],
                $result['podcastEpisodes'],
                $result['image']['banner']
            );
            $result['type'] = 'collection';
            if ('published' === $useDate) {
                $result['_sort_date'] = DateTimeImmutable::createFromFormat(DATE_ATOM, $result['published']);
            } else {
                $result['_sort_date'] = DateTimeImmutable::createFromFormat(
                    DATE_ATOM,
                    $result['updated'] ?? $result['published']
                );
            }
            $results[] = $result;
        }

        if ('' !== $for) {
            $results = array_filter(
                $results,
                static function ($result) use ($for) {
                    return false !== strpos($result['_search'], $for);
                }
            );
        }

        array_walk(
            $results,
            static function (&$result) {
                unset($result['_search']);
            }
        );

        $allSubjects = array_values($app['subjects']);

        array_walk(
            $allSubjects,
            static function (&$subject) use ($results) {
                $subject = [
                    'id' => $subject['id'],
                    'name' => $subject['name'],
                    'results' => count(
                        array_filter(
                            $results,
                            static function ($result) use ($subject) {
                                return in_array(
                                    $subject['id'],
                                    array_map(
                                        static function (array $subject) {
                                            return $subject['id'];
                                        },
                                        $result['subjects'] ?? []
                                    ),
                                    true
                                );
                            }
                        )
                    ),
                ];
            }
        );

        $allTypes = [];
        foreach (
            [
                'correction',
                'editorial',
                'feature',
                'insight',
                'research-advance',
                'research-article',
                'research-communication',
                'retraction',
                'registered-report',
                'replication-study',
                'scientific-correspondence',
                'short-report',
                'tools-resources',
            ] as $articleType
        ) {
            $allTypes[$articleType] = count(
                array_filter(
                    $results,
                    static function ($result) use ($articleType) {
                        return $articleType === $result['type'];
                    }
                )
            );
        }

        foreach (
            [
                'blog-article',
                'collection',
                'labs-post',
                'interview',
                'podcast-episode',
            ] as $contentType
        ) {
            $allTypes[$contentType] = count(
                array_filter(
                    $results,
                    static function ($result) use ($contentType) {
                        return $contentType === $result['type'];
                    }
                )
            );
        }

        if (false === empty($types)) {
            $results = array_filter(
                $results,
                static function ($result) use ($types) {
                    return in_array($result['type'], $types, true);
                }
            );
        }

        if (false === empty($subjects)) {
            $results = array_filter(
                $results,
                static function ($result) use ($subjects) {
                    return count(
                        array_intersect(
                            $subjects,
                            array_map(
                                static function (array $subject) {
                                    return $subject['id'];
                                },
                                $result['subjects'] ?? []
                            )
                        )
                    );
                }
            );
        }

        $results = array_filter(
            $results,
            static function ($result) use ($startDate) {
                return $result['_sort_date'] >= $startDate;
            }
        );

        $results = array_filter(
            $results,
            static function ($result) use ($endDate) {
                return $result['_sort_date'] <= $endDate;
            }
        );

        $content = [
            'total' => count($results),
            'items' => [],
            'subjects' => $allSubjects,
            'types' => $allTypes,
        ];

        if ('date' === $sort) {
            usort(
                $results,
                static function (array $a, array $b) {
                    return $b['_sort_date'] <=> $a['_sort_date'];
                }
            );
        }

        if ('asc' === $request->query->get('order', 'desc')) {
            $results = array_reverse($results);
        }

        $results = array_slice($results, ($page * $perPage) - $perPage, $perPage);

        if ($page > 1 && 0 === count($results)) {
            throw new NotFoundHttpException('No page '.$page);
        }

        $content['items'] = array_map(
            static function (array $result) {
                unset($result['_sort_date']);

                return $result;
            },
            $results
        );

        return new JsonResponse($content);
    }
);

$app->get(
    '/subjects',
    static function (Request $request) use ($app) {
        $subjects = $app['subjects'];

        $page = $request->query->get('page', 1);
        $perPage = $request->query->get('per-page', 10);

        $content = [
            'total' => count($subjects),
            'items' => [],
        ];

        if ('desc' === $request->query->get('order', 'desc')) {
            $subjects = array_reverse($subjects);
        }

        $subjects = array_slice($subjects, ($page * $perPage) - $perPage, $perPage);

        if ($page > 1 && 0 === count($subjects)) {
            throw new NotFoundHttpException('No page '.$page);
        }

        foreach ($subjects as $i => $subject) {
            unset($subject['content']);

            $content['items'][] = $subject;
        }

        return new JsonResponse($content);
    }
);

$app->get(
    '/subjects/{id}',
    static function (string $id) use ($app) {
        if (false === isset($app['subjects'][$id])) {
            throw new NotFoundHttpException('Not found');
        }

        $subject = $app['subjects'][$id];

        return new JsonResponse($subject);
    }
);

$app->after(
    static function (Request $request, Response $response) {
        if ('/ping' !== $request->getPathInfo()) {
            $response->headers->set(
                'Cache-Control',
                'public, max-age=300, stale-while-revalidate=300, stale-if-error=86400'
            );
            $response->headers->set('Vary', 'Accept', false);
        }

        $content = $response->getContent();

        $response->setContent(
            str_replace(
                '%base_url%',
                $request->getSchemeAndHttpHost().$request->getBasePath(),
                $content
            )
        );

        $response->headers->set('ETag', md5($response->getContent()));
        $response->isNotModified($request);
    }
);

return $app;
