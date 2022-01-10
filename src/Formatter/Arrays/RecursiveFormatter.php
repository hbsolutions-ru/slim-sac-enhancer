<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Formatter\Arrays;

use HBS\SacEnhancer\{
    Formatter\FormatterInterface,
};

class RecursiveFormatter extends BaseFormatter
{
    /**
     * @var FormatterInterface
     */
    protected $formatter;

    public function __construct(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    public function formatArray(array $response)
    {
        $response = $this->formatter->format($response);

        if (!is_array($response)) {
            return $response;
        }

        foreach ($response as &$item) {
            if (is_array($item)) {
                $item = $this->formatArray($item);
            }
        }

        return $response;
    }
}
