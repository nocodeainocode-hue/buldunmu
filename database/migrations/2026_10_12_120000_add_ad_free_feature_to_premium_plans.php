<?php

use App\Support\PremiumPlanFeatures;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        PremiumPlanFeatures::addAdFree();
    }

    public function down(): void
    {
        // Paket maddeleri yönetim panelinden de düzenlenebildiği için geri alma yapılmaz.
    }
};
