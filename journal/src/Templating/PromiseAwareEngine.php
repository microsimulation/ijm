<?php

namespace Microsimulation\Journal\Templating;

use Symfony\Component\Templating\EngineInterface;
use function GuzzleHttp\Promise\all;

final class PromiseAwareEngine implements EngineInterface
{
    private $engine;

    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
    }

    public function render($name, array $parameters = [])
    {
        return $this->engine->render($name, all($parameters)->wait());
    }

    public function exists($name)
    {
        return $this->engine->exists($name);
    }

    public function supports($name)
    {
        return $this->engine->supports($name);
    }
}
