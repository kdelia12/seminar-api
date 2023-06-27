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
        if (!auth()->guard('api')->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
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
        ], 200);
    }

    public function getsertifikatfromuser(){
        $user = auth()->guard('api')->user();
        $seminarfinalizedappliedbyuser = Seminar::where('finalized', 'Y')->where('participants', 'like', '%'.$user->id.'%')->get();
        $seminarData = [];
        foreach ($seminarfinalizedappliedbyuser as $seminar) {
            $seminarData[] = [
                'id_seminar' => $seminar->id,
                'seminarname' => $seminar->name,
                'seminardate' => $seminar->date_and_time,
                'seminarspeaker' => $seminar->speaker,
                'finalized' => $seminar->finalized,
                'seminar_applied' => $seminar->seminar_applied,
            ];
        }
        
        return response()->json([
            'nama' => $user->name,
            'email' => $user->email,
            'seminardata' => $seminarData,
        ], 200);
    }

    public function isgenerated(){
        $user = auth()->guard('api')->user();
        $id_seminar = request('id_seminar');
        //nama seminar and tanggalseminar from table seminar
        $seminar = Seminar::where('id', $id_seminar)->first();
        $seminarname = $seminar->name;
        $seminardate = $seminar->date_and_time;
        $check = Sertifikat::where('id_user', $user->id)->where('id_seminar', $id_seminar)->first();
        $sertifikatdata = [
            'nama' => $user->name,
            'email' => $user->email,
            'seminarname' => $seminarname,
            'seminardate' => $seminardate,
            'kode_sertifikat' => $check->kode_sertifikat,
            'id_seminar' => $id_seminar,
        ];
        if (!$check) {
            return response()->json(['error' => 'User Belum Generate Sertifikat'], 401);
        }
        return response()->json([
            'message' => 'Sertifikat Sudah Digenerate',
            'data' => $sertifikatdata,
        ], 200);
    }
}