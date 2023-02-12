<?php

namespace App\Http\Controllers\UserProfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    //

    public function retrieveAccInfo(Request $request, Response $response ){
        $input = $request->input('password');
        $password = Hash::make($input);
        $result = DB::select("SELECT * FROM users WHERE id = ? AND password = ?", [$request->input('users_id'), $password]);

        if ($result) {
            return response()->json(['password' => 'matched'], 200);
        } 
        else {
            return response()->json(['password' => $password], 404);
        }
        //the hashing in here doesn't produce the same output as the auth controller does.
    }

    public function updateAcc(Request $req, Response $res){
        $id = intval($req->input('users_id'));
        $updateProfile=DB::table('users')
        ->where('id', '=', $id)
        ->update([
            'email' => $req->input('email'),
            'password' => $req->input('password')
            // 'nohashpass' => $req->input('password')
        ]);

        if($updateProfile){
            $msg = array("message"=>"success", "account"=>"updated");
            $json = json_encode($msg);
            return $json;
        }

    }

    public function retrieveUserInfo(Request $request, Response $response ){
        $id = $request->input('users_id');
        $find = DB::table('userprofile')->where('users_id', '=' , $id)->first();

        if($find){
            $resultuser = DB::table('users')
            ->join('userprofile', 'users.id', '=', 'userprofile.users_id')
            ->select('users.*', 'userprofile.*')
            ->where('users.id', '=', $id)
            ->first();
            
            return response()->json($resultuser, 200);
        }else{
            $resultprofile = DB::table('users')->where('id', '=',$id)->get();
            return response()->json($resultprofile, 200);
        }

    }

    public function editProfile(Request $req, Response $res){
        
        $id = intval($req->input('users_id')); // to be change to user_id
        $firstName = $req->input('firstName');
        $lastName = $req->input('lastName');
        $bDay = $req->input('bDay');
        $gender = $req->input('gender');
        $lotNum = $req->input('lotNum');
        $street = $req->input('street');


        $data = $req->all();
        $updateProfile = DB::table('userprofile')->where('users_id', $id)->first();

        if($updateProfile){//if users exist
            DB::table('userprofile')->where('users_id', $id)->update($data);
        }else {//if no data exist in userprofile
            DB::table('userprofile')->insert($data);
        }

        return response()->json(["message" => "success", "profile" => "updated"], 200);
    }
}
