<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\api\v1\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('Auth:api', ['except'=>['login','register']]);
    }
    public function register(Request $request)
    {
//        $user = User::create([
//            'name' => (string) $request->input('name'),
//            'email' => (string) $request->input('email'),
//            'password' => bcrypt((string) $request->input('password')),
//        ]);
//        return response()->json([
//            'message' => 'Đăng ký thành công',
//            'user' =>$user
//        ], 201);

        $messages = array(
            'required' => 'Vui lòng điền đầy đủ thông tin.',
            'email' => 'Vui lòng điền email hợp lệ',
            'unique' => 'Email đã có người sử dụng'
        );

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|string|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ], $messages);
        if($validator->fails()){
            $data = $validator->errors()->toJson();
//            return response()->json($validator->errors()->toJson(),400, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            return response()->json($data, 400, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'Đăng ký thành công',
            'user' => $user
        ]);
    }
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        if(!$token = auth()->attempt($validator->validated())){
            return response()->json(['error' => 'Đăng nhập thất bại'], 401);
        };

        return $this->createNewToken($token);
    }

    public function logout(){
        auth()->logout();
        return response()->json(['message' => 'Đăng xuất thành công']);
    }

    public function profile(){
        return response()->json(auth()->user());
    }

    public function refresh(){
        return $this->createNewToken(auth()->refresh());
    }


    public function createNewToken($token){
        return response()->json([
           'token' => $token,
            'token_type' => 'bearer',
            'token_validity' => auth()->factory()->getTTL()*60,
            'user' => auth()->user()
        ]);
    }

}
