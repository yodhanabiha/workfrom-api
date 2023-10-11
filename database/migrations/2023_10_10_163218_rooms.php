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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->references('id')->on('users');
            $table->string('title');
            $table->string('alamat');
            $table->string('longitude');
            $table->string('latitude');
            $table->bigInteger('harga');
            $table->integer('diskon');
            $table->integer('capacity');
            $table->boolean('promo');
            $table->boolean('approved');
            $table->boolean('booked')->default(false);
            $table->decimal('rating', $precision = 8, $scale = 1);
            $table->timestamps();
        });

        Schema::create('room_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_room')->references('id')->on('rooms');
            $table->binary('image');
            $table->timestamps();
        });

        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_room')->references('id')->on('rooms');
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('room_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_room')->references('id')->on('rooms');
            $table->foreignId('id_user')->references('id')->on('users');
            $table->integer('rating');
            $table->timestamps();
        });

        Schema::create('room_facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_room')->references('id')->on('rooms');
            $table->string('internet')->nullable();
            $table->string('building_access')->nullable();
            $table->boolean('print_service')->nullable();
            $table->boolean('coffe')->nullable();
            $table->integer('chair')->nullable();
            $table->integer('table')->nullable();
            $table->string('location_status')->nullable();
            $table->timestamps();
        });

        Schema::create('trans_room', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_room')->references('id')->on('rooms');
            $table->foreignId('id_user')->references('id')->on('users');
            $table->bigInteger('total_payment');
            $table->date('start');
            $table->date('end');
            $table->timestamps();
        });

        Schema::create('trans_room_guest', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_room')->references('id')->on('rooms');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('number');
            $table->string('company_name')->nullable();
            $table->date('start');
            $table->date('end');
            $table->timestamps();
        });

        Schema::create('registration_room', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->references('id')->on('users');
            $table->foreignId('id_room')->references('id')->on('rooms');
            $table->binary('document');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE room_images MODIFY COLUMN image MEDIUMBLOB");
        DB::statement("ALTER TABLE registration_room MODIFY COLUMN document MEDIUMBLOB");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_room');   
        Schema::dropIfExists('trans_room');   
        Schema::dropIfExists('trans_room_guest');   
        Schema::dropIfExists('room_reviews');
        Schema::dropIfExists('room_images');
        Schema::dropIfExists('room_types');
        Schema::dropIfExists('room_facilities');
        Schema::dropIfExists('rooms');   
        Schema::dropIfExists('users');
    }
};
