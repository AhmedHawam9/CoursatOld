<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("description");
            $table->string("platform");
            $table->date("start_date",)->default(date("Y-m-d"));
            $table->date("end_date")->default(date("Y-m-d"));
            // 1 for academic & 0 for basic
            $table->enum("target",[1,0]);

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
        Schema::dropIfExists('campaigns');
    }
}
