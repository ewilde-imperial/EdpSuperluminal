<?php

namespace EdpSuperluminal;

use EdpSuperluminal\ClassDeclaration\ClassDeclarationService;
use EdpSuperluminal\ClassDeclaration\FileReflectionUseStatementService;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;


class CacheCodeGeneratorFactory implements FactoryInterface
{
     public function __invoke(ContainerInterface $container, $requestedName, array $options = null){

         /** @var FileReflectionUseStatementService $useStatementService */
        $useStatementService = $container->get('EdpSuperluminal\ClassDeclaration\UseStatementService');

        /** @var ClassDeclarationService $classDeclarationService */
        $classDeclarationService = $container->get('EdpSuperluminal\ClassDeclarationService');

        return new CacheCodeGenerator(
            $useStatementService,
            $classDeclarationService
        );
    }
}