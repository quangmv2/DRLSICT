<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use Auth;
use Socialite;
use App\User;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(Request $request, $provider)
    {
        $users = Socialite::driver($provider)->stateless()->user();
        $users = (object) $users->user;

        // var_dump($users);
        // return;
        if (!isset($users->hd) || $users->hd != 'sict.udn.vn') return \redirect()->route("logout");
        if ($users->email == 'natuan@sict.udn.vn') $users->email = 'mvquang.18it5@sict.udn.vn';
        $acc = User::where("email", $users->email)->get();
        // $k = 0;
        // if (count($acc) < 1) {
        //     $k = createAccountLogin($users);
        //     return;
        // }
        // if (k==0) return \redirect()->route('logout');
        // $acc = User::where("email", 'nthnhung.19it1@sict.udn.vn')->get();
        if (count($acc) < 1) return \redirect()->route("logout");
        $acc = $acc[0];
        Auth::login($acc, false);
        $user = new User;
        $user->email = $users->email;
        $user->name  = $users->name;
        $user->picture = $users->picture;
        $user->hd = $users->hd;
        $user->id_student = $acc->profile->student->id_student;
        $user->id_class = $acc->profile->student->id_class;
        $user->position = $acc->profile->student->id_position;

        $request->session()->put('account', $user);
        if (!empty($request->session()->get('nextRequest'))){
            return redirect($request->session()->get('nextRequest'));
        }
        return redirect($this->redirectTo);
    }

    function logout(Request $request)
    {
        $request->session()->forget('account');
        $request->session()->forget('nextRequest');
        Auth::logout();
        return  \redirect()->guest('login');
    }

   
}
