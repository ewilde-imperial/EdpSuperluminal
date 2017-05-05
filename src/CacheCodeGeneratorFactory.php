<?php

namespace EdpSuperluminal;

use EdpSuperluminal\ClassDeclaration\ClassDeclarationService;
use EdpSuperluminal\ClassDeclaration\FileReflectionUseStatementService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;


class CacheCodeGeneratorFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CacheCodeGenerator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var FileReflectionUseStatementService $useStatementService */
        $useStatementService = $serviceLocator->get('EdpSuperluminal\ClassDeclaration\UseStatementService');

        /** @var ClassDeclarationService $classDeclarationService */
        $classDeclarationService = $serviceLocator->get('EdpSuperluminal\ClassDeclarationService');

        return new CacheCodeGenerator(
            $useStatementService,
            $classDeclarationService
        );
    }



     public function __invoke(ContainerInterface $container, $requestedName, array $options = null){

        $serviceLocator = $container->getServiceLocator();
         /** @var FileReflectionUseStatementService $useStatementService */
        $useStatementService = $serviceLocator->get('EdpSuperluminal\ClassDeclaration\UseStatementService');

        /** @var ClassDeclarationService $classDeclarationService */
        $classDeclarationService = $serviceLocator->get('EdpSuperluminal\ClassDeclarationService');

        return new CacheCodeGenerator(
            $useStatementService,
            $classDeclarationService
        );
    }
}