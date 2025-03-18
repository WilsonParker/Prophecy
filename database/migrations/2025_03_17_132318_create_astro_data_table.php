<?php

use App\Models\Date;
use App\Models\Zodiac\Zodiac;
use Illuminate\Database\Schema\Blueprint;
use LaravelSupports\Database\Migrations\CreateMigration;

return new class extends CreateMigration {

    protected string $table = "astro";

    protected function defaultUpTemplate(Blueprint $table): void
    {
        $table->id();
        $table->foreignIdFor(Date::class)->nullable(false)->comment('date')->constrained()->onDelete('cascade');
        $this->foreignCodeFor($table, Zodiac::class);
        $table->unsignedTinyInteger('hour')->nullable(false)->comment('hour');
        $table->unsignedTinyInteger('minute')->nullable(false)->comment('minute');
        $table->string('description', 64)->nullable(false)->comment('description');
        $table->string('motion', 32)->nullable(false)->comment('motion');

        $table->unique(['date_id', 'hour', 'minute']);
    }

    protected function defaultDownTemplate(Blueprint $table): void
    {
        $table->dropForeignIdFor(Date::class);
        $table->dropForeign('astro_zodiac_code_foreign');
    }
};
