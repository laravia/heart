<?php

namespace Laravia\Heart\App\Classes;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Orchid\Platform\Models\User;
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

    protected function assertTraitExist(string $traitNameWithNamespace)
    {
        $this->assertTrue(trait_exists($traitNameWithNamespace));
    }

    protected function assertMethodInClassExists(string $methodName, string $classNameWithNamespace)
    {
        $reflectionClass = new ReflectionClass($classNameWithNamespace);
        $this->assertTrue($reflectionClass->hasMethod($methodName));
    }

    public function actAsAdmin(){
        return $this->createAndActAsUser("admin");
    }
    public function actAsUser(){
        return $this->createAndActAsUser("user");
    }

    public function createAndActAsUser($type = "admin")
    {

        $roles = [
            'admin'  => [
                'platform.index'                      => 1,
                'platform.systems'                    => 1,
                'platform.systems.index'              => 1,
                'platform.systems.roles'              => 1,
                'platform.systems.settings'           => 1,
                'platform.systems.users'              => 1,
                'platform.systems.comment'            => 1,
                'platform.systems.attachment'         => 1,
                'platform.systems.media'              => 1,
            ],
            'user'   => [
                'platform.index'                       => 1,
                'platform.systems'                     => 1,
                'platform.systems.roles'               => 0,
                'platform.systems.settings'            => 1,
                'platform.systems.users'               => 0,
                'platform.systems.menu'                => 0,
                'platform.systems.attachment'          => 1,
                'platform.systems.media'               => 1,
            ],
        ];

        $user = User::create([
            'name'           => $this->faker->firstName,
            'email'          => $this->faker->unique()->safeEmail,
            'password'       => Hash::make('password'),
            'remember_token' => $this->faker->word,
            'permissions'    => $roles[$type],
        ]);

        $this->actingAs($user);

        return $user;
   }
}
