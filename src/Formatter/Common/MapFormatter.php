<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Formatter\Common;

use HBS\SacEnhancer\Formatter\FormatterInterface;

class MapFormatter implements FormatterInterface
{
    /**
     * @var callable
     */
    protected $mapper;

    public function __construct(callable $mapper)
    {
        $this->mapper = $mapper;
    }

    public function format($response, $queryArgs = [])
    {
        return call_user_func($this->mapper, $response);
    }
}
