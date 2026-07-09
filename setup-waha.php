<?php
// =============================================
// WhatsApp Kampanya Sistemi - Dosya Olusturucu
// Bu scripti sunucuda calistir: php setup-waha.php
// =============================================

echo "WhatsApp Kampanya Sistemi kuruluyor...\n\n";

// ──────────── 1. MIGRATION ────────────
$ts = date('Y_m_d_His');
$migration = <<<'PHP'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('whatsapp_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('message_template')->nullable();
            $table->integer('delay_min')->default(30);
            $table->integer('delay_max')->default(90);
            $table->string('image_url')->nullable();
            $table->enum('status', ['draft','sending','paused','completed'])->default('draft');
            $table->timestamps();
        });
        Schema::create('whatsapp_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('whatsapp_campaigns')->cascadeOnDelete();
            $table->string('phone');
            $table->string('name')->nullable();
            $table->text('custom_message')->nullable();
            $table->enum('status', ['pending','sent','failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('whatsapp_recipients');
        Schema::dropIfExists('whatsapp_campaigns');
    }
};
PHP;
file_put_contents("database/migrations/{$ts}_create_whatsapp_tables.php", $migration);
echo "✅ Migration olusturuldu\n";

// ──────────── 2. MODELS ────────────
$campaignModel = <<<'PHP'
<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WhatsAppCampaign extends Model {
    protected $fillable = ['name','message_template','delay_min','delay_max','image_url','status'];
    public function recipients() { return $this->hasMany(WhatsAppRecipient::class, 'campaign_id'); }
    public function sentCount() { return $this->recipients()->where('status','sent')->count(); }
    public function pendingCount() { return $this->recipients()->where('status','pending')->count(); }
}
PHP;
file_put_contents("app/Models/WhatsAppCampaign.php", $campaignModel);

$recipientModel = <<<'PHP'
<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WhatsAppRecipient extends Model {
    protected $fillable = ['campaign_id','phone','name','custom_message','status','error_message','sent_at'];
    protected $casts = ['sent_at'=>'datetime'];
    public function campaign() { return $this->belongsTo(WhatsAppCampaign::class, 'campaign_id'); }
}
PHP;
file_put_contents("app/Models/WhatsAppRecipient.php", $recipientModel);
echo "✅ Modeller olusturuldu\n";

// ──────────── 3. KOMUT (SENDER) ────────────
@mkdir('app/Console/Commands', 0755, true);
$command = <<<'PHP'
<?php namespace App\Console\Commands;
use App\Models\WhatsAppCampaign;
use App\Models\WhatsAppRecipient;
use Illuminate\Console\Command;

class SendWhatsAppCampaign extends Command {
    protected $signature = 'waha:send {campaign_id}';
    protected $description = 'Send WhatsApp campaign messages';

    public function handle() {
        $campaign = WhatsAppCampaign::with('recipients')->findOrFail($this->argument('campaign_id'));
        
        if ($campaign->status === 'completed') { $this->info('Already completed.'); return; }
        
        $config = config('waha');
        $pending = $campaign->recipients()->where('status','pending')->get();
        
        if ($pending->isEmpty()) {
            $campaign->update(['status'=>'completed']);
            $this->info('All sent! Campaign completed.');
            return;
        }
        
        $campaign->update(['status'=>'sending']);
        
        foreach ($pending as $r) {
            $message = $r->custom_message ?: $campaign->message_template;
            $message = str_replace(['{name}','{phone}'],[$r->name??'',$r->phone],$message);
            $chatId = (str_starts_with($r->phone,'9')?$r->phone:'9'.$r->phone).'@c.us';
            
            try {
                $payload = ['session'=>$config['session'],'chatId'=>$chatId,'text'=>$message];
                
                // Varsa gorsel
                if ($campaign->image_url) {
                    $ch = curl_init($config['api_url'].'/api/sendImage');
                    $payload['caption'] = $message;
                    $payload['file'] = ['mimetype'=>'image/jpeg','url'=>$campaign->image_url,'filename'=>'image.jpg'];
                } else {
                    $ch = curl_init($config['api_url'].'/api/sendText');
                }
                
                curl_setopt_array($ch, [
                    CURLOPT_POST=>true, CURLOPT_RETURNTRANSFER=>true, CURLOPT_TIMEOUT=>30,
                    CURLOPT_HTTPHEADER=>['Content-Type: application/json','X-Api-Key: '.$config['api_key']],
                    CURLOPT_POSTFIELDS=>json_encode($payload),
                ]);
                
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                if ($httpCode >= 200 && $httpCode < 300) {
                    $r->update(['status'=>'sent','sent_at'=>now()]);
                    $this->info("✅ {$r->phone}");
                } else {
                    throw new \Exception("HTTP $httpCode: ".substr($response,0,100));
                }
            } catch (\Exception $e) {
                $r->update(['status'=>'failed','error_message'=>$e->getMessage()]);
                $this->error("❌ {$r->phone}: ".$e->getMessage());
            }
            
            sleep(rand($campaign->delay_min, $campaign->delay_max));
        }
        
        // Re-check
        if ($campaign->recipients()->where('status','pending')->count() === 0) {
            $campaign->update(['status'=>'completed']);
        } else {
            $campaign->update(['status'=>'paused']);
        }
        
        $this->info("Done. Sent: {$campaign->sentCount()}, Failed: ".$campaign->recipients()->where('status','failed')->count());
    }
}
PHP;
file_put_contents("app/Console/Commands/SendWhatsAppCampaign.php", $command);
echo "✅ Send komutu olusturuldu\n";

// ──────────── 4. CONFIG ────────────
$config = <<<'PHP'
<?php
return [
    'api_url' => env('WAHA_API_URL', 'http://localhost:3000'),
    'api_key' => env('WAHA_API_KEY', '3cbfbf4ac2e84591bfa8c4c0112443b9'),
    'session' => env('WAHA_SESSION', 'session_01kx14x1xzb2krx5bhz30vyxqb'),
];
PHP;
file_put_contents("config/waha.php", $config);
echo "✅ Waha config olusturuldu\n";

// ──────────── 5. FILAMENT RESOURCE ────────────
@mkdir('app/Filament/Resources/WhatsAppCampaignResource', 0755, true);
@mkdir('app/Filament/Resources/WhatsAppCampaignResource/Pages', 0755, true);

$resource = <<<'PHP'
<?php namespace App\Filament\Resources;
use App\Filament\Resources\WhatsAppCampaignResource\Pages\ListWhatsAppCampaigns;
use App\Filament\Resources\WhatsAppCampaignResource\Pages\CreateWhatsAppCampaign;
use App\Filament\Resources\WhatsAppCampaignResource\Pages\EditWhatsAppCampaign;
use App\Models\WhatsAppCampaign;
use Filament\Forms\Components\{TextInput,Textarea,FileUpload,Select,Grid,Section};
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\{Action,EditAction,DeleteAction};
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Forms;

class WhatsAppCampaignResource extends Resource {
    protected static ?string $model = WhatsAppCampaign::class;
    protected static ?string $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;
    protected static ?string $navigationLabel = 'WhatsApp Kampanyalar';
    protected static ?string $navigationGroup = 'Pazarlama';

    public static function form(Schema $schema): Schema {
        return $schema->components([
            Section::make('Kampanya')->schema([
                TextInput::make('name')->label('Ad')->required(),
                Textarea::make('message_template')->label('Mesaj')->required()->rows(4)
                    ->helperText('{name} ve {phone} kullanilabilir'),
                Grid::make(2)->schema([
                    TextInput::make('delay_min')->label('Min Bekleme (sn)')->numeric()->default(30),
                    TextInput::make('delay_max')->label('Max Bekleme (sn)')->numeric()->default(90),
                ]),
                TextInput::make('image_url')->label('Gorsel URL')->url()->placeholder('https://...'),
                Select::make('status')->label('Durum')->options(['draft'=>'Taslak','sending'=>'Gonderiliyor','paused'=>'Duraklatildi','completed'=>'Tamamlandi'])->default('draft'),
            ]),
            Section::make('Alicilar (CSV)')->schema([
                FileUpload::make('csv_file')->label('CSV Dosyasi')->disk('local')->directory('csv-uploads')
                    ->acceptedFileTypes(['text/csv','text/plain','application/csv'])
                    ->helperText('Format: telefon,isim,ozel_mesaj (telefon zorunlu)'),
            ])->visible(fn($record)=>!$record || $record->status==='draft'),
        ]);
    }

    public static function table(Table $table): Table {
        return $table->columns([
            TextColumn::make('name')->label('Ad')->searchable(),
            TextColumn::make('recipients_count')->label('Alici')->counts('recipients'),
            TextColumn::make('status')->label('Durum')->badge()->formatStateUsing(fn($s)=>match($s){'draft'=>'Taslak','sending'=>'Gonderiliyor','paused'=>'Durakladi','completed'=>'Bitti',default=>$s})->color(fn($s)=>match($s){'draft'=>'gray','sending'=>'warning','paused'=>'danger','completed'=>'success',default=>'gray'}),
            TextColumn::make('created_at')->label('Tarih')->dateTime('d.m.Y H:i'),
        ])
        ->defaultSort('created_at','desc')
        ->actions([
            Action::make('import_csv')->label('CSV Yukle')->icon(Heroicon::OutlinedDocumentArrowUp)->color('gray')
                ->form([Forms\Components\FileUpload::make('csv')->required()])
                ->action(function($record,$data){
                    $path = $data['csv']->store('csv-imports');
                    $lines = file(storage_path('app/'.$path), FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
                    $added=0;
                    foreach ($lines as $line) {
                        $cols = str_getcsv($line);
                        if (empty($cols[0])) continue;
                        $record->recipients()->create(['phone'=>trim($cols[0]),'name'=>trim($cols[1]??''),'custom_message'=>trim($cols[2]??''),'status'=>'pending']);
                        $added++;
                    }
                    Notification::make()->title("$added alici eklendi!")->success()->send();
                })->visible(fn($r)=>$r->status==='draft'),
            
            Action::make('start')->label('Gonder')->icon(Heroicon::OutlinedPlay)->color('success')
                ->requiresConfirmation()->modalHeading('Kampanyayi Baslat')->modalDescription('Mesajlar gonderilmeye baslanacak.')
                ->action(fn($r)=>dispatch(new \App\Jobs\SendWhatsAppCampaignJob($r->id)))
                ->visible(fn($r)=>in_array($r->status,['draft','paused'])),
            
            EditAction::make(),
            DeleteAction::make(),
        ]);
    }

    public static function getPages(): array {
        return ['index'=>ListWhatsAppCampaigns::route('/'),'create'=>CreateWhatsAppCampaign::route('/create'),'edit'=>EditWhatsAppCampaign::route('/{record}/edit')];
    }
}
PHP;
file_put_contents("app/Filament/Resources/WhatsAppCampaignResource.php", $resource);

// Pages (stubs)
foreach (['ListWhatsAppCampaigns','CreateWhatsAppCampaign','EditWhatsAppCampaign'] as $page) {
    $base = match($page){'ListWhatsAppCampaigns'=>'ListRecords','CreateWhatsAppCampaign'=>'CreateRecord','EditWhatsAppCampaign'=>'EditRecord'};
    $code = "<?php namespace App\Filament\Resources\WhatsAppCampaignResource\Pages;\nuse App\Filament\Resources\WhatsAppCampaignResource;\nuse Filament\Resources\Pages\\$base;\nclass $page extends $base { protected static string \$resource = WhatsAppCampaignResource::class; }\n";
    file_put_contents("app/Filament/Resources/WhatsAppCampaignResource/Pages/{$page}.php", $code);
}
echo "✅ Filament Resource olusturuldu\n";

// ──────────── 6. JOB ────────────
@mkdir('app/Jobs', 0755, true);
$job = <<<'PHP'
<?php namespace App\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class SendWhatsAppCampaignJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(public int $campaignId) {}
    public function handle(): void {
        Artisan::call('waha:send', ['campaign_id' => $this->campaignId]);
    }
}
PHP;
file_put_contents("app/Jobs/SendWhatsAppCampaignJob.php", $job);
echo "✅ Job olusturuldu\n";

// ──────────── 7. ENV DEGISKENI ────────────
$envFile = '.env';
if (file_exists($envFile)) {
    $env = file_get_contents($envFile);
    if (!str_contains($env, 'WAHA_API_URL')) {
        $env .= "\n# WhatsApp Waha API\nWAHA_API_URL=http://localhost:3000\nWAHA_API_KEY=[REDACTED:high_entropy_env]\nWAHA_SESSION=session_01kx14x1xzb2krx5bhz30vyxqb\n";
        file_put_contents($envFile, $env);
        echo "✅ .env guncellendi\n";
    }
}

echo "\n══════════════════════════════\n";
echo "  KURULUM TAMAMLANDI!\n";
echo "  Simdi calistir:\n";
echo "  php artisan migrate --force\n";
echo "  php artisan optimize:clear\n";
echo "══════════════════════════════\n";
