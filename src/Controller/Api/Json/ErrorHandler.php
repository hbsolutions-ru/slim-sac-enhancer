<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Controller\Api\Json;

use InvalidArgumentException;
use Throwable;

use Psr\Http\Message\{
    ResponseFactoryInterface as ResponseFactory,
    ResponseInterface as Response,
    ServerRequestInterface as Request,
};
use Psr\Log\{
    LoggerInterface,
    LogLevel,
    NullLogger,
};

use Fig\Http\Message\StatusCodeInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use HBS\SacEnhancer\{
    Formatter\FormatterInterface,
    Utility\HttpStatusUtility,
};

use function json_encode;

class ErrorHandler implements ErrorHandlerInterface
{
    protected int $responseCode = StatusCodeInterface::STATUS_BAD_REQUEST;
    protected string $logLevel = LogLevel::ERROR;

    protected ResponseFactory $responseFactory;

    protected FormatterInterface $formatter;

    protected bool $exceptionCodeToHttp = false;

    protected LoggerInterface $logger;

    public function __construct(
        ResponseFactory $responseFactory,
        FormatterInterface $formatter,
        bool $exceptionCodeToHttp = false,
        LoggerInterface $logger = null
    ) {
        $this->responseFactory = $responseFactory;
        $this->formatter = $formatter;
        $this->exceptionCodeToHttp = $exceptionCodeToHttp;
        $this->logger = $logger ?: new NullLogger();
    }

    public function __invoke(
        Request $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): Response {
        if ($logErrors) {
            $this->logger->log($this->logLevel, $exception->getMessage());
        }

        $response = $this->responseFactory->createResponse(
            $this->getHttpResponseStatusCode($exception)
        );

        $responseData = $this->formatter->format($exception);

        if ($responseData === null) {
            return $response;
        }

        $response->getBody()->write((string)json_encode($responseData));

        return $response->withHeader('Content-Type', 'application/json');
    }

    protected function getHttpResponseStatusCode(Throwable $exception): int
    {
        if (!$this->exceptionCodeToHttp) {
            return $this->responseCode;
        }

        // PDO could throw exceptions with string codes
        // E.g.,
        // SQLSTATE[HY000]: General error - will return "HY000" as code,
        // SQLSTATE[42S02]: Base table or view not found... - will return "42S02" as code
        if (!is_int($exception->getCode())) {
            return $this->responseCode;
        }

        try {
            return HttpStatusUtility::filterStatus($exception->getCode());
        } catch (InvalidArgumentException $argumentException) {
            return $this->responseCode;
        }
    }
}
