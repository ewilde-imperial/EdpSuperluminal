<?php

namespace EdpSuperluminal;

use EdpSuperluminal\ShouldCacheClass\ShouldCacheClassSpecification;
use Laminas\Code\Reflection\ClassReflection;
use Laminas\Code\Scanner\FileScanner;

class CacheBuilder
{
    protected $knownClasses = array();

    /**
     * @var CacheCodeGenerator
     */
    protected $cacheCodeGenerator;

    /**
     * @var ShouldCacheClassSpecification
     */
    protected $shouldCacheClass;

    /**
     * @param CacheCodeGenerator $cacheCodeGenerator
     * @param ShouldCacheClassSpecification $shouldCacheClass
     */
    public function __construct(CacheCodeGenerator $cacheCodeGenerator, ShouldCacheClassSpecification $shouldCacheClass)
    {
        $this->cacheCodeGenerator = $cacheCodeGenerator;
        $this->shouldCacheClass = $shouldCacheClass;
    }


    protected function everyInArray($neadles,$hey){
        foreach($neadles as $neadle){
            if(!in_array($neadle,$hey))return false;
        }
        return true;
    }

    /**
     * Cache declared interfaces and classes to a single file
     * @todo - extract the file_put_contents / php_strip_whitespace calls or figure out a way to mock the filesystem
     *
     * @param string
     * @return void
     */
    public function cache($classCacheFilename)
    {
        if (file_exists($classCacheFilename)) {
            $this->reflectClassCache($classCacheFilename);
            $code = "<?php\n";//file_get_contents($classCacheFilename);
        } else {
            $code = "<?php\n";
        }

        // put the classes in a consistent order
        // this mitagates intermittent errors
        // when different people build the class cache
        $traits = sort(get_declared_traits());
        $interfaces = sort(get_declared_interfaces());
        $classes = sort(get_declared_classes());

        $classes = array_merge($traits, $interfaces, $classes);

        // print_r($classes);die();
        foreach ($classes as $class) {
            $class = new ClassReflection($class);

            if (!$this->shouldCacheClass->isSatisfiedBy($class)) {
                continue;
            }

            // Skip any classes we already know about
            if (in_array($class->getName(), $this->knownClasses)) {
                continue;
            }

            $this->knownClasses[] = $class->getName();

            // $code .= $this->cacheCodeGenerator->getCacheCode($class);


        }
        
        $classsAddedCodeList = array();
        $iteraions = 0;
        for($key = 0; $key < count($this->knownClasses) && $iteraions < 1e3;$iteraions++){
            $className = $this->knownClasses[$key];
            $classReflection = new \ReflectionClass ($className);
            $extends = array();
            $interfaces = array_keys($classReflection->getInterfaces());

            
            $interfaces = array_filter($interfaces,function($_class){
                $_classRef = new ClassReflection($_class);
                return !$_classRef->isInternal() && !$_classRef->getExtensionName();
            });

            // print_r($this->knownClasses);

            $classReflectionForParent = new \ReflectionClass ($className);
            while ($parent = $classReflectionForParent->getParentClass()) {
                $extends[] = $parent->getName();
                $classReflectionForParent = $parent;
            }        
            $extends = array_filter($extends,function($_class){
                $_classRef = new ClassReflection($_class);
                return !$_classRef->isInternal() && !$_classRef->getExtensionName();
            });    
            if(
                ( empty($extends) && empty($interfaces)) ||
                ($this->everyInArray($extends,$classsAddedCodeList) && $this->everyInArray($interfaces,$classsAddedCodeList))
            ){
                $classsAddedCodeList[] = $className;
                // $class = new ClassReflection($class);
                // $code .= $this->cacheCodeGenerator->getCacheCode($class);
            }else{
                $this->knownClasses[] = $className;
            }
            unset($this->knownClasses[$key]);
            $this->knownClasses = array_values($this->knownClasses);
            // 
        }
        // print_r($code);
        // // print_r($classsAddedCodeList);
        // die();
        foreach ($classsAddedCodeList as $class) {
             $class = new ClassReflection($class);
             $code .= $this->cacheCodeGenerator->getCacheCode($class);
        }
       
        // var_dump($classCacheFilename);
        // die();
        file_put_contents($classCacheFilename, $code . PHP_EOL);
        file_put_contents($classCacheFilename . '.classes', json_encode($classsAddedCodeList));

        // minify the file
        file_put_contents($classCacheFilename, php_strip_whitespace($classCacheFilename) . PHP_EOL);



    }

    /**
     * Determine what classes are present in the cache
     *
     * @param $classCacheFilename
     * @return void
     */
    protected function reflectClassCache($classCacheFilename)
    {
        if(!file_exists($classCacheFilename . '.classes')){
            $this->knownClasses = array();
        }else{
            // die($classCacheFilename. '.classes');
            $this->knownClasses = array_unique(json_decode(file_get_contents($classCacheFilename. '.classes')));
             $this->knownClasses = array();
        }
        
        // $scanner = new FileScanner($classCacheFilename);
        // $this->knownClasses = array_unique($scanner->getClassNames());
    }
}