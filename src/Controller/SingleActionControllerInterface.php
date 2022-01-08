<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Controller;

use Psr\Http\Message\{
    ResponseInterface as Response,
    ServerRequestInterface as Request,
};

interface SingleActionControllerInterface
{
    public function __invoke(Request $request, Response $response, $args): Response;
}
