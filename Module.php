<?php

namespace EdpSuperluminal;

use Laminas\Console\Request as ConsoleRequest;
use Laminas\Mvc\MvcEvent;

/**
 * Create a class cache of all classes used.
 *
 * @package EdpSuperluminal
 */
class Module
{
    /**
     * Attach the cache event listener
     *
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        /** @var CacheBuilder $cacheBuilder */
        $cacheBuilder = $serviceManager->get('EdpSuperluminal\CacheBuilder');

        $eventManager = $e->getApplication()->getEventManager()->getSharedManager();
        $eventManager->attach('Laminas\Mvc\Application', 'finish', function (MvcEvent $e) use ($cacheBuilder) {
            $request = $e->getRequest();

            if ($request instanceof ConsoleRequest ||
                $request->getQuery()->get('EDPSUPERLUMINAL_CACHE', null) === null) {
                return;
            }

            $cacheBuilder->cache(ZF_CLASS_CACHE);
        });
    }

    public function getAutoloaderConfig(){
        return array(
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
//                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    __NAMESPACE__ => __DIR__ . '/src/' ,
                ),
            ),
        );
    }
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'EdpSuperluminal\ClassDeclaration\UseStatementService' => 'EdpSuperluminal\ClassDeclaration\FileReflectionUseStatementService',
            ),
            'factories' => array(
                'EdpSuperluminal\CacheCodeGenerator'     => 'EdpSuperluminal\CacheCodeGeneratorFactory',
                'EdpSuperluminal\CacheBuilder'     => 'EdpSuperluminal\CacheBuilderFactory',
                'EdpSuperluminal\ShouldCacheClass'     => 'EdpSuperluminal\ShouldCacheClass\ShouldCacheClassSpecificationFactory',
                'EdpSuperluminal\ClassDeclarationService'     => 'EdpSuperluminal\ClassDeclaration\ClassDeclarationServiceFactory',
                'EdpSuperluminal\ClassDeclaration\ClassUseNameService'     => 'EdpSuperluminal\ClassDeclaration\ClassUseNameServiceFactory',
            )
        );
    }
}
