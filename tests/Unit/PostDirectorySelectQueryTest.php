<?php

namespace Tests\Unit;

use App\Models\Post;
use Filament\Support\Services\RelationshipJoiner;
use Illuminate\Database\Eloquent\Relations\Relation;
use Tests\TestCase;

class PostDirectorySelectQueryTest extends TestCase
{
    public function test_post_directory_selector_does_not_select_json_columns(): void
    {
        $relationship = Relation::noConstraints(fn() => (new Post())->directories());
        $query = app(RelationshipJoiner::class)
            ->prepareQueryForNoConstraints($relationship)
            ->select(['directories.id', 'directories.name']);

        $sql = $query->toSql();

        $this->assertStringContainsString('distinct', $sql);
        $this->assertStringContainsString('directories"."id', $sql);
        $this->assertStringContainsString('directories"."name', $sql);
        $this->assertStringNotContainsString('directories".*', $sql);
    }
}
