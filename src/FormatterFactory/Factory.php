<?php declare(strict_types=1);

namespace HBS\SacEnhancer\FormatterFactory;

use Psr\Http\Message\ServerRequestInterface as Request;
use HBS\SacEnhancer\Formatter\FormatterInterface;
use HBS\SacEnhancer\EnhancerFactoryInterface;

class Factory implements FormatterFactoryInterface
{
    /**
     * @var EnhancerFactoryInterface
     */
    private $enhancerFactory;

    /**
     * @var string
     */
    private $formatterNamePattern;

    /**
     * Factory constructor.
     *
     * @param EnhancerFactoryInterface $enhancerFactory
     * @param string $formatterNamePattern
     */
    public function __construct(EnhancerFactoryInterface $enhancerFactory, string $formatterNamePattern)
    {
        $this->enhancerFactory = $enhancerFactory;
        $this->formatterNamePattern = $formatterNamePattern;
    }

    /**
     * @param Request $request
     * @param string $fallbackFormatter
     * @return FormatterInterface
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \HBS\SacEnhancer\Exception\ExceptionInterface
     */
    public function get(Request $request, string $fallbackFormatter): FormatterInterface
    {
        /** @var FormatterInterface $formatter */
        $formatter = $this->enhancerFactory->get(
            FormatterInterface::class,
            $request,
            $this->formatterNamePattern,
            $fallbackFormatter
        );
        return $formatter;
    }
}
