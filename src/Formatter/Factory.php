<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Formatter;

use Psr\Http\Message\ServerRequestInterface as Request;
use HBS\SacEnhancer\EnhancerFactoryInterface;

final class Factory
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
