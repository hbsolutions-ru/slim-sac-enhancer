<?php declare(strict_types=1);

namespace HBS\SacEnhancer;

use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Container;
use Slim\Routing\RouteContext;
use HBS\ClassNameTransformer\{
    ClassNameTransformer,
    Exception\ExceptionInterface as ClassNameTransformerException
};
use HBS\SacEnhancer\Exception;

final class EnhancerFactory
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $controllerNamePattern;

    /**
     * EnhancerFactory constructor.
     *
     * @param Container $container
     * @param string $controllerNamePattern
     */
    public function __construct(Container $container, string $controllerNamePattern)
    {
        $this->container = $container;
        $this->controllerNamePattern = $controllerNamePattern;
    }

    /**
     * Returns object with specified type related to the Single Action Controller currently called
     *
     * @param string $type
     * @param Request $request
     * @param string $namePattern
     * @param string $fallback
     * @return EnhancerInterface
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function get(string $type, Request $request, string $namePattern, string $fallback): EnhancerInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $callable = $routeContext->getRoute()->getCallable();

        if (!is_string($callable)) {
            throw new Exception\InvalidArgumentException("Route Callable is not a string");
        }

        try {
            $reflectionClass = new \ReflectionClass($callable);
        } catch (\ReflectionException $e) {
            throw new Exception\ClassNotFound(
                sprintf("Single Action Controller class not found; Class name: %s; Reason: %s", $callable, $e->getMessage()),
                $e->getCode(), $e
            );
        }

        if (!$reflectionClass->implementsInterface(SingleActionControllerInterface::class)) {
            throw new Exception\InvalidArgumentException("Given class does not implement Single Action Controller interface");
        }

        /** @var ClassNameTransformer $resolver */
        $resolver = $this->container->make(ClassNameTransformer::class, [
            'classNamePattern' => $this->controllerNamePattern,
            'typeResolvers' => [
                $type => $namePattern,
            ],
        ]);

        try {
            $className = $resolver->resolve($callable, $type);
        } catch (ClassNameTransformerException $e) {
            $className = $fallback;
        }

        // TODO: check that class implements EnhancerInterface
        return $this->container->get($className);
    }
}
