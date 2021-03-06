<?php

namespace EdpSuperluminalTest\ClassDeclaration;

use EdpSuperluminal\ClassDeclaration\ClassUseNameService;
use EdpSuperluminal\ClassDeclaration\ExtendsStatementService;
use EdpSuperluminalTest\AbstractSuperluminalTest;
use Phake;
use Laminas\Code\Reflection\ClassReflection;
use Laminas\Code\Reflection\FileReflection;

class ExtendsStatementServiceTest extends AbstractSuperluminalTest
{
    /** @var ExtendsStatementService */
    protected $sut;

    /** @var ClassUseNameService */
    protected $classUseNameService;

    public function setUp()
    {
        parent::setUp();

        $this->classUseNameService = Phake::mock('EdpSuperluminal\ClassDeclaration\ClassUseNameService');

        $this->sut = new ExtendsStatementService($this->classUseNameService);

        Phake::when($this->mockClassReflection)->getNamespaceName()->thenReturn('Zend');
    }

    public function testDoesNotExtendAnything()
    {
        $this->assertEquals('', $this->sut->getClassExtendsStatement($this->mockClassReflection));
    }

    public function testExtendsAClass()
    {
        $parent = Phake::mock('Laminas\Code\Reflection\ClassReflection');

        Phake::when($this->mockClassReflection)->getParentClass()->thenReturn($parent);

        Phake::when($this->classUseNameService)->getClassUseName(Phake::anyParameters())->thenReturn('ServiceManager');

        $this->assertEquals(' extends ServiceManager', $this->sut->getClassExtendsStatement($this->mockClassReflection));
    }

    public function testHasNoNamespaceAndExtendsAClassWhichHasNotBeenUsed()
    {
        Phake::when($this->mockClassReflection)->getNamespaceName()->thenReturn(null);

        $this->mockParent();

        $this->assertEquals(' extends \Laminas\ServiceManager\ServiceManager', $this->sut->getClassExtendsStatement($this->mockClassReflection));
    }

    protected function mockParent()
    {
        $parent = Phake::mock('Laminas\Code\Reflection\ClassReflection');

        Phake::when($this->mockClassReflection)->getParentClass()->thenReturn($parent);

        Phake::when($this->classUseNameService)->getClassUseName()->thenReturn('Laminas\ServiceManager\ServiceManager');

        Phake::when($parent)->getName()->thenReturn('Laminas\ServiceManager\ServiceManager');

        Phake::when($parent)->getShortName()->thenReturn('ServiceManager');
    }
}