<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Controller\Api\Json\ErrorResponse;

use Psr\Http\Message\{
    ResponseInterface as Response,
    ResponseFactoryInterface as ResponseFactory,
    ServerRequestInterface as Request,
};
use HBS\SacEnhancer\Controller\Api\Exception\ExceptionInterface;

class Factory
{
    protected const DEFAULT_CODE = 400;

    /**
     * @var ResponseFactory
     */
    protected $responseFactory;

    /**
     * @var ExceptionInterface
     */
    protected $fallbackError;

    /**
     * @var bool
     */
    protected $withBody;

    /**
     * Factory constructor.
     *
     * @param ResponseFactory $responseFactory
     * @param ExceptionInterface $fallbackError
     * @param bool $withBody
     */
    public function __construct(
        ResponseFactory $responseFactory,
        ExceptionInterface $fallbackError,
        bool $withBody = true
    ) {
        $this->responseFactory = $responseFactory;
        $this->fallbackError = $fallbackError;
        $this->withBody = $withBody;
    }

    public function get(\Exception $exception, Request $request): Response
    {
        $response = $this->responseFactory->createResponse();

        $exception = ($exception instanceof ExceptionInterface) ? $exception : $this->fallbackError;

        if ($this->withBody) {
            $response = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write((string)json_encode(
                $exception->getBodyData($request)
            ));
        }

        return $response->withStatus($this->getStatusCode($exception));
    }

    protected function getStatusCode(\Exception $exception): int
    {
        $code = intval($exception->getCode());

        return $code < 100 || $code > 599 ? static::DEFAULT_CODE : $code;
    }
}
