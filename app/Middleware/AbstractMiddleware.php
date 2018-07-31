<?php

namespace App\Middleware;

use Symfony\Component\HttpFoundation\Request;

abstract class AbstractMiddleware
{
    /** @var Request */
    private $request;
    /** @var bool */
    private $next;

    public function __construct(Request $request)
    {
        $this->request  = $request;
//        $this->next     = $next;
    }

    public function resolve()
    {
        return $this->handle($this->request);
    }

    abstract protected function handle(Request $request);

}