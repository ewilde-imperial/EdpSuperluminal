<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;


class ShouldCacheClassSpecificationFactory implements FactoryInterface
{
    protected $specificationClasses = array(
        'IsNonZendClass',
        'IsZendAutoloader',
        'IsAnAnnotatedClass',
        'IsZf2BasedAutoloader',
        'IsCoreClass',
        // 'IsInteropClass',

    );

    public function __construct($specificationClasses = null)
    {
        if (!is_null($specificationClasses)) {
            $this->specificationClasses = $specificationClasses;
        }
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
        $specifications = array();
        $config = $container->get('Config');

        foreach ($this->specificationClasses as $specificationClass) {
            $specificationClass = 'EdpSuperluminal\ShouldCacheClass\\' . $specificationClass;

            if (!class_exists($specificationClass)) {
                throw new \Exception("The specification '{$specificationClass}' does not exist!");
            }

            $specification = new $specificationClass($config);

            if (!$specification instanceof SpecificationInterface) {
                throw new \Exception("The specifications provided must implement SpecificationInterface!");
            }

            $specifications[] = $specification;
        }

        return new ShouldCacheClassSpecification($specifications);
    }
}