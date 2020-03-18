<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Laminas\Code\Reflection\ClassReflection;

class ShouldCacheClassSpecification implements SpecificationInterface
{
    /** @var  SpecificationInterface[] */
    protected $specifications, $config;

    /**
     * @param SpecificationInterface[] $specifications
     */
    public function __construct($specifications = array(), $config = array())
    {
        $this->specifications = $specifications;
        $this->config = $config;
    }

    public function isSatisfiedBy(ClassReflection $class)
    {
        foreach ($this->specifications as $specification) {
            if ($specification->isSatisfiedBy($class)) {
                return false;
            }
        }

        return true;
    }
}