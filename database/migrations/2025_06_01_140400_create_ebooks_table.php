<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('author');
            $table->date('release_date');
            $table->string('cover_path')->required();
            $table->string('pdf_path')->required();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};
