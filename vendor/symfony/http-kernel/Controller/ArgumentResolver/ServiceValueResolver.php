<?php
 namespace Symfony\Component\HttpKernel\Controller\ArgumentResolver; use Psr\Container\ContainerInterface; use Symfony\Component\HttpFoundation\Request; use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface; use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata; final class ServiceValueResolver implements ArgumentValueResolverInterface { private $container; public function __construct(ContainerInterface $container) { $this->container = $container; } public function supports(Request $request, ArgumentMetadata $argument) { $controller = $request->attributes->get('_controller'); if (\is_array($controller) && \is_callable($controller, true) && \is_string($controller[0])) { $controller = $controller[0].'::'.$controller[1]; } elseif (!\is_string($controller) || '' === $controller) { return false; } if ('\\' === $controller[0]) { $controller = ltrim($controller, '\\'); } if (!$this->container->has($controller) && false !== $i = strrpos($controller, ':')) { $controller = substr($controller, 0, $i).strtolower(substr($controller, $i)); } return $this->container->has($controller) && $this->container->get($controller)->has($argument->getName()); } public function resolve(Request $request, ArgumentMetadata $argument) { if (\is_array($controller = $request->attributes->get('_controller'))) { $controller = $controller[0].'::'.$controller[1]; } if ('\\' === $controller[0]) { $controller = ltrim($controller, '\\'); } if (!$this->container->has($controller)) { $i = strrpos($controller, ':'); $controller = substr($controller, 0, $i).strtolower(substr($controller, $i)); } yield $this->container->get($controller)->get($argument->getName()); } } 