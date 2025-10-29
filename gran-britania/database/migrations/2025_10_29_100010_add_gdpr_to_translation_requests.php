<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('translation_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('translation_requests', 'gdpr_given')) {
                $table->boolean('gdpr_given')->default(false)->after('comments');
            }
            if (! Schema::hasColumn('translation_requests', 'gdpr_at')) {
                $table->timestamp('gdpr_at')->nullable()->after('gdpr_given');
            }
        });
    }

    public function down(): void
    {
        Schema::table('translation_requests', function (Blueprint $table) {
            $table->dropColumn(['gdpr_given','gdpr_at']);
        });
    }
};
