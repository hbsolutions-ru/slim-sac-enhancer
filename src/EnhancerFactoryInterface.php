<?php declare(strict_types=1);

namespace HBS\SacEnhancer;

use Psr\Http\Message\ServerRequestInterface as Request;

interface EnhancerFactoryInterface
{
    public function get(string $type, Request $request, string $namePattern, string $fallback): EnhancerInterface;
}
