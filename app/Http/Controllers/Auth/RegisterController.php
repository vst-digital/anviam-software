<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Mail;
use App\Mail\RegisterEmail;

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
    //protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = '/login';
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email','regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix', 'max:255', 'unique:users'],
            'phone_number' => ['required'],
            'company_name' => ['required'],
            'company_number' => ['required'],
            'street' => ['required'],
            'city' => ['required'],
            'state' => ['required'],
            'zip' => ['required'],
            'country' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = new User;
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->phone_number = $data['phone_number'];
        $user->email = $data['email'];
        $user->role = "2";
        $user->status = 0;
        $user->password = Hash::make($data['password']);
        $user->save();

        Mail::to($user->email)->send(new RegisterEmail($data['first_name'],$data['last_name'],$data['email'],$data['password']));

        if($user)
        {
            $userCompany = new UserCompany;
            $userCompany->user_id                = $user->id;
            $userCompany->company_name           = $data['company_name'];
            $userCompany->company_number         = $data['company_number'];
            $userCompany->street                 = $data['street'];
            $userCompany->city                   = $data['city'];
            $userCompany->state                  = $data['state'];
            $userCompany->zip                    = $data['zip'];
            $userCompany->country                = $data['country'];
            $userCompany->save();
            $user->company_id                    = $userCompany->id;
            $user->save();
        }
        return $user;
    }
	public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return redirect($this->redirectTo)->with('message', 'Registered successfully, We will notify you once admin will approve your account.');
    }
}
