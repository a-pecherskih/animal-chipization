<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Animal;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->decimal('weight', 15, 10);
            $table->decimal('length', 15, 10);
            $table->decimal('height', 15, 10);
            $table->enum('gender', Animal::getGendersList());
            $table->enum('life_status', Animal::getStatusesList())->default(Animal::STATUS_ALIVE);
            $table->unsignedBigInteger('chipper_id');
            $table->unsignedBigInteger('chipping_location_id')->nullable();
            $table->timestamp('chipping_date_time');
            $table->timestamp('death_date_time')->nullable();

            $table->foreign('chipper_id')
                ->references('id')
                ->on('users')->onDelete('cascade');
            $table->foreign('chipping_location_id')
                ->references('id')
                ->on('locations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animals');
    }
};
