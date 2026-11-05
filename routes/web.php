<?php
use App\Http\Controllers\{HomeController,ProductController,CartController,CheckoutController,LibraryController,ProfileController,AuthController};
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin as A;
use App\Http\Middleware\EnsureAdmin;
Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('/katalog', [ProductController::class,'index'])->name('catalog');
Route::get('/produk/{product:slug}', [ProductController::class,'show'])->name('product.show');
Route::get('/keranjang', [CartController::class,'index'])->name('cart');
Route::post('/keranjang/{product:slug}', [CartController::class,'add'])->name('cart.add');
Route::delete('/keranjang/{product:slug}', [CartController::class,'remove'])->name('cart.remove');
Route::post('/voucher', [CartController::class,'voucher'])->name('cart.voucher');
Route::post('/midtrans/notification', [CheckoutController::class,'notification']);

Route::middleware('guest')->group(function(){
  Route::view('/masuk','auth',['mode'=>'login'])->name('login');
  Route::view('/daftar','auth',['mode'=>'register'])->name('register');
  Route::post('/masuk',[AuthController::class,'login']);
  Route::post('/daftar',[AuthController::class,'register']);
});
Route::middleware('auth')->group(function(){
  Route::post('/keluar',[AuthController::class,'logout'])->name('logout');
  Route::post('/checkout',[CheckoutController::class,'store'])->name('checkout');
  Route::get('/transaksi',[CheckoutController::class,'index'])->name('orders.index');
  Route::get('/transaksi/{order}',[CheckoutController::class,'show'])->name('orders.show');
  Route::post('/transaksi/{order}/simulasi',[CheckoutController::class,'simulate'])->name('orders.simulate');
  Route::get('/perpustakaan',[LibraryController::class,'index'])->name('library');
  Route::get('/perpustakaan/{product:slug}/unduh',[LibraryController::class,'download'])->name('library.download');
  Route::post('/produk/{product:slug}/ulasan',[ProductController::class,'review'])->name('review');
  Route::post('/produk/{product:slug}/wishlist',[ProductController::class,'wish'])->name('wishlist');
  Route::get('/profil',[ProfileController::class,'edit'])->name('profile');
  Route::put('/profil',[ProfileController::class,'update'])->name('profile.update');
  Route::put('/profil/password',[ProfileController::class,'password'])->name('profile.password');
});


Route::prefix('admin')->name('admin.')->middleware(['auth', EnsureAdmin::class])->group(function () {
    Route::get('/', [A\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('produk', A\ProductController::class)->except('show')->parameters(['produk' => 'product'])->names('products');
    Route::resource('kategori', A\CategoryController::class)->only(['index', 'store', 'update', 'destroy'])->parameters(['kategori' => 'category'])->names('categories');
    Route::resource('voucher', A\VoucherController::class)->only(['index', 'store', 'update', 'destroy'])->names('vouchers');
    Route::get('pesanan', [A\OrderController::class, 'index'])->name('orders.index');
    Route::get('pesanan/{order}', [A\OrderController::class, 'show'])->name('orders.show');
    Route::put('pesanan/{order}', [A\OrderController::class, 'update'])->name('orders.update');
    Route::get('ulasan', [A\ReviewController::class, 'index'])->name('reviews.index');
    Route::delete('ulasan/{review}', [A\ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('pengguna', [A\UserController::class, 'index'])->name('users.index');
    Route::put('pengguna/{user}/admin', [A\UserController::class, 'toggleAdmin'])->name('users.admin');
});