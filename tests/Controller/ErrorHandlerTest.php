<?php declare(strict_types=1);

namespace Tests\Controller;

use \RuntimeException;

use Slim\Psr7\Factory\{
    RequestFactory,
    ResponseFactory,
};
use HBS\SacEnhancer\Controller\Api\Json\ErrorHandler;

use PHPUnit\Framework\TestCase;
use Tests\Controller\Objects\ExceptionFormatter;

final class ErrorHandlerTest extends TestCase
{
    public function testErrorHandler()
    {
        $httpResponseCode = 404;
        $errorMessage = "Page not found";

        $requestFactory = new RequestFactory();
        $responseFactory = new ResponseFactory();
        $exceptionFormatter = new ExceptionFormatter();
        $exception = new RuntimeException($errorMessage, $httpResponseCode);

        $errorHandler = new ErrorHandler($responseFactory, $exceptionFormatter, true);

        $response = $errorHandler(
            $requestFactory->createRequest("GET", "https://github.com/hbsolutions-ru"),
            $exception,
            false,
            false,
            false
        );

        $this->assertEquals($httpResponseCode, $response->getStatusCode());
        $this->assertEquals("application/json", $response->getHeader("Content-Type")[0]);

        $response->getBody()->rewind();
        $jsonData = json_decode($response->getBody()->getContents(), true);

        $this->assertNotNull($jsonData);
        $this->assertIsArray($jsonData);

        $this->assertArrayHasKey("errors", $jsonData);
        $this->assertIsArray($jsonData['errors']);
        $this->assertArrayHasKey(0, $jsonData['errors']);

        $errorData = $jsonData['errors'][0];

        $this->assertArrayHasKey("code", $errorData);
        $this->assertEquals($httpResponseCode, $errorData['code']);

        $this->assertArrayHasKey("message", $errorData);
        $this->assertEquals($errorMessage, $errorData['message']);
    }
}
