<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Zend\Code\Reflection\ClassReflection;

class IsNonZendClass implements SpecificationInterface
{
    /**
     * @param ClassReflection $class
     * @return bool
     */
    public function isSatisfiedBy(ClassReflection $class)
    {
    	// var_Dump(0 !== strpos('Doctrine\Test', 'Zend') && 0 !== strpos('Doctrine\Test', 'Doctrine'));
    	// die();
        return
         	0 !== strpos($class->getName(), 'Zend')  
            && 0 !== strpos($class->getName(), 'Interop')
            && 0 !== strpos($class->getName(), 'Doctrine\\')
        	&& 0 !== strpos($class->getName(), 'Gedmo\\')
        ;
    }
}