<?php

use App\Models\Date;
use Illuminate\Database\Schema\Blueprint;
use LaravelSupports\Database\Migrations\CreateMigration;

return new class extends CreateMigration {

    protected string $table = "wiki_histories";

    /**
     * Run the migrations.
     */
    protected function defaultUpTemplate(Blueprint $table): void
    {
        $table->id();
        $table->foreignIdFor(Date::class)->nullable(false)->comment('date')->constrained()->onDelete('cascade');
        $table->string('event_uri', 256)->nullable(false)->comment('event uri');
        $table->string('description', 256)->nullable(false)->comment('description');
    }

    protected function defaultDownTemplate(Blueprint $table): void
    {
        $table->dropForeignIdFor(Date::class);
    }

};
