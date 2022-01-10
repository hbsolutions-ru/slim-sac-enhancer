<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Controller;

use Psr\Http\Message\{
    ResponseInterface as Response,
    ServerRequestInterface as Request,
};

abstract class BaseActionController implements SingleActionControllerInterface
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws \Exception
     */
    public function __invoke(Request $request, Response $response, $args): Response
    {
        return $this->actionWrapper($request, $response, empty($args) ? [] : (array)$args);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Exception
     */
    abstract protected function action(Request $request, Response $response, array $args): Response;

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws \Exception
     */
    protected function actionWrapper(Request $request, Response $response, array $args): Response
    {
        return $this->action($request, $response, $args);
    }
}
