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
    Controller\Api\Json\ErrorResponse\Factory as ErrorResponseFactory,
    Exception\ExceptionInterface,
    Formatter\Common\EmptyFormatter,
    Formatter\Factory as FormatterFactory,
};

abstract class BaseActionController extends \HBS\SacEnhancer\Controller\BaseActionController
{
    protected const DEFAULT_CODE_SUCCESS = 200;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var ErrorResponseFactory
     */
    protected $errorResponseFactory;

    /**
     * @var FormatterFactory
     */
    protected $formatterFactory;

    public function __construct(Container $container)
    {
        $this->logger = $container->has(Logger::class) ? $container->get(Logger::class) : new NullLogger();
        $this->errorResponseFactory = $container->get(ErrorResponseFactory::class);
        $this->formatterFactory = $container->get(FormatterFactory::class);
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
                $formatter = $this->formatterFactory->get($request, EmptyFormatter::class);
                $apiResponse = $formatter->format($apiResponse);
            } catch (ExceptionInterface $exception) {
                $this->logger->error(
                    sprintf("Formatter error: %s", $exception->getMessage())
                );
            }
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
            $this->logger->error($exception->getMessage());
            $response = $this->errorResponseFactory->get($exception, $request);
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
