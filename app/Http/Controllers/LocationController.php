<?php
namespace App\Http\Controllers;
use App\Models\Device;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class LocationController extends Controller {
 public function store(Request $request): JsonResponse {
  $data=$request->validate(['device_id'=>['required','string','max:50'],'latitude'=>['required','numeric','between:-90,90'],'longitude'=>['required','numeric','between:-180,180'],'timestamp'=>['nullable','date']]);
  Device::firstOrCreate(['device_id'=>$data['device_id']],['nama_device'=>$data['device_id']]);
  $location=Location::create($data+['timestamp'=>$data['timestamp']??now()]);
  return response()->json(['success'=>true,'message'=>'Data lokasi berhasil disimpan.','data'=>$location],201);
 }
 public function index(): JsonResponse { return response()->json(['success'=>true,'data'=>Location::orderByDesc('timestamp')->paginate(50)]); }
 public function latest(Device $device): JsonResponse {
  $location=Location::where('device_id',$device->device_id)->latest('timestamp')->first();
  if(!$location) return response()->json(['success'=>false,'message'=>'Belum ada data lokasi untuk perangkat ini.'],404);
  return response()->json(['success'=>true,'data'=>$location]);
 }
 public function history(Device $device): JsonResponse { return response()->json(['success'=>true,'data'=>Location::where('device_id',$device->device_id)->orderBy('timestamp')->get()]); }
}
