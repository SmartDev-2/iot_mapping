<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Device extends Model {
 protected $fillable=['device_id','nama_device'];
 public function locations(): HasMany { return $this->hasMany(Location::class,'device_id','device_id'); }
}
