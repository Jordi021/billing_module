<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create("clients", function (Blueprint $table) {
            $table->string("id", 13)->primary();
            $table->string("name", 50);
            $table->string("last_name",50);
            $table->date("birth_date");
            $table->enum("client_type", ["Cash", "Credit"]);
            $table->string("address");
            $table->string("phone",10);
            $table->string("email",250)->unique();
            $table->boolean("status")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists("clients");
    }
};
