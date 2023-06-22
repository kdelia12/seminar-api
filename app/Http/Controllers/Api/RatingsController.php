<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Seminar;
use App\Models\User;
use App\Models\Ratings;

class RatingsController extends Controller {
    Public function addratings (Request $request) {
        $user = auth()->guard('api')->user();
        $seminar_applied = $user->seminar_applied;
        $check = Ratings::where('id_user', $user->id)->where('id_seminar', $request->id_seminar)->first();
        if ($check) {
            return response()->json(['error' => 'User Sudah Memberi Rating'], 401);
        }
        //if user didnt apply fot that seminar user cant give comment
        if (!$seminar_applied->contains($request->id_seminar)) {
            return response()->json(['error' => 'User Belum Mengikuti Seminar'], 401);
        }
        $validatedData = $request->validate([
            'id_seminar' => ['required', 'integer'],
            'stars' => ['required', 'integer'],
            'review' => ['required', 'string'],
        ]);
        $ratings = Ratings::create([
            'id_user' => $user->id,
            'id_seminar' => $validatedData['id_seminar'],
            'stars' => $validatedData['stars'],
            'comment' => $validatedData['review'],
        ]);
        return response()->json($ratings, 201);
    }

    public function getuserstars(Request $request, $id_seminar){
        if (!auth()->guard('api')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = auth()->guard('api')->user();
        $userid = $user->id;
        $ratings = Ratings::where('id_user', $userid)->where('id_seminar', $id_seminar)->first();
        $stars = $ratings->stars;
        $comment = $ratings->comment;
        return response()->json(['stars' => $stars, 'comment' => $comment], 200);
    }

    public function getseminarstars(Request $request, $id_seminar)
    {
        $ratings = Ratings::where('id_seminar', $id_seminar)->get();
        $averageRating = $ratings->avg('stars');
        return response()->json(['average_rating' => $averageRating], 200);
    }
}

