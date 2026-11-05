<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model {
 protected $guarded=[];
 public function category(){return $this->belongsTo(Category::class);}
 public function reviews(){return $this->hasMany(Review::class);}
 public function getFinalPriceAttribute(){return $this->discount_price ?? $this->price;}
 public function getOnSaleAttribute(){return $this->discount_price!==null && $this->discount_price<$this->price;}
}
