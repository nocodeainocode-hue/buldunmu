<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Directory;
use App\Support\FormGuard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class ContactFormGuardTest extends TestCase
{
    use RefreshDatabase;

    private Directory $directory;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        $this->directory = Directory::create(['name' => 'Koruma', 'slug' => 'koruma', 'domain' => 'koruma.test', 'status' => 'active']);
    }

    /** Belirli yaştaki geçerli belirteç üretir. */
    public static function token(int $answer, int $age = 30): string
    {
        return Crypt::encryptString(json_encode(['a' => $answer, 't' => time() - $age, 'i' => bin2hex(random_bytes(8))]));
    }

    private function send(array $data)
    {
        return $this->withServerVariables(['HTTP_HOST' => 'koruma.test'])
            ->from('http://koruma.test/iletisim')
            ->post('http://koruma.test/iletisim', $data + ['name' => 'Ali Veli', 'message' => 'Merhaba, bilgi almak istiyorum.']);
    }

    public function test_every_contact_page_renders_a_fresh_signed_question_and_the_honeypot(): void
    {
        $html = $this->withServerVariables(['HTTP_HOST' => 'koruma.test'])->get('http://koruma.test/iletisim')->assertOk()->getContent();

        $this->assertStringContainsString('name="fax_no"', $html);
        $this->assertStringContainsString('name="captcha_token"', $html);
        $this->assertMatchesRegularExpression('/\d+ [+\-×] \d+ = \?/u', $html);
    }

    public function test_a_valid_submission_is_stored_as_new(): void
    {
        $this->send(['captcha' => 9, 'captcha_token' => self::token(9)])->assertSessionHas('success');

        $this->assertSame('new', ContactMessage::withoutGlobalScopes()->firstOrFail()->status);
    }

    public function test_wrong_or_missing_answer_is_rejected_with_an_error(): void
    {
        $this->send(['captcha' => 10, 'captcha_token' => self::token(9)])->assertSessionHas('error');
        $this->send(['captcha' => 9])->assertSessionHas('error');
        $this->send(['captcha' => 9, 'captcha_token' => 'sahte'])->assertSessionHas('error');

        $this->assertSame(0, ContactMessage::withoutGlobalScopes()->count());
    }

    public function test_a_token_cannot_be_used_twice(): void
    {
        $token = self::token(9);

        $this->send(['captcha' => 9, 'captcha_token' => $token])->assertSessionHas('success');
        $this->send(['captcha' => 9, 'captcha_token' => $token, 'message' => 'Başka bir mesaj'])->assertSessionHas('error');

        $this->assertSame(1, ContactMessage::withoutGlobalScopes()->count());
    }

    public function test_a_form_submitted_too_fast_is_silently_dropped(): void
    {
        $this->send(['captcha' => 9, 'captcha_token' => self::token(9, 1)])->assertSessionHas('success');

        $this->assertSame(0, ContactMessage::withoutGlobalScopes()->count());
    }

    public function test_the_filled_honeypot_is_dropped(): void
    {
        $this->send(['captcha' => 9, 'captcha_token' => self::token(9), 'fax_no' => 'http://spam.test'])->assertSessionHas('success');

        $this->assertSame(0, ContactMessage::withoutGlobalScopes()->count());
    }

    public function test_the_same_message_from_the_same_address_is_stored_once(): void
    {
        $this->send(['captcha' => 9, 'captcha_token' => self::token(9)]);
        $this->send(['captcha' => 9, 'captcha_token' => self::token(9)])->assertSessionHas('success');

        $this->assertSame(1, ContactMessage::withoutGlobalScopes()->count());
    }

    public function test_link_heavy_messages_are_stored_as_spam_not_new(): void
    {
        $this->send([
            'captcha' => 9, 'captcha_token' => self::token(9),
            'message' => 'Bak: http://a.example http://b.example https://c.example',
        ])->assertSessionHas('success');

        $this->assertSame('spam', ContactMessage::withoutGlobalScopes()->firstOrFail()->status);
    }

    public function test_spam_detection_leaves_normal_messages_alone(): void
    {
        $this->assertFalse(FormGuard::looksLikeSpam('Ayşe Yılmaz', 'Firmamı eklemek istiyorum, telefonum 0532 123 45 67. www.firmam.com adresindeyiz.'));
        $this->assertTrue(FormGuard::looksLikeSpam('Seo Pro', 'Backlink satın alın'));
        $this->assertTrue(FormGuard::looksLikeSpam('http://spam.test', 'Merhaba'));
    }
}
