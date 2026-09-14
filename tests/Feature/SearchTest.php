<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_blank_search_shows_paginated_companies_instead_of_a_collection_error(): void
    {
        $directory = Directory::create([
            'name' => 'Arama Rehberi',
            'slug' => 'arama-rehberi',
            'domain' => 'arama.test',
            'status' => 'active',
        ]);
        $category = Category::create(['name' => 'Avukat', 'slug' => 'avukat', 'status' => 'active']);
        $city = City::create(['name' => 'İstanbul', 'slug' => 'istanbul']);
        Company::create([
            'name' => 'Örnek Hukuk Bürosu',
            'directory_id' => $directory->id,
            'category_id' => $category->id,
            'city_id' => $city->id,
            'status' => 'active',
        ]);

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->get('http://arama.test/ara')
            ->assertOk()
            ->assertSee('1 firma bulundu')
            ->assertSee('Örnek Hukuk Bürosu');
    }
}
