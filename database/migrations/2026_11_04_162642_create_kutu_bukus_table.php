<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  public function up(): void {
    Schema::table('users', fn(Blueprint $t)=>$t->string('avatar')->nullable());
    Schema::create('categories', function(Blueprint $t){ $t->id(); $t->string('name'); $t->string('slug')->unique(); $t->timestamps(); });
    Schema::create('products', function(Blueprint $t){
      $t->id(); $t->foreignId('category_id')->constrained()->cascadeOnDelete();
      $t->string('title'); $t->string('slug')->unique(); $t->string('author');
      $t->text('description'); $t->text('preview')->nullable();
      $t->unsignedInteger('price')->default(0); $t->unsignedInteger('discount_price')->nullable();
      $t->string('format')->default('PDF'); $t->string('file_path')->nullable();
      $t->unsignedInteger('sold')->default(0); $t->boolean('featured')->default(false); $t->timestamps();
    });
    Schema::create('vouchers', function(Blueprint $t){ $t->id(); $t->string('code')->unique(); $t->unsignedTinyInteger('percent'); $t->boolean('active')->default(true); $t->timestamps(); });
    Schema::create('orders', function(Blueprint $t){
      $t->id(); $t->foreignId('user_id')->constrained()->cascadeOnDelete(); $t->string('invoice')->unique();
      $t->unsignedInteger('subtotal'); $t->unsignedInteger('discount')->default(0); $t->unsignedInteger('total');
      $t->string('voucher_code')->nullable(); $t->string('status')->default('pending');
      $t->string('payment_method')->nullable(); $t->string('snap_token')->nullable(); $t->timestamp('paid_at')->nullable(); $t->timestamps();
    });
    Schema::create('order_items', function(Blueprint $t){ $t->id(); $t->foreignId('order_id')->constrained()->cascadeOnDelete(); $t->foreignId('product_id')->constrained()->cascadeOnDelete(); $t->unsignedInteger('price'); });
    Schema::create('reviews', function(Blueprint $t){
      $t->id(); $t->foreignId('user_id')->constrained()->cascadeOnDelete(); $t->foreignId('product_id')->constrained()->cascadeOnDelete();
      $t->unsignedTinyInteger('rating'); $t->text('body'); $t->timestamps(); $t->unique(['user_id','product_id']);
    });
    Schema::create('wishlists', function(Blueprint $t){ $t->foreignId('user_id')->constrained()->cascadeOnDelete(); $t->foreignId('product_id')->constrained()->cascadeOnDelete(); $t->primary(['user_id','product_id']); });
  }
  public function down(): void { foreach(['wishlists','reviews','order_items','orders','vouchers','products','categories'] as $t) Schema::dropIfExists($t); }
};
