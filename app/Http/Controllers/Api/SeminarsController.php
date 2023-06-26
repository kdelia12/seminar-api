<?php

namespace App\Http\Controllers\Api;

use App\Models\Seminar;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SeminarsController extends Controller
{
    public function upcoming_seminar()
    {
        // $seminars = Seminar::where('date_and_time', '>', now())->get();
        //take all seminars without participants data with time gmt+7
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
        'category' => ['required', 'string'],
        'lokasi' => ['required', 'string'],
        // 'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
    ]);

    // // Generate a unique name for the image file
    // $imageName = str_replace(' ', '_', $validatedData['name']) . '-' . time() . '.' . $request->image->getClientOriginalExtension();

    // // Store the image in the "public/seminar" directory
    // $imagePath = Storage::disk('spaces')->put('seminar', $request->image);
    // $imgurl = Storage::disk('spaces')->url($imagePath);


    // Create a new seminar record with the validated data and image URL
    $seminar = Seminar::create([
        'name' => $validatedData['name'],
        'short_description' => $validatedData['short_description'],
        'full_description' => $validatedData['full_description'],
        'quota' => $validatedData['quota'],
        'date_and_time' => $request->input('date_and_time'),
        'speaker' => $request->input('speaker'),
        'category' => $validatedData['category'],
        'lokasi' => $validatedData['lokasi'],
        'alamat' => $request->input('alamat'),
    ]);

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

    public function apply(Seminar $seminar, Request $request){
    $user = auth()->guard('api')->user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    //check if user have no ktp number cant apply
    if (!$user->no_KTP) {
        return response()->json(['error' => 'Please fill your KTP number'], 400);
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
    $user = User::find($user->id);
    $seminar->update([
        'participants' => json_encode($participants),
        'participant_count' => count($participants)
    ]);
    $seminarlist = json_decode($user->seminar_applied, true) ?? [];
    $seminarlist[] = $seminar->id;
    $user->update([
        'seminar_applied' => json_encode($seminarlist)
    ]);
    
    return response()->json(['message' => 'Seminar Applied'], 200);
}

public function get_all_seminar_applied(){
    // Retrieve the user ID from the bearer token
    $user = auth()->guard('api')->user();

    // Retrieve the user with the given ID
    $user = User::find($user->id);

    // Check if the user exists
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Retrieve the applied seminar data from the user's record
    $seminar_applied = json_decode($user->seminar_applied, true);
    // $seminar_name = Seminar::find($seminar_applied)->name;
    $seminars = [];
    foreach ($seminar_applied as $seminarId) {
        $seminar = Seminar::find($seminarId);
        if ($seminar) {
            $seminars[] = [
                'seminar_id' => $seminarId,
                'seminar_name' => $seminar->name,
                'seminar_short_description' => $seminar->short_description,
                'seminar_date' => $seminar->date_and_time,
                'seminar_speaker' => $seminar->speaker,
                'seminar_category' => $seminar->category,
                'seminar_lokasi' => $seminar->lokasi,
                'seminar_alamat' => $seminar->alamat,

            ];
        }
    }

    // Check if the seminar data is empty
    if (!$seminar_applied) {
        return response()->json(['message' => 'No seminar data found'], 200);
    }

    // Return the seminar data as a JSON response
    return response()->json(['seminars' => $seminars], 200);
}


public function get_all_seminar_applicant(Seminar $seminar){
    // Retrieve the user ID from the bearer token
    $user = auth()->guard('api')->user();

    if (!$user || $user->role !== 'admin') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    
    $participants = json_decode($seminar->participants, true) ?? [];
    $applicants =[];
    foreach ($participants as $participantId) {
        $participant = User::find($participantId);
        if ($participant) {
            $applicants[] = [
                'participant_id' => $participantId,
                'participant_name' => $participant->name,
                'participant_phone' => $participant->no_hp,
            ];
        }
    }
    return response()->json(['applicants' => $applicants], 200);
}

public function check_apply(Seminar $seminar ){

    $user = auth()->guard('api')->user();
    $participants = json_decode($seminar->participants, true) ?? [];
    $user = auth()->guard('api')->user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    if (in_array($user->id, $participants)) {
        return response()->json(['message' => 'Anda Sudah Mendaftar'], 200);
    }

    return response()->json(['message' => 'Anda belum mendaftar'], 200);
}

public function getseminardata (Seminar $seminar){
    if (!$seminar) {
        return response()->json(['error' => 'Seminar not found'], 404);
    }
    $seminar = Seminar::find($seminar->id);
    $seminar = [
        'id' => $seminar->id,
        'name' => $seminar->name,
        'short_description' => $seminar->short_description,
        'full_description' => $seminar->full_description,
        'date_and_time' => $seminar->date_and_time,
        'quota' => $seminar->quota,
        'participant_count' => $seminar->participant_count,
        'speaker' => $seminar->speaker,
        'category' => $seminar->category,
        'lokasi' => $seminar->lokasi,
        'alamat' => $seminar->alamat,
    ];
    return response()->json(['seminar' => $seminar], 200);

}

public function editseminar(Request $request, Seminar $seminar){
    $user = auth()->guard('api')->user();
    if (!$user || $user->role !== 'admin') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    //only save those who request are filled
    if ($request->name) {
        $seminar->name = $request->name;
    }
    if ($request->short_description) {
        $seminar->short_description = $request->short_description;
    }
    if ($request->full_description) {
        $seminar->full_description = $request->full_description;
    }
    if ($request->quota) {
        $seminar->quota = $request->quota;
    }
    if ($request->date_and_time) {
        $seminar->date_and_time = $request->date_and_time;
    }
    if ($request->speaker) {
        $seminar->speaker = $request->speaker;
    }
    if ($request->category) {
        $seminar->category = $request->category;
    }
    if ($request->lokasi) {
        $seminar->lokasi = $request->lokasi;
    }
    if ($request->alamat) {
        $seminar->alamat = $request->alamat;
    }
    $seminar->save();
    return response()->json(['message' => 'Seminar Updated'], 200);
}

public function finalizeseminar (Seminar $seminar){
    $user = auth()->guard('api')->user();
    if (!$user || $user->role !== 'admin') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    if ($seminar->finalized == 'Y') {
        return response()->json(['error' => 'Seminar Sudah Di finalisasi'], 401);
    }
    if ($seminar->date_and_time > now()) {
        return response()->json(['error' => 'Seminar Belum Berakhir'], 401);
    }
    $seminar->finalized = 'Y';
    $seminar->save();
    return response()->json(['message' => 'Seminar Berhasil Di finalisasi'], 200);
}

public function cancelapply (Seminar $seminar){
    $user = auth()->guard('api')->user();
    $participants = json_decode($seminar->participants, true) ?? [];
    $user = auth()->guard('api')->user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    if (!in_array($user->id, $participants)) {
        return response()->json(['message' => 'Anda Belum Mendaftar'], 200);
    }
    $key = array_search($user->id, $participants);
    unset($participants[$key]);
    $participants = array_values($participants);
    $user = User::find($user->id);
    $seminar->update([
        'participants' => json_encode($participants),
        'participant_count' => count($participants)
    ]);
    $seminarlist = json_decode($user->seminar_applied, true) ?? [];
    $key = array_search($seminar->id, $seminarlist);
    unset($seminarlist[$key]);
    $seminarlist = array_values($seminarlist);
    $user->update([
        'seminar_applied' => json_encode($seminarlist)
    ]);
    return response()->json(['message' => 'Seminar Dibatalkan'], 200);

}
}