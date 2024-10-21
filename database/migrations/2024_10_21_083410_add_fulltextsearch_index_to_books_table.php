<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('CREATE INDEX books_fulltext_index ON books USING gin(to_tsvector(\'english\', title || \' \' || author || \' \' || isbn || \' \' || available))');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX books_fulltext_index');
    }
};
