<?php

namespace EdpSuperluminal\ClassDeclaration;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;


class ClassUseNameServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
        $useStatementService = $container->get('EdpSuperluminal\ClassDeclaration\UseStatementService');

        return new ClassUseNameService($useStatementService);
    }
}