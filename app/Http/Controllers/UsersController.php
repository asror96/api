<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function __construct()
    {

    }
    public function getItems(Request $request)
    {

        $user =null;
        $attributes=($this->model_path::paginate($request->per_page))->value();
        $filters = $request-> get("filter");
        $orders = $request-> get("order");
        $withs = $request-> get("withs");
        $per_page=$request-> get("per_page");
        $page=$request-> get("page");

        if($filters->isNotEmpty()){
            for ($i = 0; $i < count($filters); $i++) {
                if ($filters [$i] [1] == "=") {
                    $user = User::where($filters[$i][0],$filters[$i][2])->get();
                }
                else if($filters [$i] [1]=="<"||$filters [$i] [1]==">"||$filters [$i] [1]=="<="||$filters [$i] [1]==">="||$filters [$i] [1]=="!="){
                    $user = User::where($filters[$i][0],$filters[$i][1],$filters[$i][2])->get();
                }
                else{
                    return response()->json(["data" => ["items" => [],"message" => "Received!"]]);
                }
            }
        }
        else{
            return response()->json(["data" => null,"message" => "Received!"],422);
        }
        if ($orders->isNotEmpty()) {
            for ($i = 0; $i < count($orders);$i++){
                if ($orders[$i][1] == "asc"){
                    $user = $user -> sortBy($orders[$i][0]);
                }
                else{
                    $user = $user -> sortByDesc($orders[$i][0]);
                }
            }
        }

        if($withs->isNotEmpty()){
            foreach ($withs as $with_value){
                $attributes->map(function ($items,$key)use ($with_value){
                    if(!isset($items->$with_value)){
                        return collect($items)->merge($items->$with_value);
                    }
                });
            }
        }
        if($per_page!=null||$per_page!=null){
            $user = $user->chunk($per_page);
            return response()->json(["data" => ["items" => $user[$page-1] ?? []],"message" => "Received!"]);
        }
        else{
            return response()->json(["data" => ["items" => $user[0]],"message" => "Received!"]);
        }
    }
    public function refreshToken(Request $request)
    {
        $refresh_token = $request-> get("refresh_token");
        $user = User::where('refresh_token',$refresh_token) -> get();
        if(!($user->all() === [])){
            $access_token= mb_substr(Hash::make(rand(0,100000000)),40,40);
            User::where('refresh_token',$refresh_token) -> update(["access_token"=>$access_token]);
            return response() -> json([
                "data" => [
                    "access_token" => $access_token
                ],
                "message" => "Refreshed!"
            ]);
        }
        else{
            return response()->json([
                "data" => [null],
                "message" => "refresh_token is not find"
            ]);
        }
    }

    public function register(Request $request)
    {
        $this -> validate($request,[
            'name' => 'bail|required|string|between:1,255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string']);
        $user = $request-> only(["name","email"]);
        $user['password'] = Hash::make($request->password);
        $access_token = mb_substr(Hash::make(rand(0,100000000)),40,40);
        $refresh_token = mb_substr(Hash::make(rand(0,100000000)),40,40);
        $user['access_token']=$access_token;
        $user['refresh_token']=$refresh_token;

        $User=User::create($user);
        User::where('email',$request -> email) -> update(["access_token" => $access_token,"refresh_token" => $refresh_token]);

        return response()->json
        ([
            "data"=>$User,
            "message"=>"Registered!"
        ]);
    }

    public function login(Request $request)
    {

        $this -> validate($request,[
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email',$request -> email) -> first();

        if (!isset($user -> password)){
            return response()-> json([
                "data" => null,
                "message" => "Email checking error!"
            ],401);
        }
        else if(Hash::check($request -> password,$user -> password))
        {
            $access_token = mb_substr(Hash::make(rand(0,100000000)),40,40);
            $refresh_token = mb_substr(Hash::make(rand(0,100000000)),40,40);
            User::where('email',$request -> email) -> update(["access_token" => $access_token,"refresh_token" => $refresh_token]);
            return response() -> json([
                "data" => [
                    "access_token" => $access_token,
                    "refresh_token" => $refresh_token
                ],
                "message" => "Logged!"
            ]);
        }
        else{
            return response()-> json([
                "data" => null,
                "message" => "Password checking error!"
            ],401);
        }
    }

    public function getItem(Request $request,int $id)
    {
        if($id != null){
            $attribute = User::find($id);
            $withs = $request -> collect("withs");
            foreach ($withs as $with){
                if(isset($attribute -> $with))$attribute[$with] = $attribute -> $with;
            }
            return response() -> json(["data" => ["attributes" => $attribute],"message" => "Received!"]);
        }
        else{
            return response() -> json(["data" => null,"message" => "Task`s not found"],404);
        }
    }
    public function update(Request $request,$id)
    {
        $attribute = $request -> get("attributes");
        $this -> validate($request,[
            'name' => 'bail|required|string|between:1,255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string'
        ]);
        $user = User::find($id);

        if($attribute['name'] != null) $user -> name = $attribute['name'];
        if($attribute['email'] != null) $user -> email = $attribute['email'];
        if($attribute['password'] != null)$user -> password = $attribute['password'];

        $user->save();
        return response()-> json(["data" => $attribute,"massage" => "Update!"]);
    }
}
