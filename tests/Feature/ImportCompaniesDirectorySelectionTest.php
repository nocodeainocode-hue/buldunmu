<?php

namespace Tests\Feature;

use App\Filament\Resources\DiscoveredCompanies\Pages\ImportCompanies;
use App\Models\Directory;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ImportCompaniesDirectorySelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_select_all_and_clear_actions_fill_every_directory(): void
    {
        foreach (range(1, 5) as $i) {
            Directory::create(['name' => "Rehber {$i}", 'slug' => "rehber-{$i}", 'domain' => "rehber{$i}.example", 'status' => 'active']);
        }

        $this->actingAs(User::factory()->create(['is_admin' => true]));
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $component = Livewire::test(ImportCompanies::class)
            ->callAction(
                \Filament\Actions\Testing\TestAction::make('selectAllDirectories')->schemaComponent('directoryIds'),
            );

        $this->assertCount(5, $component->get('data.directoryIds'));

        $component->callAction(
            \Filament\Actions\Testing\TestAction::make('clearDirectories')->schemaComponent('directoryIds'),
        );

        $this->assertSame([], $component->get('data.directoryIds'));
    }
}
