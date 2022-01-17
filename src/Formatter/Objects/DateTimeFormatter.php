<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Formatter\Objects;

class DateTimeFormatter extends BaseFormatter
{
    /**
     * @var string
     */
    protected $format;

    public function __construct(string $format)
    {
        $this->format = $format;
    }

    protected function formatObject($response)
    {
        if (!($response instanceof \DateTime)) {
            return $response;
        }

        return $response->format($this->format);
    }
}
