<?php

use App\Models\Date;
use App\Models\Zodiac\Zodiac;
use Illuminate\Database\Schema\Blueprint;
use LaravelSupports\Database\Migrations\CreateMigration;

return new class extends CreateMigration {

    protected string $table = "zodiacs_pivot_dates";

    protected function defaultUpTemplate(Blueprint $table): void
    {
        $table->id();
        $table->foreignIdFor(Date::class)->nullable(false)->unique()->comment('date')->constrained()->onDelete('cascade');
        $this->foreignCodeFor($table, Zodiac::class);
    }

    protected function defaultDownTemplate(Blueprint $table): void
    {
        $table->dropForeignIdFor(Date::class);
        $table->dropForeign('zodiacs_pivot_dates_zodiac_code_foreign');
    }
};