<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Order extends Model {
 protected $guarded=[];
 protected $casts=["paid_at"=>"datetime"];
 public function user(){return $this->belongsTo(User::class);}
 public function items(){return $this->hasMany(OrderItem::class);}
 public function markPaid(?string $method=null): void {
  if($this->status==="paid") return;
  $this->update(["status"=>"paid","paid_at"=>now(),"payment_method"=>$method ?? $this->payment_method]);
  foreach($this->items()->with("product")->get() as $i) $i->product?->increment("sold");
 }
}
