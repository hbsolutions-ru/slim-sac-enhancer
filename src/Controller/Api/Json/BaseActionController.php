<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Controller\Api\Json;

use Psr\Http\Message\{
    ResponseInterface as Response,
    ServerRequestInterface as Request,
};
use Psr\Log\{
    LoggerInterface as Logger,
    NullLogger,
};
use DI\Container;
use HBS\SacEnhancer\{
    Exception\ExceptionInterface,
    Formatter\Common\EmptyFormatter,
    FormatterFactory\ResponseFormatterFactoryInterface as ResponseFormatterFactory,
};

abstract class BaseActionController extends \HBS\SacEnhancer\Controller\BaseActionController
{
    protected const DEFAULT_CODE_SUCCESS = 200;

    protected Logger $logger;

    protected ResponseFormatterFactory $responseFormatterFactory;

    public function __construct(Container $container)
    {
        $this->logger = $container->has(Logger::class) ? $container->get(Logger::class) : new NullLogger();
        $this->responseFormatterFactory = $container->get(ResponseFormatterFactory::class);
    }

    protected function action(Request $request, Response $response, array $args): Response
    {
        $apiResponse = $this->apiAction(
            (array)$request->getParsedBody(),
            $args,
            $request->getQueryParams(),
            $request
        );

        if ($apiResponse !== null) {
            try {
                $formatter = $this->responseFormatterFactory->get($request, EmptyFormatter::class);
                $apiResponse = $formatter->format($apiResponse, $request->getQueryParams());
            } catch (ExceptionInterface $exception) {
                $this->logger->error(
                    sprintf("Formatter error: %s", $exception->getMessage())
                );
            }
            $response->getBody()->write((string)json_encode($apiResponse));
        }

        return $response
            ->withStatus(static::DEFAULT_CODE_SUCCESS)
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
