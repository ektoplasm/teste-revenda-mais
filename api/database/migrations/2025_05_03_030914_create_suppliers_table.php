<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();

            # CPF/CNPJ não pode ser repetido
            $table->string('cpf_cnpj', 14)->unique();
            $table->string('name');
            $table->string('email', 100);
            $table->string('address');
            $table->string('number', 5);
            $table->string('city', 100);
            $table->char('state', 2);
            $table->string('address_info')->nullable();
            $table->string('primary_contact');
            $table->string('primary_contact_email', 100);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
