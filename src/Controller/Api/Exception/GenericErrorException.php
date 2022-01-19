<?php  declare(strict_types=1);

namespace HBS\SacEnhancer\Controller\Api\Exception;

use Psr\Http\Message\ServerRequestInterface as Request;

final class GenericErrorException extends \RuntimeException implements ExceptionInterface
{
    public const ERROR_MESSAGE = 'generic error';

    public function __construct($message = self::ERROR_MESSAGE, $code = 400, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getBodyData(?Request $request = null)
    {
        return self::ERROR_MESSAGE;
    }
}
