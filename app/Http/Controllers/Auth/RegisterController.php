<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/customer/dashboard';

    public function redirectPath()
    {
        return route('customer.dashboard');
    }

    protected function registered(Request $request, $user)
    {
        $this->guard()->login($user);
        return redirect()->route('customer.dashboard');
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        // Check captcha
        if ($request->captcha != session('register_captcha_answer')) {
            return back()->withErrors(['captcha' => 'Captcha salah, coba lagi'])->withInput();
        }

        $user = $this->create($request->all());

        event(new Registered($user));

        $this->guard()->login($user);

        return redirect()->route('customer.dashboard');
    }

    public function showRegistrationForm()
    {
        $num1 = rand(1, 20);
        $num2 = rand(1, 20);

        session([
            'register_captcha_answer' => $num1 + $num2,
            'register_captcha_question' => "$num1 + $num2 = ?"
        ]);

        return view('auth.register');
    }

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:15', 'regex:/^[0-9+\-\s()]+$/'],
            'address' => ['required', 'string', 'max:500'],
            'captcha' => ['required'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'address' => $data['address'],
            'role' => 'customer',
            'is_active' => true,
        ]);
    }
}
