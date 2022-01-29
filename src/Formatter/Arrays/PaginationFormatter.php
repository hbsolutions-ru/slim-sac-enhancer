<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Formatter\Arrays;

class PaginationFormatter extends BaseFormatter
{
    public const DATA = 'data';
    public const LIMIT = 'limit';
    public const OFFSET = 'offset';
    public const TOTAL = 'total';

    /**
     * @var callable
     */
    protected $mapper;

    /**
     * @var int
     */
    protected $defaultLimit;

    public function __construct(?callable $mapper = null, int $defaultLimit = 20)
    {
        $this->mapper = $mapper;
        $this->defaultLimit = $defaultLimit;
    }

    protected function formatArray(array $response, $queryArgs = [])
    {
        $limit = intval($queryArgs[static::LIMIT] ?? $this->defaultLimit);
        $offset = intval($queryArgs[static::OFFSET] ?? 0);
        $total = count($response);

        $response = array_slice($response, $offset, $limit);

        if (is_callable($this->mapper)) {
            $response = array_map($this->mapper, $response);
        }

        return [
            static::DATA => array_values($response),
            static::LIMIT => $limit,
            static::OFFSET => $offset,
            static::TOTAL => $total,
        ];
    }
}
