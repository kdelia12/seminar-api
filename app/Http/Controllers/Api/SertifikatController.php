<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Symfony\Component\String\Slugger\AsciiSlugger;
use App\Models\Seminar;
use App\Models\User;
use App\Models\Sertifikat;

class SertifikatController extends Controller {

    public function generatesertifikat(Seminar $seminar){
        $user = auth()->guard('api')->user();
        $seminar_applied = json_decode($user->seminar_applied, true)?? [];
        if (!in_array($seminar->id, $seminar_applied)) {
            return response()->json(['error' => 'User Belum Mengikuti Seminar'], 401);
        }
        if ($seminar->finalized != 'Y') {
            return response()->json(['error' => 'Seminar Belum Final'], 401);
        }
        //check if the user_id and seminar_id already exist in sertifikat table
        $check = Sertifikat::where('id_user', $user->id)->where('id_seminar', $seminar->id)->first();
        if ($check) {
            return response()->json(['error' => 'User Sudah Generate Sertifikat'], 401);
        }

        $kodesertifikat = substr(hash('sha256', $user->id.$seminar->id), 0, 8);
        $sertifikat = sertifikat::create([
            'id_user' => $user->id,
            'id_seminar' => $seminar->id,
            'kode_sertifikat' => $kodesertifikat,
        ]);
        return response()->json([
            'message' => 'Sertifikat data generated',
            'data' => $sertifikat,
        ], 201);
    }

    public function showsertifikatdatafromkode (Request $request){
        $user = auth()->guard('api')->user();
        $kodesertifikat = $request->kode_sertifikat;
        $sertifikat = Sertifikat::where('kode_sertifikat', $kodesertifikat)->first();
        if (!$sertifikat) {
            return response()->json(['error' => 'Kode Sertifikat Tidak Ditemukan'], 401);
        }
        $id = $sertifikat->id_user;
        $ids = $sertifikat->id_seminar;

        $user = User::where('id', $id)->first();
        $seminar = Seminar::where('id', $ids)->first();
        $nama = $user->name;
        $seminarname = $seminar->name;
        $seminardate = $seminar->date_and_time;
        $seminarspeaker = $seminar->speaker;

        return response()->json([
            'kode_sertifikat' => $kodesertifikat,
            'nama' => $nama,
            'seminarname' => $seminarname,
            'seminardate' => $seminardate,
            'id_seminar' => $seminar->id,
            'seminarspeaker' => $seminarspeaker,
            'message' => 'Sertifikat Valid',
        ], 200);
    }

    public function getsertifikatfromuser(){
        $user = auth()->guard('api')->user();
        $sertifikat = Sertifikat::where('id_user', $user->id)->get();
        if (!$sertifikat) {
            return response()->json(['error' => 'User Belum Generate Sertifikat'], 401);
        }
        $seminarData = [];
        foreach ($sertifikat as $sertifikats) {
            $id = $sertifikats->id_seminar;
            $seminar = Seminar::where('id', $id)->first();
        
            $seminarData[] = [
                'seminarname' => $seminar->name,
                'seminardate' => $seminar->date_and_time,
                'id_seminar' => $seminar->id,
                'seminarspeaker' => $seminar->speaker,
                'kode_sertifikat' => $sertifikats->kode_sertifikat,
            ];
        }
        
        return response()->json([
            'nama' => $user->name,
            'email' => $user->email,
            'seminardata' => $seminarData,
        ], 200);
    }
}