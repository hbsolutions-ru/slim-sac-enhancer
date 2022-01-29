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

    public function format($response, array $queryArgs = [])
    {
        foreach ($this->formatters as $formatterClass) {
            try {
                $reflectionClass = new \ReflectionClass($formatterClass);
            } catch (\ReflectionException $e) {
                throw new ClassNotFound(
                    sprintf("Class '%s' not found; Reason: %s", $formatterClass, $e->getMessage()),
                    $e->getCode(), $e
                );
            }

            if (!$reflectionClass->implementsInterface(FormatterInterface::class)) {
                throw new UnexpectedValueException("Formatter class does not implement Formatter Interface");
            }

            /** @var FormatterInterface $formatter */
            $formatter = is_string($formatterClass)
                ? $reflectionClass->newInstance()
                : $formatterClass;

            $response = $formatter->format($response, $queryArgs);
        }

        return $response;
    }
}
