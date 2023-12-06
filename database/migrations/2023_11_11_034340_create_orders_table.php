<?php

use App\Models\address;
use App\Models\pharmacy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\doctor;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            //ID,OrderedUserName,DeliveringAddress, CreationDate, DoctorName,IsInsured,Status,Actions
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(address::class)->constrained();
            $table->foreignIdFor(doctor::class)->constrained()->nullable();
            $table->boolean('is_insured');
            $table->string('status');
            $table->string('creator_type');
            $table->foreignIdFor(pharmacy::class)->constrained()->nullable();
            $table->integer('Total_price')->nullable();
            $table->text('session_id')->nullable();
            $table->text('payment_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
