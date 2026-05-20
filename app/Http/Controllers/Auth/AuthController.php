<?php
// app/Http/Controllers/Auth/AuthController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\{User, EscrowWallet};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};

class AuthController extends Controller
{
    public function showLogin()    { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }
    public function showOrganiserRegister() { return view('auth.register-organiser'); }

    public function login(Request $request)
    {
        $request->validate(['email'=>'required|email','password'=>'required']);
        if (!Auth::attempt($request->only('email','password'), $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }
        $user = Auth::user();
        if (!$user->is_active) { Auth::logout(); return back()->withErrors(['email'=>'Account is disabled.']); }
        return redirect()->intended($this->dashboardRoute($user->role));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
        ]);
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'role'     => 'client',
        ]);
        Auth::login($user);
        return redirect()->route('client.dashboard')->with('success', 'Welcome, '.$user->name.'!');
    }

    public function registerOrganiser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone'    => 'required|string|max:20',
        ]);
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'role'     => 'organiser',
        ]);
        EscrowWallet::create([
            'organiser_id'     => $user->id,
            'held_balance'     => 0,
            'available_balance'=> 0,
            'total_withdrawn'  => 0,
        ]);
        Auth::login($user);
        return redirect()->route('organiser.dashboard')->with('success', 'Organiser account created!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Logged out successfully.');
    }

    private function dashboardRoute(string $role): string
    {
        return match($role) {
            'admin'     => route('admin.dashboard'),
            'organiser' => route('organiser.dashboard'),
            default     => route('client.dashboard'),
        };
    }
}