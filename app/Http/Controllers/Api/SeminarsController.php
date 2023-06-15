<?php

namespace App\Http\Controllers\Api;

use App\Models\Seminar;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SeminarsController extends Controller
{
    public function upcoming_seminar()
    {
        $seminars = Seminar::where('date_and_time', '>', now())->get();

        return response()->json($seminars, 200);
    }

    public function past_seminar()
    {
        $seminars = Seminar::where('date_and_time', '<', now())->get();

        return response()->json($seminars, 200);
    }

    public function store(Request $request)
    {
        $user = auth()->guard('api')->user();
    
        if (!$user || $user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $validatedData = $request->validate([
            'name' => ['required', 'string'],
            'short_description' => ['required', 'string'],
            'full_description' => ['required', 'string'],
            'quota' => ['required', 'integer'],
            'date_and_time' => ['required', 'date'],
            'speaker' => ['required', 'string'],
        ]);
    
        $seminar = Seminar::create($validatedData);
    
        return response()->json($seminar, 201);
    }

    public function destroy(Seminar $seminar)
    {
        $user = auth()->guard('api')->user();
    
        if (!$user || $user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $seminar->delete();
        return response()->json(['message' => 'Seminar deleted'], 200);
    }

    public function apply(Seminar $seminar, Request $request)
{
    $user = auth()->guard('api')->user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    // Check if seminar is already past
    if ($seminar->date_and_time < now()) {
        return response()->json(['error' => 'Seminar is closed'], 400);
    }

    $quota = $seminar->quota;
    $participants = json_decode($seminar->participants, true) ?? [];
    
    // Check if the seminar is already full
    if (count($participants) >= $quota) {
        return response()->json(['error' => 'Seminar full'], 400);
    }
    
    // Check if the user is already a participant
    if (in_array($user->id, $participants)) {
        return response()->json(['error' => 'User already applied'], 400);
    }
    
    // Add the user as a participant
    $participants[] = $user->id;
    $seminar->update([
        'participants' => json_encode($participants),
        'participant_count' => count($participants)
    ]);
    
    return response()->json(['message' => 'Seminar Applied'], 200);
}
}