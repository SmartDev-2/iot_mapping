<?php
namespace App\Http\Controllers;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
class DeviceController extends Controller {
 public function index(): JsonResponse { return response()->json(['success'=>true,'data'=>Device::orderBy('id')->get()]); }
}
