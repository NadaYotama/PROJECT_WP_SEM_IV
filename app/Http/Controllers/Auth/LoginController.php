<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function authenticated(Request $request, $user)
    {
        // Check if user is active
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Your account is not active.']);
        }

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'manager':
                return redirect()->route('manager.dashboard');
            case 'kasir':
                return redirect()->route('kasir.dashboard');
            case 'customer':
                return redirect()->route('customer.dashboard');
            default:
                return redirect()->route('customer.dashboard');
        }
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

        public function showLoginForm()
{
    $num1 = rand(1, 20);
    $num2 = rand(1, 20);

    session([
        'captcha_answer' => $num1 + $num2,
        'captcha_question' => "$num1 + $num2 = ?"
    ]);

    return view('auth.login');
}

protected function validateLogin(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'captcha' => 'required',
    ]);

    // check captcha
    if ($request->captcha != session('captcha_answer')) {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'captcha' => ['Captcha salah, coba lagi'],
        ]);
    }
}
}
