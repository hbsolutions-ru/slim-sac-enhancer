<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Controller\Api\Json;

use Throwable;
use Psr\Http\Message\{
    ResponseFactoryInterface as ResponseFactory,
    ResponseInterface as Response,
    ServerRequestInterface as Request,
};
use Slim\Interfaces\ErrorHandlerInterface;
use HBS\SacEnhancer\Formatter\FormatterInterface;

class ErrorHandler implements ErrorHandlerInterface
{
    protected const DEFAULT_CODE_ERROR = 400;

    protected int $responseCode = self::DEFAULT_CODE_ERROR;

    protected ResponseFactory $responseFactory;

    protected FormatterInterface $formatter;

    protected bool $exceptionCodeToHttp = false;

    public function __construct(
        ResponseFactory $responseFactory,
        FormatterInterface $formatter,
        bool $exceptionCodeToHttp = false
    ) {
        $this->responseFactory = $responseFactory;
        $this->formatter = $formatter;
        $this->exceptionCodeToHttp = $exceptionCodeToHttp;
    }

    public function __invoke(
        Request $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): Response {
        $responseCode = $this->exceptionCodeToHttp ? $exception->getCode() : $this->responseCode;
        $response = $this->responseFactory->createResponse($responseCode ?: self::DEFAULT_CODE_ERROR);

        $responseData = $this->formatter->format($exception);

        if ($responseData === null) {
            return $response;
        }

        $response->getBody()->write((string)json_encode($responseData));

        return $response->withHeader('Content-Type', 'application/json');
    }
}