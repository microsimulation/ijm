<?php

namespace Microsimulation\Journal\Router;

interface ParameterResolver
{
    public function resolve(string $route, array $parameters) : array;
}
