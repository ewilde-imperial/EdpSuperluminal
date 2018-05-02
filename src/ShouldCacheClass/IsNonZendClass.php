<?php

namespace EdpSuperluminal\ShouldCacheClass;

use Zend\Code\Reflection\ClassReflection;

class IsNonZendClass implements SpecificationInterface
{
    protected $config;

    public function __construct($config = array())
    {
        $this->config = $config;
    }

    /**
     * @param ClassReflection $class
     * @return bool
     */
    public function isSatisfiedBy(ClassReflection $class)
    {
    	// var_Dump(0 !== strpos('Doctrine\Test', 'Zend') && 0 !== strpos('Doctrine\Test', 'Doctrine'));

        $defaultIncluded = array(
            'Zend',
            'Interop',
            'Doctrine\\',
            'Gedmo\\',
        );
        $defaultExcluded = array();

        $included = (isset($this->config['edp-superluminal']['included']) && count($this->config['edp-superluminal']['included']) > 0) ? $this->config['edp-superluminal']['included'] : $defaultIncluded;
        $excluded = (isset($this->config['edp-superluminal']['excluded']) && count($this->config['edp-superluminal']['excluded']) > 0) ? $this->config['edp-superluminal']['excluded'] : $defaultExcluded;

        $isNotIncluded = false;
        $isExcluded = false;

        // check for classes which are NOT included - e.g. non-Zend classes
        foreach ($included as $includedClass) {
            $isNotIncluded = (strpos($class->getName(), $includedClass) === false);
            if (!$isNotIncluded) { break; } // service is included, so move on to the next test
        }

        // check for classes which are *specifically* excluded - e.g. Zend classes which use __DIR__
        foreach ($excluded as $excludedClass) {
            $isExcluded = (strpos($class->getName(), $excludedClass) !== false);
            if ($isExcluded) { $isNotIncluded = true; break; }
        }

        // if (!$isNotIncluded) { // display included classes
        //     var_dump($class->getName());
        // }
        return $isNotIncluded;
    }
}