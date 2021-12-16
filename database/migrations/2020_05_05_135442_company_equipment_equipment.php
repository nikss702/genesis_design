<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CompanyEquipmentEquipment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_equipment_equipment', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_equipment_id');
            $table->foreign('company_equipment_id')->references('id')->on('company_equipment');
            $table->foreignId('equipment_id');
            $table->foreign('equipment_id')->references('id')->on('equipment');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_equipment_equipment');
    }
}
