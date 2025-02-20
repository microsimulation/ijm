<?php

namespace Microsimulation\Journal\Exception;

use Symfony\Component\HttpFoundation\Response;
use Throwable;
use UnexpectedValueException;

class EarlyResponse extends UnexpectedValueException
{
    private $response;

    public function __construct(Response $response, Throwable $previous = null)
    {
        parent::__construct('Early response', 0, $previous);

        $this->response = $response;
    }

    final public function getResponse() : Response
    {
        return $this->response;
    }
}
