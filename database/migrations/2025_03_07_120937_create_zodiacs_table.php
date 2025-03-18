<?php

use Illuminate\Database\Schema\Blueprint;
use LaravelSupports\Database\Migrations\CreateMigration;

return new class extends CreateMigration {

    protected string $table = "zodiacs";

    protected function defaultUpTemplate(Blueprint $table): void
    {
        $table->string('code')->primary()->comment('code');
        $table->string('description', 64)->nullable(false)->comment('설명');
    }
};
