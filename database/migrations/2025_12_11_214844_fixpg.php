<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Page;
use App\Models\Workspace;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         DB::transaction(function () {

          // 1. Get the workspace
          $workspace = Workspace::where('name', 'faqe 1')->first();
      
          if (! $workspace) {
              throw new \Exception('Workspace "faqe 1" not found.');
          }
      
          // 2. Update pages with NULL / empty / 0 workspace_id
        DB::table('pages')
    ->whereRaw('workspace_id IS NULL OR workspace_id = "" OR workspace_id = 0')
    ->update(['workspace_id' => $workspace->id]);

      
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
