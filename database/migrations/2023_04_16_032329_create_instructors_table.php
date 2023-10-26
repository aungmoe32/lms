<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstructorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->string('first_name');
            $table->string('last_name');
            // $table->string('instructor_slug');
            $table->string('contact_email');
            $table->string('telephone')->default('');
            $table->string('mobile')->default('');
            $table->string('paypal_id');

            $table->string('link_facebook')->default('');
            $table->string('link_linkedin')->default('');
            $table->string('link_twitter')->default('');
            $table->string('link_googleplus')->default('');

            $table->mediumText('biography')->default('');
            $table->string('instructor_image')->default('default-image.jpg');
            // $table->decimal('total_credits', 10, 2)->default(0.00);
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
        Schema::dropIfExists('instructors');
    }
}
