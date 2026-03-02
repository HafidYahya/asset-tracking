<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('asset_code')->nullable()->after('id');
        });

        $assetIds = DB::table('assets')->orderBy('id')->pluck('id');
        $counter = 1;
        foreach ($assetIds as $id) {
            DB::table('assets')
                ->where('id', $id)
                ->update([
                    'asset_code' => 'KA' . str_pad((string) $counter, 3, '0', STR_PAD_LEFT),
                ]);
            $counter++;
        }

        Schema::table('assets', function (Blueprint $table) {
            $table->unique('asset_code');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropUnique(['asset_code']);
            $table->dropColumn('asset_code');
        });
    }
};
