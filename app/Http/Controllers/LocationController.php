<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'device_id' => ['nullable', 'string', 'max:50'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        Location::create([
            'device_id' => $data['device_id'] ?? 'GPS001',
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location saved',
        ]);
    }

    public function latest(): JsonResponse
    {
        $location = Location::latest('id')->first();

        if (! $location) {
            return response()->json([
                'success' => false,
                'message' => 'Data GPS belum tersedia',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'timestamp' => $location->created_at?->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function index(): JsonResponse
    {
        return $this->latest();
    }
}
