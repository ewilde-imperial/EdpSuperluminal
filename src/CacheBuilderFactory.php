<?php

namespace EdpSuperluminal;

use EdpSuperluminal\ShouldCacheClass\ShouldCacheClassSpecification;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class CacheBuilderFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CacheBuilder
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var CacheCodeGenerator $cacheCodeGenerator */
        $cacheCodeGenerator = $serviceLocator->get('EdpSuperluminal\CacheCodeGenerator');

        /** @var ShouldCacheClassSpecification $shouldCacheClass */
        $shouldCacheClass = $serviceLocator->get('EdpSuperluminal\ShouldCacheClass');

        return new CacheBuilder($cacheCodeGenerator, $shouldCacheClass);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){

        $serviceLocator = $container->getServiceLocator();
        /** @var CacheCodeGenerator $cacheCodeGenerator */
        $cacheCodeGenerator = $serviceLocator->get('EdpSuperluminal\CacheCodeGenerator');

        /** @var ShouldCacheClassSpecification $shouldCacheClass */
        $shouldCacheClass = $serviceLocator->get('EdpSuperluminal\ShouldCacheClass');

        return new CacheBuilder($cacheCodeGenerator, $shouldCacheClass);
    }
}