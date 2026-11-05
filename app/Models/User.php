<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable {
  protected $fillable=['name','email','password','avatar'];
  protected $hidden=['password','remember_token'];
  protected function casts(): array { return ['password'=>'hashed','email_verified_at'=>'datetime']; }
  public function orders(){ return $this->hasMany(Order::class); }
  public function wishlist(){ return $this->belongsToMany(Product::class,'wishlists'); }
  public function library(){
    return Product::whereIn('id', OrderItem::whereHas('order', fn($q)=>$q->where('user_id',$this->id)->where('status','paid'))->select('product_id'));
  }
  public function owns(Product $p): bool { return $this->library()->where('id',$p->id)->exists(); }
}
