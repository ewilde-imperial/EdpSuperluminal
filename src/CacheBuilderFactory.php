<?php

namespace EdpSuperluminal;

use EdpSuperluminal\ShouldCacheClass\ShouldCacheClassSpecification;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;

class CacheBuilderFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){

        /** @var CacheCodeGenerator $cacheCodeGenerator */
        $cacheCodeGenerator = $container->get('EdpSuperluminal\CacheCodeGenerator');

        /** @var ShouldCacheClassSpecification $shouldCacheClass */
        $shouldCacheClass = $container->get('EdpSuperluminal\ShouldCacheClass');

        return new CacheBuilder($cacheCodeGenerator, $shouldCacheClass);
    }
}