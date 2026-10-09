<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claim_invites', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->unique()->constrained('companies')->cascadeOnDelete();
            $table->foreignId('directory_id')->constrained('directories')->cascadeOnDelete();
            $table->string('token', 12)->unique();
            $table->string('phone', 30)->nullable();
            $table->text('message');
            // ready: hazır · sent: gönderildi · clicked: tıkladı · claimed: sahiplenme talebi verdi · opted_out: bir daha yazma
            $table->string('status', 20)->default('ready')->index();
            $table->unsignedInteger('clicks')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('first_clicked_at')->nullable();
            $table->timestamp('last_clicked_at')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->unsignedBigInteger('listing_request_id')->nullable();
            $table->timestamp('opted_out_at')->nullable();
            $table->timestamps();

            $table->index(['directory_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claim_invites');
    }
};
