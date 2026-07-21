<?php

namespace Tests\Feature;

use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    protected Directory $directory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->directory = Directory::create([
            'name' => 'Test Rehberi',
            'slug' => 'test-rehberi',
            'domain' => 'test.local',
            'status' => 'active',
        ]);
        app()->instance('currentDirectory', $this->directory);
    }

    public function test_contact_form_has_subject_dropdown(): void
    {
        $response = $this->get('/iletisim');
        $response->assertStatus(200);
        $response->assertSee('Üyelik Paketleri');
        $response->assertSee('Reklam ve Sponsorluk');
    }
}
