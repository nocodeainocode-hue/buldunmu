<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyOwner;
use App\Models\District;
use App\Models\ListingRequest;
use App\Models\OwnerCampaignEvent;
use App\Models\SiteSetting;
use App\Models\User;
use App\Rules\TurkishPhone;
use App\Services\Attribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OwnerPanelController extends Controller
{
    public function register()
    {
        return view('frontend.owner.register', $this->catalog());
    }

    public function storeRegistration(Request $request)
    {
        $directory = $this->directory();
        $validated = $this->validateCompany($request, $directory->id);
        $validated += $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = DB::transaction(function () use ($validated, $directory, $request): User {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            // Reklam/kaynak bilgisi (UTM, tıklama kimliği, yönlendiren site) ve kayıt olunan rehber.
            $user->forceFill(app(Attribution::class)->forUser($request) + ['signup_directory_id' => $directory->id])->save();

            $company = Company::create([
                'name' => $validated['company_name'],
                'directory_id' => $directory->id,
                'category_id' => $validated['category_id'],
                'city_id' => $validated['city_id'],
                'district_id' => $validated['district_id'] ?? null,
                'phone' => $validated['phone'],
                'whatsapp' => $validated['whatsapp'] ?? null,
                'email' => $validated['company_email'] ?? null,
                'website' => $validated['website'] ?? null,
                'address' => $validated['address'] ?? null,
                'short_description' => $validated['short_description'] ?? null,
                'status' => 'pending',
            ]);

            CompanyOwner::create([
                'company_id' => $company->id,
                'user_id' => $user->id,
                'directory_id' => $directory->id,
                'role' => 'owner',
                'status' => 'active',
            ]);

            $application = [
                'company_name' => $company->name,
                'contact_name' => $user->name,
                'phone' => $company->phone,
                'whatsapp' => $company->whatsapp,
                'email' => $company->email ?: $user->email,
                'website' => $company->website,
                'category_id' => $company->category_id,
                'requested_category' => $validated['requested_category'] ?? null,
                'city_id' => $company->city_id,
                'district_id' => $company->district_id,
                'directory_id' => $directory->id,
                'claim_company_id' => $company->id,
                'source' => 'owner_registration',
                'status' => 'new',
            ];

            // 1. adımda alınmış yarım başvuru varsa aynı kayıt tamamlanır (ikinci kayıt açılmaz).
            $lead = $this->findLead($request, $directory->id);

            if ($lead) {
                $lead->update($application + ['is_partial' => false, 'lead_token' => null]);
            } else {
                ListingRequest::create($application + app(Attribution::class)->forListing($request));
            }

            return $user;
        });

        $request->session()->forget('registration_lead_token');
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('owner.dashboard')->with('success', 'Firma profiliniz oluşturuldu. Yayına alınmadan önce kısa bir inceleme yapılacaktır.');
    }

    /**
     * Kayıt formunun 1. adımı geçildiğinde çağrılır: firma ve telefon bilgisi "yarım başvuru" olarak
     * saklanır; kişi 2. adımı doldurmasa bile geri dönülebilir. Aynı kişi geri gelirse kayıt güncellenir.
     */
    public function saveLead(Request $request)
    {
        $directory = $this->directory();
        $categoryIsOther = $request->input('category_id') === 'other';

        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'max:30', new TurkishPhone],
            'whatsapp' => ['nullable', 'string', 'max:30', new TurkishPhone],
            'category_id' => $categoryIsOther
                ? ['required', Rule::in(['other'])]
                : ['nullable', Rule::exists('categories', 'id')->where(fn ($query) => $query->whereNull('directory_id')->orWhere('directory_id', $directory->id))],
            'requested_category' => 'nullable|string|max:120',
            'city_id' => ['nullable', Rule::exists('cities', 'id')->where(fn ($query) => $query->whereNull('directory_id')->orWhere('directory_id', $directory->id))],
            'district_id' => ['nullable', 'integer', Rule::exists('districts', 'id')->where('city_id', $request->input('city_id'))],
        ]);

        // Sinyal uç noktası: hata durumunda istisna fırlatmadan 422 döner (form akışı bundan etkilenmez).
        if ($validator->fails()) {
            // Sinyal sessiz çalıştığı için başarısızlık izlenebilsin: yalnızca alan adları loglanır (kişisel veri yok).
            Log::info('Ön başvuru doğrulaması başarısız.', ['directory_id' => $directory->id, 'alanlar' => array_keys($validator->errors()->toArray())]);

            return response()->json(['message' => 'Geçersiz bilgi.'], 422);
        }

        $data = $validator->validated();
        $token = $request->session()->get('registration_lead_token') ?: Str::random(40);
        $request->session()->put('registration_lead_token', $token);

        $lead = $this->findLead($request, $directory->id, $data['phone']);

        $fields = [
            'company_name' => trim($data['company_name']),
            'phone' => trim($data['phone']),
            'whatsapp' => $data['whatsapp'] ?? null,
            'category_id' => $categoryIsOther ? null : ($data['category_id'] ?? null),
            'requested_category' => $data['requested_category'] ?? null,
            'city_id' => $data['city_id'] ?? null,
            'district_id' => $data['district_id'] ?? null,
        ];

        if ($lead) {
            $lead->update($fields + ['lead_token' => $token]);
        } else {
            ListingRequest::create($fields + [
                'directory_id' => $directory->id,
                'source' => 'owner_registration',
                'status' => 'new',
                'is_partial' => true,
                'lead_token' => $token,
            ] + app(Attribution::class)->forListing($request));
        }

        return response()->noContent();
    }

    /** Oturumdaki (ya da aynı telefonla son 24 saatteki) tamamlanmamış başvuruyu bulur. */
    private function findLead(Request $request, int $directoryId, ?string $phone = null): ?ListingRequest
    {
        $query = ListingRequest::withoutGlobalScope('directory')
            ->where('is_partial', true)
            ->where('directory_id', $directoryId);

        $token = $request->session()->get('registration_lead_token');

        if ($token && ($lead = (clone $query)->where('lead_token', $token)->first())) {
            return $lead;
        }

        return $phone
            ? (clone $query)->where('phone', trim($phone))->where('created_at', '>=', now()->subDay())->latest('id')->first()
            : null;
    }

    public function login()
    {
        return view('frontend.owner.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $throttleKey = Str::lower($credentials['email']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages(['email' => "Çok fazla deneme yaptınız. {$seconds} saniye sonra tekrar deneyin."]);
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 300);

            throw ValidationException::withMessages(['email' => 'E-posta veya şifre hatalı.']);
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();

        return redirect()->intended(route('owner.dashboard'));
    }

    public function dashboard()
    {
        $directory = $this->directory();
        $user = Auth::user();
        $companies = $user->ownedCompanies()
            ->wherePivot('directory_id', $directory->id)
            ->with(['category', 'city'])
            ->get();
        $campaignSettings = SiteSetting::getSettings();
        $showCampaignPopup = $this->shouldShowCampaignPopup($user);

        if ($showCampaignPopup) {
            if ($user->campaign_popup_seen_at === null) {
                $user->forceFill(['campaign_popup_seen_at' => now()])->save();
            }

            $this->recordCampaignEvent($user, $directory->id, 'popup_shown', 'popup');
        }

        return view('frontend.owner.dashboard', compact('directory', 'companies', 'showCampaignPopup', 'campaignSettings'));
    }

    public function campaigns(Request $request)
    {
        $user = Auth::user();
        $context = $this->campaignContext();

        $from = in_array($request->query('from'), OwnerCampaignEvent::SOURCES, true) ? $request->query('from') : 'direct';
        $this->recordCampaignEvent($user, $context['directory']->id, 'page_view', $from, dedupeMinutes: 10);

        return view('frontend.owner.campaigns', [
            'directory' => $context['directory'],
            'companies' => $context['companies'],
            'whatsapp' => $context['whatsapp'],
            'message' => $context['message'],
            'settings' => $context['settings'],
            'campaignTitle' => $context['title'],
            'whatsappUrl' => route('owner.campaigns.whatsapp'),
        ]);
    }

    /** WhatsApp tıklamasını sayar ve hazır mesajla wa.me'ye yönlendirir. */
    public function campaignWhatsapp()
    {
        $context = $this->campaignContext();

        if ($context['whatsapp'] === '') {
            return redirect()->route('owner.campaigns');
        }

        $this->recordCampaignEvent(Auth::user(), $context['directory']->id, 'whatsapp_click', 'direct');

        return redirect()->away('https://wa.me/'.$context['whatsapp'].'?text='.urlencode($context['message']));
    }

    /** Tarayıcıdan gelen hafif olay bildirimi (şimdilik yalnızca popup kapatma). */
    public function campaignEvent(Request $request)
    {
        // Ölçüm sinyali: yalnızca bilinen olay kabul edilir. İstisna fırlatmadan doğrudan 422 döner.
        if ($request->input('event') !== 'popup_dismiss') {
            return response()->json(['message' => 'Geçersiz olay.'], 422);
        }

        $this->recordCampaignEvent(Auth::user(), $this->directory()->id, 'popup_dismiss', 'popup', dedupeMinutes: 60);

        return response()->noContent();
    }

    /** Popup: ilk girişte; etkileşim olmadıysa 2 gün sonra bir kez daha (toplam en fazla 2). */
    private function shouldShowCampaignPopup(User $user): bool
    {
        $seenAt = $user->campaign_popup_seen_at;
        $total = max(OwnerCampaignEvent::where('user_id', $user->id)->where('event', 'popup_shown')->count(), $seenAt ? 1 : 0);

        if ($total === 0) {
            return true;
        }

        if ($total >= 2 || ! $seenAt || $seenAt->gt(now()->subDays(2))) {
            return false;
        }

        return ! OwnerCampaignEvent::where('user_id', $user->id)->whereIn('event', ['page_view', 'whatsapp_click'])->exists();
    }

    private function recordCampaignEvent(User $user, ?int $directoryId, string $event, ?string $source, int $dedupeMinutes = 0): void
    {
        if ($dedupeMinutes > 0 && OwnerCampaignEvent::where('user_id', $user->id)->where('event', $event)
            ->where('created_at', '>=', now()->subMinutes($dedupeMinutes))->exists()) {
            return;
        }

        OwnerCampaignEvent::create([
            'user_id' => $user->id,
            'directory_id' => $directoryId,
            'event' => $event,
            'source' => $source,
            'created_at' => now(),
        ]);
    }

    /** @return array{directory: \App\Models\Directory, companies: \Illuminate\Support\Collection, whatsapp: string, message: string, settings: SiteSetting, title: string} */
    private function campaignContext(): array
    {
        $directory = $this->directory();
        $companies = Auth::user()->ownedCompanies()
            ->wherePivot('directory_id', $directory->id)
            ->orderBy('name')
            ->get(['companies.id', 'companies.name']);
        $settings = SiteSetting::getSettings();
        $title = $settings->campaign_title ?: '100 Firma Rehberinde Yayın Projesi';
        $whatsapp = preg_replace('/\D+/', '', (string) ($settings->campaign_whatsapp ?: $settings->whatsapp));
        $message = sprintf(
            'Merhaba, %s rehberindeki %s firma profilim için %s hakkında bilgi almak istiyorum.',
            $directory->name,
            $companies->pluck('name')->join(', ') ?: 'firma profilim',
            $title
        );

        return compact('directory', 'companies', 'whatsapp', 'message', 'settings', 'title');
    }

    public function edit(Company $company)
    {
        $this->authorizeCompany($company);

        return view('frontend.owner.edit-company', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $this->authorizeCompany($company);

        $validated = $request->validate([
            'phone' => ['nullable', 'string', 'max:30', new TurkishPhone],
            'whatsapp' => ['nullable', 'string', 'max:30', new TurkishPhone],
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:1000',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:10000',
            'opening_hours' => 'nullable|string|max:2000',
        ]);

        $company->update($validated);

        return redirect()->route('owner.dashboard')->with('success', 'Firma bilgileriniz kaydedildi.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function catalog(): array
    {
        return [
            'categories' => Category::active()->orderBy('name')->get(),
            'cities' => City::orderBy('name')->get(),
            'districts' => District::orderBy('name')->get(['id', 'city_id', 'name']),
        ];
    }

    private function validateCompany(Request $request, int $directoryId): array
    {
        $categoryIsMissing = $request->input('category_id') === 'other';

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'max:30', new TurkishPhone],
            'whatsapp' => ['nullable', 'string', 'max:30', new TurkishPhone],
            'company_email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:1000',
            'short_description' => 'nullable|string|max:500',
            'category_id' => $categoryIsMissing
                ? ['required', Rule::in(['other'])]
                : ['required', Rule::exists('categories', 'id')->where(fn ($query) => $query->whereNull('directory_id')->orWhere('directory_id', $directoryId))],
            'requested_category' => [Rule::requiredIf($categoryIsMissing), 'nullable', 'string', 'max:120'],
            'city_id' => ['required', Rule::exists('cities', 'id')->where(fn ($query) => $query->whereNull('directory_id')->orWhere('directory_id', $directoryId))],
            'district_id' => ['nullable', Rule::exists('districts', 'id')->where(fn ($query) => $query->where('city_id', $request->input('city_id'))->where(fn ($directoryQuery) => $directoryQuery->whereNull('directory_id')->orWhere('directory_id', $directoryId)))],
        ]);

        if ($categoryIsMissing) {
            $validated['category_id'] = null;
        }

        return $validated;
    }

    private function directory()
    {
        abort_unless(app()->bound('currentDirectory'), 404);

        return app('currentDirectory');
    }

    private function authorizeCompany(Company $company): void
    {
        $directory = $this->directory();

        abort_unless(
            (int) $company->directory_id === (int) $directory->id
            && $company->owners()->where('user_id', Auth::id())->wherePivot('directory_id', $directory->id)->wherePivot('status', 'active')->exists(),
            403
        );
    }
}
