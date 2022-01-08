<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Formatter\Common;

use HBS\SacEnhancer\{
    Exception\ClassNotFound,
    Exception\UnexpectedValueException,
    Formatter\FormatterInterface,
};

class CompositeFormatter implements FormatterInterface
{
    /**
     * @var array
     */
    protected $formatters;

    public function __construct(array $formatters)
    {
        $this->formatters = $formatters;
    }

    public function format($response)
    {
        foreach ($this->formatters as $formatterClassName) {
            try {
                $reflectionClass = new \ReflectionClass($formatterClassName);
            } catch (\ReflectionException $e) {
                throw new ClassNotFound(
                    sprintf("Class '%s' not found; Reason: %s", $formatterClassName, $e->getMessage()),
                    $e->getCode(), $e
                );
            }

            if (!$reflectionClass->implementsInterface(FormatterInterface::class)) {
                throw new UnexpectedValueException("Formatter class does not implement Formatter Interface");
            }

            /** @var FormatterInterface $formatter */
            $formatter = $reflectionClass->newInstance();
            $response = $formatter->format($response);
        }

        return $response;
    }
}
