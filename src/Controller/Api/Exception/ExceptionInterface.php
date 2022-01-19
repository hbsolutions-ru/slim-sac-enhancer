<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Controller\Api\Exception;

use Psr\Http\Message\ServerRequestInterface as Request;
use HBS\SacEnhancer\Exception\ExceptionInterface as CommonInterface;

interface ExceptionInterface extends CommonInterface
{
    public function getBodyData(?Request $request = null);
}
