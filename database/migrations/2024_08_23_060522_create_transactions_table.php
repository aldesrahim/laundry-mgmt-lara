<?php

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Service::class)->constrained();
            $table->dateTime('date');
            $table->date('target_date')->nullable();
            $table->string('customer');
            $table->decimal('weight')->unsigned();
            $table->decimal('total');
            $table->boolean('queued')->default(0);
            $table->dateTime('queued_at')->nullable();
            $table->decimal('critical_ratio', 22, 8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
