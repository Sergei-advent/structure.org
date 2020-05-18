<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFunctionalGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('functional_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_functional_group_id')->nullable();
            $table->string('name');
            $table->string('code_name')->nullable();
            $table->text('description')->nullable();
            $table->json('other_information')->nullable();

            $table->timestamps();

            $table->foreign('parent_functional_group_id')->references('id')->on('functional_groups')->onDelete('set null');
        });

        Schema::create('employees_functional_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('functional_group_id');

            $table->timestamps();

            $table->foreign('functional_group_id')->references('id')->on('functional_groups')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees_functional_groups');
        Schema::dropIfExists('functional_groups');
    }
}
