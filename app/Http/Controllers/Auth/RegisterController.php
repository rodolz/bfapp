<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Role;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use UxWeb\SweetAlert\SweetAlert;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        // $roles = Role::pluck('display_name','id');
        // return view('auth.register', compact('roles'));
        return view('auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        if($data['email'] == ""){
            $data['email'] = str_replace(" ", "_", $data['nombre'])."@bfservices.com";
        }
        else if($data['password'] == ""){
            $data['password'] = "1234";
        }
        
        $user = User::create([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        $role = Role::findorFail($data['idRol']);
        $user->attachRole($role);
        return $user;
    }
}
