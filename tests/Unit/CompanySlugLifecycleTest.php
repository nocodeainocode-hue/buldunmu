<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Services\CompanySlugService;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Events\Dispatcher;
use PHPUnit\Framework\TestCase;

class CompanySlugLifecycleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $container = new Container();
        Container::setInstance($container);
        $container->singleton(CompanySlugService::class);
        $dispatcher = new Dispatcher($container);
        $container->instance('events', $dispatcher);

        $capsule = new Capsule($container);
        $capsule->addConnection(['driver'=>'sqlite','database'=>':memory:','prefix'=>'']);
        $capsule->setEventDispatcher($dispatcher);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        Model::clearBootedModels();

        $schema = $capsule->schema();
        $schema->create('directories', function ($table) {
            $table->id(); $table->string('name'); $table->string('slug'); $table->string('domain');
            $table->string('slug_pattern'); $table->string('status')->default('active'); $table->timestamps();
        });
        $schema->create('cities', function ($table) {
            $table->id(); $table->foreignId('directory_id')->nullable(); $table->string('name'); $table->string('slug'); $table->timestamps();
        });
        $schema->create('categories', function ($table) {
            $table->id(); $table->foreignId('directory_id')->nullable(); $table->string('name'); $table->string('slug');
            $table->string('status')->default('active'); $table->timestamps();
        });
        $schema->create('companies', function ($table) {
            $table->id(); $table->foreignId('directory_id')->nullable(); $table->string('name'); $table->string('slug');
            $table->foreignId('category_id'); $table->foreignId('city_id'); $table->string('status')->default('active'); $table->timestamps();
            $table->unique(['directory_id','slug']);
        });
    }

    protected function tearDown(): void
    {
        Model::clearBootedModels();
        Container::setInstance(null);
        parent::tearDown();
    }

    public function test_slug_is_patterned_once_collision_safe_and_immutable_on_update(): void
    {
        $directory = Directory::create(['name'=>'Yerel Rehber','slug'=>'yerel','domain'=>'yerel.test','slug_pattern'=>'{name}-{city}']);
        $city = City::create(['directory_id'=>$directory->id,'name'=>'İstanbul','slug'=>'istanbul']);
        $category = Category::create(['directory_id'=>$directory->id,'name'=>'Market','slug'=>'market','status'=>'active']);

        $first = Company::create([
            'directory_id'=>$directory->id,'name'=>'Eyüp Market','slug'=>'eyup-market',
            'city_id'=>$city->id,'category_id'=>$category->id,'status'=>'active',
        ]);
        $second = Company::create([
            'directory_id'=>$directory->id,'name'=>'Eyüp Market','slug'=>'eyup-market',
            'city_id'=>$city->id,'category_id'=>$category->id,'status'=>'active',
        ]);

        $this->assertSame('eyup-market-istanbul', $first->slug);
        $this->assertSame('eyup-market-istanbul-2', $second->slug);

        $first->update(['name'=>'Eyüp Süper Market','slug'=>'degistirilmeye-calisti']);
        $this->assertSame('eyup-market-istanbul', $first->fresh()->slug);
    }
}
