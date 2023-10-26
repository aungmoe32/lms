<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLectureContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lecture_contents', function (Blueprint $table) {
            $table->id();
            $table->integer('lecture_id');
            $table->string('type');
            $table->text('contenttext')->nullable();
            $table->string('file_name')->nullable();  // real file name on server
            $table->string('file_title')->nullable();
            $table->string('file_extension')->nullable();
            $table->integer('instructor_id');
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
        Schema::dropIfExists('lecture_contents');
    }
}
