<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_message_is_visible_only_in_the_source_directory(): void
    {
        $source = Directory::create([
            'name' => 'Kaynak Rehber',
            'slug' => 'kaynak-rehber',
            'domain' => 'kaynak.test',
            'status' => 'active',
        ]);
        $other = Directory::create([
            'name' => 'Diğer Rehber',
            'slug' => 'diger-rehber',
            'domain' => 'diger.test',
            'status' => 'active',
        ]);

        $this->withSession(['captcha_result' => 7])
            ->withServerVariables(['HTTP_HOST' => $source->domain])
            ->from('http://kaynak.test/iletisim')
            ->post('http://kaynak.test/iletisim', [
                'name' => 'Test Kullanıcı',
                'email' => 'test@example.com',
                'message' => 'Test mesajı',
                'captcha' => 7,
            ])
            ->assertRedirect('http://kaynak.test/iletisim');

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'test@example.com',
            'directory_id' => $source->id,
        ]);

        app()->instance('currentDirectory', $other);
        $this->assertSame(0, ContactMessage::count());
    }
}
