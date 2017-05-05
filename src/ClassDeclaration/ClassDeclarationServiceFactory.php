<?php

namespace EdpSuperluminal\ClassDeclaration;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;


class ClassDeclarationServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ClassUseNameService $classUseNameService */
        $classUseNameService = $serviceLocator->get('EdpSuperluminal\ClassDeclaration\ClassUseNameService');

        return new ClassDeclarationService(
            new ClassTypeService(),
            new ExtendsStatementService($classUseNameService),
            new InterfaceStatementService($classUseNameService)
        );
    }


    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
        $serviceLocator = $container->getServiceLocator();
        /** @var ClassUseNameService $classUseNameService */
        $classUseNameService = $serviceLocator->get('EdpSuperluminal\ClassDeclaration\ClassUseNameService');

        return new ClassDeclarationService(
            new ClassTypeService(),
            new ExtendsStatementService($classUseNameService),
            new InterfaceStatementService($classUseNameService)
        );
    }
}