<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Location extends Model {
 protected $fillable=['device_id','latitude','longitude','timestamp'];
 protected $casts=['latitude'=>'decimal:7','longitude'=>'decimal:7','timestamp'=>'datetime'];
 public function device(): BelongsTo { return $this->belongsTo(Device::class,'device_id','device_id'); }
}
