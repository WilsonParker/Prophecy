<?php

use Illuminate\Database\Schema\Blueprint;
use LaravelSupports\Database\Migrations\CreateMigration;

return new class extends CreateMigration {

    protected string $table = "dates";

    protected function defaultUpTemplate(Blueprint $table): void
    {
        $table->id();
        $table->unsignedSmallInteger('year')->nullable(false)->comment('year');
        $table->unsignedTinyInteger('month')->nullable(false)->comment('month');
        $table->unsignedTinyInteger('day')->nullable(false)->comment('day');

        $table->unique(['year', 'month', 'day']);
    }
};
