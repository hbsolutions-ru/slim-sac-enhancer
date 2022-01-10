<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Controller\Api\Json;

use Psr\Http\Message\{
    ResponseInterface as Response,
    ServerRequestInterface as Request,
};

abstract class BaseActionController extends \HBS\SacEnhancer\Controller\BaseActionController
{
    protected const DEFAULT_CODE_SUCCESS = 200;
    protected const DEFAULT_CODE_ERROR = 400;

    protected function action(Request $request, Response $response, array $args): Response
    {
        $apiResponse = $this->apiAction(
            (array)$request->getParsedBody(),
            $args,
            $request->getQueryParams(),
            $request
        );

        if ($apiResponse !== null) {
            $response->getBody()->write((string)json_encode($apiResponse));
        }

        return $response;
    }

    protected function actionWrapper(Request $request, Response $response, array $args): Response
    {
        try {
            $response = $this->action($request, $response, $args)
                ->withStatus(static::DEFAULT_CODE_SUCCESS);
        } catch (\Exception $exception) {
            $response = $response->withStatus(static::DEFAULT_CODE_ERROR);
        }

        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    /**
     * Action implementation in the final class
     *
     * @param array $bodyArgs
     * @param array $pathArgs
     * @param array $queryArgs
     * @param Request $request
     * @return object|array|null
     */
    abstract protected function apiAction(array $bodyArgs, array $pathArgs, array $queryArgs, Request $request);
}
