<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('directories', function (Blueprint $table) {
            $table->string('blog_layout')->default('editorial')->after('group_other_cities');
            $table->text('editorial_voice')->nullable()->after('blog_layout');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('content_type')->default('guide')->after('slug');
            $table->string('primary_query')->nullable()->unique()->after('content_type');
            $table->string('search_intent')->nullable()->after('primary_query');
            $table->string('target_city_slug')->nullable()->after('search_intent');
            $table->string('target_category_slug')->nullable()->after('target_city_slug');
            $table->string('author_name')->nullable()->after('image');
            $table->string('reviewer_name')->nullable()->after('author_name');
            $table->json('sources')->nullable()->after('reviewer_name');
            $table->json('faq_items')->nullable()->after('sources');
            $table->json('pros')->nullable()->after('faq_items');
            $table->json('cons')->nullable()->after('pros');
            $table->boolean('is_indexable')->default(true)->after('cons');
            $table->string('canonical_url')->nullable()->after('is_indexable');
            $table->text('editorial_notes')->nullable()->after('canonical_url');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropUnique(['primary_query']);
            $table->dropColumn([
                'content_type', 'primary_query', 'search_intent', 'target_city_slug',
                'target_category_slug', 'author_name', 'reviewer_name', 'sources',
                'faq_items', 'pros', 'cons', 'is_indexable', 'canonical_url', 'editorial_notes',
            ]);
        });

        Schema::table('directories', function (Blueprint $table) {
            $table->dropColumn(['blog_layout', 'editorial_voice']);
        });
    }
};
