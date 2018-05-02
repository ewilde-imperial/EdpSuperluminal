<?php

namespace EdpSuperluminal;

use EdpSuperluminal\ShouldCacheClass\ShouldCacheClassSpecification;
use Zend\ServiceManager\Factory\FactoryInterface;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

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