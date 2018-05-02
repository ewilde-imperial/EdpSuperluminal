<?php

namespace EdpSuperluminal\ClassDeclaration;

use Zend\ServiceManager\Factory\FactoryInterface;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;


class ClassDeclarationServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
        /** @var ClassUseNameService $classUseNameService */
        $classUseNameService = $container->get('EdpSuperluminal\ClassDeclaration\ClassUseNameService');

        return new ClassDeclarationService(
            new ClassTypeService(),
            new ExtendsStatementService($classUseNameService),
            new InterfaceStatementService($classUseNameService)
        );
    }
}