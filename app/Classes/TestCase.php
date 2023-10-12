<?php

namespace Laravia\Heart\App\Classes;

use Illuminate\Support\Facades\Artisan;
use ReflectionClass;
use Tests\TestCase as LaravelTestCase;

class TestCase extends LaravelTestCase
{
    public $faker;

    public $class;
    protected static $setUpRun = false;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
        if (!static::$setUpRun) {
            Artisan::call('migrate:fresh');
            Artisan::call('db:seed');
            static::$setUpRun = true;
        }
    }

    protected function assertClassExist(string $classNameWithNamespace)
    {
        $this->assertTrue(class_exists($classNameWithNamespace));
    }

    protected function assertMethodInClassExist(string $classNameWithNamespace, string $methodName)
    {
        $reflectionClass = new ReflectionClass($classNameWithNamespace);
        $this->assertTrue($reflectionClass->hasMethod($methodName));
    }
}
