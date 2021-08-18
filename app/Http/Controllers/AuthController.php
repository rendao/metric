<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * login https://laravel.com/docs/5.8/api-authentication
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {   
        // $array = array('foo', 'bar');
        // return $array;
        $credentials = array(
            'name' => $request->name,
            'password' => $request->password
        );
        if(!Auth::attempt($credentials, $request->remember = true))
        {
            $result = array(
                'data' => $request->all(),
                'message' => 'Password is not match',
                'code' => -1
            );
            return $result;
        }

        $user = Auth::user();

        if (auth()->attempt($credentials)) {
            $token = $this->update($request);
            return response()->json($token, 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
        // $user['accessToken'] = $user->createToken(ENV('APP_NAME'))->accessToken;
    }

    /**
     * register.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $result = User::where("email", $request->get('email'))->get();

        if(!$result->isEmpty())
            return $result;
        $data = $request->all();
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        $token = $this->update($request);

        return response()->json($token, 200);
    }

    public function update(Request $request)
    {
        $token = Str::random(60);

        $request->user()->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        return ['token' => $token];
    }


}
