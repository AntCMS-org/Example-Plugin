<?php

namespace AntCMS\Plugins\Example\Api;

use AntCMS\ApiResponse as Response;

class PublicApi
{
    public function foo(array $data): Response
    {
        return new Response('bar');
    }

    public function bar(array $data): Response
    {
        return new Response('foo');
    }

    public function dumpApiParams(array $data): Response
    {
        return new Response($data);
    }
}
