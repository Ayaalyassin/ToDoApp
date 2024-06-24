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
        Schema::create('work_times', function (Blueprint $table) {
            $table->id();

            $table->bigInteger("task_id")->unsigned();
            $table->foreign("task_id")->references("id")
                ->on("tasks")->onDelete("cascade");

            $table->time("start");
            $table->time("pause");
            $table->time("end");
            $table->time("finish");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_times');
    }
};
