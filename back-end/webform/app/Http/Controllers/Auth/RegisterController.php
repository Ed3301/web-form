<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone_number' => ['required', 'min:11', 'max:14'],
            'zip_code' => ['size:5'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function store(Request $request)
    {
        $data = $request->all();
        $vaildator = self::validator($data);
        if($vaildator->fails()) {
            dd($vaildator->errors());
            return response([
                'msg' => 'Invalid data'
            ], 400);
        } else {
            if($data['photo']) {
                $photo = $data['photo'];
                $name = time().'.' . explode('/', explode(':', substr($photo, 0, strpos($photo, ';')))[1])[1];
                Image::make($photo)->save(public_path('images/').$name);
                $data['photo'] = $name;
            }
            if($data['file']) {
                $file = $data['file'];
                $file = str_replace('data:text/plain;base64,', '', $file);
                $file = str_replace(' ', '+', $file);
                $fileName = time().'.' . explode('/', explode(':', substr($file, 0, strpos($file, ';')))[0])[0] . '.txt';
                File::put(public_path('files'). '/' . $fileName, base64_decode($file));
                $data['file'] = $fileName;
            }
            User::create($data);
            return response([
                'msg' => 'User has been created successfully'
            ], 200);
        }

    }
}
