<?php declare(strict_types=1);

namespace HBS\SacEnhancer;

use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Container;
use Slim\Routing\RouteContext;
use HBS\ClassNameTransformer\{
    ClassNameTransformer,
    Exception\ExceptionInterface as ClassNameTransformerException
};
use HBS\SacEnhancer\{
    Exception,
    Controller\SingleActionControllerInterface,
};

class EnhancerFactory implements EnhancerFactoryInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var string
     */
    private $controllerNamePattern;

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
    public function get(string $type, Request $request, string $namePattern, string $fallback = null): EnhancerInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $callable = $routeContext->getRoute()->getCallable();

        if (!is_string($callable)) {
            throw new Exception\InvalidArgumentException("Route Callable is not a string");
        }

        if (!$this->implementsInterface($callable, SingleActionControllerInterface::class)) {
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
            if ($fallback === null) {
                throw new Exception\ClassNotFound("Enhancer class not found");
            }
            $className = $fallback;
        }

        $enhancer = $this->container->get($className);

        if (!$this->implementsInterface($enhancer, EnhancerInterface::class)) {
            throw new Exception\UnexpectedValueException("Enhancer class does not implement Enhancer Interface");
        }

        return $enhancer;
    }

    private function implementsInterface($objectOrClass, string $interfaceName): bool
    {
        try {
            $reflectionClass = new \ReflectionClass($objectOrClass);
        } catch (\ReflectionException $e) {
            throw new Exception\ClassNotFound(
                sprintf("Class '%s' not found; Reason: %s", $objectOrClass, $e->getMessage()),
                $e->getCode(), $e
            );
        }

        return $reflectionClass->implementsInterface($interfaceName);
    }
}
