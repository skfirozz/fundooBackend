<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('userid');
            $table->string('labelname')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('reminder')->nullable();
            $table->string('color')->nullable();
            $table->boolean('ispinned')->default(0);
            $table->boolean('isarchived')->default(0);
            $table->boolean('istrash')->default(0);
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
        Schema::dropIfExists('notes');
    }
}
