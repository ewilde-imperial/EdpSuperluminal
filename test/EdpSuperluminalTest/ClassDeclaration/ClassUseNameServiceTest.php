<?php

namespace EdpSuperluminalTest\ClassDeclaration;

use EdpSuperluminal\ClassDeclaration\ClassUseNameService;
use EdpSuperluminal\ClassDeclaration\ClassUseNameServiceFactory;
use EdpSuperluminal\ClassDeclaration\FileReflectionUseStatementService;
use EdpSuperluminalTest\AbstractSuperluminalTest;
use Phake;
use Laminas\Code\Reflection\ClassReflection;
use Laminas\Code\Reflection\FileReflection;

class ClassUseNameServiceTest extends AbstractSuperluminalTest
{
    /** @var ClassUseNameService */
    protected $sut;

    public function setUp()
    {
        parent::setUp();

        $factory = new ClassUseNameServiceFactory();

        $this->sut = $factory->createService($this->serviceLocator);
    }

    public function testAClassWhichHasBeenUsed()
    {
        $useClass = Phake::mock('Laminas\Code\Reflection\ClassReflection');

        $useClassFqn = 'Laminas\ServiceManager\ServiceManager';

        Phake::when($this->useStatementService)->getUseNames(Phake::anyParameters())->thenReturn(array($useClassFqn => null));

        Phake::when($useClass)->getName()->thenReturn($useClassFqn);
        Phake::when($useClass)->getShortName()->thenReturn('ServiceManager');

        $this->assertEquals('ServiceManager', $this->sut->getClassUseName($this->mockClassReflection, $useClass));
    }

    public function testAClassWhichHasBeenUsedAsSomethingElse()
    {
        $useClass = Phake::mock('Laminas\Code\Reflection\ClassReflection');

        $useClassFqn = 'Laminas\ServiceManager\ServiceManager';

        Phake::when($this->useStatementService)->getUseNames(Phake::anyParameters())->thenReturn(array($useClassFqn => 'MyServiceManager'));

        Phake::when($useClass)->getName()->thenReturn($useClassFqn);
        Phake::when($useClass)->getShortName()->thenReturn('ServiceManager');

        $this->assertEquals('MyServiceManager', $this->sut->getClassUseName($this->mockClassReflection, $useClass));
    }

    public function testAClassWhichHasNotBeenUsedAndHasTheSameNamespace()
    {
        $useClass = Phake::mock('Laminas\Code\Reflection\ClassReflection');

        $useClassFqn = 'Laminas\ServiceManager\ServiceManager';

        Phake::when($this->mockClassReflection)->getNamespaceName()->thenReturn('Laminas\ServiceManager');
        Phake::when($useClass)->getName()->thenReturn($useClassFqn);
        Phake::when($useClass)->getShortName()->thenReturn('ServiceManager');

        $this->assertEquals('ServiceManager', $this->sut->getClassUseName($this->mockClassReflection, $useClass));
    }

    public function testAClassWhichHasNotBeenUsedAndHasADifferentNamespace()
    {
        $useClass = Phake::mock('Laminas\Code\Reflection\ClassReflection');

        $useClassFqn = 'Laminas\ServiceManager\ServiceManager';

        Phake::when($this->mockClassReflection)->getNamespaceName()->thenReturn('Laminas\Server');
        Phake::when($useClass)->getName()->thenReturn($useClassFqn);
        Phake::when($useClass)->getShortName()->thenReturn('ServiceManager');

        $this->assertEquals('\Laminas\ServiceManager\ServiceManager', $this->sut->getClassUseName($this->mockClassReflection, $useClass));
    }
}