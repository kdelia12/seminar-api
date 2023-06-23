<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = auth()->guard('api')->user();
        return response()->json([
            'success' => true,
            'user'    => $user,  
        ], 200);
    }

    public function changePassword(Request $request){
        $user = auth()->guard('api')->user();
    
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password Lama Tidak Sesuai',
            ], 401);
        }
    
        if ($request->new_password != $request->new_password_confirmation) {
            return response()->json([
                'success' => false,
                'message' => 'Password Baru Tidak Sesuai',
            ], 401);
        }
    
        $validatedData = $request->validate([
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    
        $user->password = bcrypt($validatedData['new_password']);
        $user->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Password Berhasil Diubah',
        ], 200);
    }
    public function editprofile(Request $request)
    {
        $user = auth()->guard('api')->user();
    
        //if no_ktp null return error
        if ($request->no_KTP == null) {
            return response()->json([
                'success' => false,
                'message' => 'No KTP Tidak Boleh Kosong',
            ], 401);
        }
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['string', 'email', 'max:255'],
            'no_KTP' => ['required', 'string', 'max:255'],
        ]);
    
        $user->name = $validatedData['name'];
        $user->no_KTP = $validatedData['no_KTP'];
        $user->no_hp = $validatedData['no_hp'];
        $user->alamat = $request->input('alamat');
        $user->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Profile Berhasil Diubah',
        ], 200);
    }
    public function pembaruanberkas (Request $request){
        $user = auth()->guard('api')->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($request->no_KTP == null) {
            return response()->json([
                'success' => false,
                'message' => 'No KTP Tidak Boleh Kosong',
            ], 401);
        }
        $validatedData = $request->validate([
            'tempat_lahir' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['required', 'string', 'max:1000'],
        ]);
        $user->no_KTP = $request->input('no_KTP');
        $user->tempat_lahir = $validatedData['tempat_lahir'];
        $user->tanggal_lahir = $validatedData['tanggal_lahir'];
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Berkas Berhasil Diperbarui',
        ], 200);
    }
}
