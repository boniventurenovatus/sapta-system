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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Employee relationship
            |--------------------------------------------------------------------------
            |
            | employee_id is created here as a normal nullable column.
            | The foreign key is added later, after employees table exists.
            |
            */
            $table->unsignedBigInteger('employee_id')
                ->nullable()
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Login information
            |--------------------------------------------------------------------------
            */

            $table->string('username', 100)
                ->nullable()
                ->unique();

            $table->string('email', 150)
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Password
            |--------------------------------------------------------------------------
            |
            | SAPTA uses password_hash instead of Laravel's default password
            | column.
            |
            */

            $table->string('password_hash');

            /*
            |--------------------------------------------------------------------------
            | Profile
            |--------------------------------------------------------------------------
            */

            $table->string('profile_image')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Account status
            |--------------------------------------------------------------------------
            */

            $table->string('account_status', 30)
                ->default('active');

            /*
            |--------------------------------------------------------------------------
            | First login / initial password
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_first_login')
                ->default(true);

            $table->timestamp('first_password_expires_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Login security
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('failed_login_attempts')
                ->default(0);

            $table->timestamp('locked_until')
                ->nullable();

            $table->timestamp('last_login_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Password tracking
            |--------------------------------------------------------------------------
            */

            $table->timestamp('password_changed_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Email verification
            |--------------------------------------------------------------------------
            */

            $table->timestamp('email_verified_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Remember login
            |--------------------------------------------------------------------------
            */

            $table->rememberToken();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};