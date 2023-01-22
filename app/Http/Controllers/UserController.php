<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Task;
use Validator;

class UserController extends Controller
{
    public function register(Request $request)
    { 
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
            'c_password'=>'required|same:password'
        ]);

        if($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()],202);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $responseArray = [];
        $responseArray['token'] = $user->createToken('MyApp')->accessToken;
        $responseArray['name'] = $user->name;
        
        return response()->json($responseArray,200);  
    }
    public function login(Request $request)
    { 
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password]))
        {
            $user = Auth::user();
            $responseArray = [];
            $responseArray['token'] = $user->createToken('MyApp')->accessToken;
            $responseArray['name'] = $user->name;
            return response()->json($responseArray,200);
        }
        else
        {
            return response()->json(['error'=>'Unauthenticated'],203);
        }
    }
    
    public function getTaskList(){
        $data =  Task::all();
        $responseArray = [
            'status'=>'ok',
            'res'=>$data
        ]; 
        return response()->json(['results'=>$responseArray],200);
    }
    public function logout(Request $request)
     {
        if ($request->user()) { 
            $request->user()->tokens()->delete();
        }
        return response()->json(['message' => 'You are Logout'], 200);
     }
    
    
}
