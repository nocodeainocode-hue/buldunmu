<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetDirectoryCategoriesTest extends TestCase
{
    use RefreshDatabase;

    private const NAMES = ['Unisex Kuaför', 'Kadın Kuaför', 'Erkek Kuaför', 'Spa Merkezi', 'Epilasyon', 'Tırnak Salonu', 'Güzellik Merkezi'];

    public function test_only_the_given_categories_are_visible_in_order_and_companies_are_moved(): void
    {
        $kuafor = Directory::create(['name' => 'Kuaför Rehberi', 'slug' => 'kuaforrehberi', 'domain' => 'kuaforrehberi.com.tr', 'status' => 'active']);
        $other = Directory::create(['name' => 'Buldun mu', 'slug' => 'buldunmu', 'domain' => 'buldunmu.com.tr', 'status' => 'active']);
        $beauty = Category::create(['name' => 'Güzellik ve Kişisel Bakım', 'slug' => 'guzellik-ve-kisisel-bakim', 'status' => 'active']);
        $jewel = Category::create(['name' => 'Takı ve Aksesuarları', 'slug' => 'taki-ve-aksesuarlari', 'status' => 'active']);
        $city = City::create(['name' => 'İstanbul', 'slug' => 'istanbul']);
        $make = fn ($name, $cat, $dir) => Company::create(['name' => $name, 'category_id' => $cat->id, 'city_id' => $city->id, 'directory_id' => $dir->id, 'status' => 'active', 'phone' => '0532 412 55 66']);
        $barber = $make('Ahmet Berber', $beauty, $kuafor);
        $nails = $make('Pembe Nail Art', $beauty, $kuafor);
        $salon = $make('Selin Kuaför', $beauty, $kuafor);
        $bijou = $make('Karma Bijuteri', $jewel, $kuafor);
        $elsewhere = $make('Başka Berber', $beauty, $other);

        $this->artisan('directory:set-categories', ['domain' => 'kuaforrehberi.com.tr', 'names' => self::NAMES])->assertSuccessful();
        $this->assertSame(0, Category::withoutGlobalScope('directory')->where('slug', 'unisex-kuafor')->count(), 'kuru çalıştırma değişiklik yapmamalı');

        $this->artisan('directory:set-categories', ['domain' => 'kuaforrehberi.com.tr', 'names' => self::NAMES, '--apply' => true])->assertSuccessful();

        app()->instance('currentDirectory', $kuafor);
        $this->assertSame(self::NAMES, Category::active()->pluck('name')->all());

        app()->instance('currentDirectory', $other);
        $visibleElsewhere = Category::active()->pluck('name')->all();
        $this->assertContains('Güzellik ve Kişisel Bakım', $visibleElsewhere);
        $this->assertNotContains('Unisex Kuaför', $visibleElsewhere);

        $slug = fn (Company $c) => Category::withoutGlobalScope('directory')->find($c->fresh()->category_id)->slug;
        $this->assertSame('erkek-kuafor', $slug($barber));
        $this->assertSame('tirnak-salonu', $slug($nails));
        $this->assertSame('unisex-kuafor', $slug($salon));
        $this->assertSame('taki-ve-aksesuarlari', $slug($bijou));        // eşleşmeyen dokunulmaz
        $this->assertSame('guzellik-ve-kisisel-bakim', $slug($elsewhere)); // başka rehberin firması dokunulmaz

        // Tekrar çalıştırılabilir.
        $this->artisan('directory:set-categories', ['domain' => 'kuaforrehberi.com.tr', 'names' => self::NAMES, '--apply' => true])->assertSuccessful();
        app()->instance('currentDirectory', $kuafor);
        $this->assertSame(self::NAMES, Category::active()->pluck('name')->all());
    }
}
