<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Themes;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Hash;
use App\Mail\ModeratorRejected;
use App\Mail\ModeratorAccepted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    // Show Register/Create Form
    public function create(){
        return view('users.register');
    }


    //Create New User
    public function store(Request $request)
    {
        $messages = [
            'required' => 'Polje :attribute je obavezno.',
            'min' => 'Polje :attribute mora imati najmanje :min karaktera.',
            'max' => 'Polje :attribute ne sme imati više od :max karaktera.',
            'email' => 'Unesite ispravnu email adresu za :attribute.',
            'unique' => ':attribute već postoji u bazi podataka.',
            'confirmed' => 'Potvrda za :attribute se ne poklapa.',
            'in' => 'Izabrana vrednost za :attribute nije validna.',
            'image' => 'Polje :attribute mora biti slika.',
            'mimes' => 'Dozvoljeni formati slika za :attribute su: :values.',
            'date' => 'Polje :attribute mora biti validan datum.',
            'before' => 'Polje :attribute mora biti datum pre :date.',
        ];

        $customAttributes = [
            'name' => 'ime',
            'email' => 'email adresa',
            'password' => 'lozinka',
            'gender' => 'pol',
            'place_of_birth' => 'mesto rođenja',
            'country' => 'zemlja',
            'birth_date' => 'datum rođenja',
            'personal_number' => 'jmbg',
            'phone_number' => 'broj telefona',
            'picture' => 'slika',

        ];

        $formFields = $request->validate([
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', 'min:8', 'max:255'],
            'gender' => ['required', 'in:male,female,other'],
            'place_of_birth' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date', 'before:' . now()->subYears(16)->format('Y-m-d')],
            'personal_number' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'picture' => ['required', 'image', 'mimes:jpeg,png,jpg'],
        ], $messages, $customAttributes);

        $formFields['password'] = bcrypt($formFields['password']);
        $formFields['role'] = 'korisnik';

        $imageName = time() . '.' . $request->picture->extension();
        $request->picture->move(public_path('storage'), $imageName);
        $formFields['picture'] = $imageName;

        $user = User::create($formFields);

        // $user->sendEmailVerificationNotification();

        // auth()->login($user);

        return redirect('/login')->with('message', 'Korisnik je uspesno registrovan!');
    }




    //Send Verification Email
    public function sendEmailVerificationNotification(User $user)
    {
        $user->notify(new VerifyEmail);
    }


    //Log User Out
    public function logout(Request $request){
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message','Izlogovani ste');
    }


    //Show Login Form
    public function login(){
        return view('users.login');
    }


    //Delete User
    public function destroy(User $user){

        $user->delete();
        return back()->with('message', "Korisnik uspešno izbrisan!");
    }



     //Manage Users- uopravljanje korisnicima  za admina
     public function manage(){
        $adminId = auth()->user()->id;
        $users = User::where('id', '!=', $adminId)->get();
        return view('users.manage', [
            'users' => $users,
        ]);
    }


    //Manage Users- stranica sa zahtevima za moderatore i neodobrene teme
    public function requests(){
        $adminId = auth()->user()->id;
        $users = User::where('id', '!=', $adminId)->get();
        $themes = Themes::where('approved',  'false')->latest()->paginate(3);
        return view('users.requests', [
            'users' => $users,
            'themes' => $themes
        ]);
    }




    //Authenticate User
    public function authenticate(Request $request){
        $messages = [
            'email.required' => 'Polje email je obavezno.',
            'email.email' => 'Unesite ispravnu email adresu.',
            'password.required' => 'Polje lozinka je obavezno.'
        ];

        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ], $messages);

        if(auth()->attempt($formFields)){
            $request->session()->regenerate();

            return redirect('/')->with('message','Sada ste ulogovani!');
        }

        return back()->withErrors(['password'=> 'Pogresna sifra'])->onlyInput('email');
    }



    //Show Reset Password Form
    public function reset(User $user){
        return view('users.resetPassword', ['user' => $user]);
    }


    //Reset Password
    public function resetPassword(Request $request,User $user){
        $formFields = $request->validate([
            'password' => ['required','min:6','confirmed']
        ]);

        $user->update([
            'password' => Hash::make($formFields['password']),
        ]);

        return redirect('/');

    }


    //Show Resend Email Verification Form
    public function showResendVerificationForm(){
        return view('components.verification-notice');
    }


    //Resend Email Verification
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return back()->with('status', 'Vaš email je već verifikovan.');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Verifikacioni link je poslat na vašu email adresu.');
    }



    public function rejectModeratorRequest(User $user)
    {

        DB::table('users')->where('id', $user->id)->update(['request' => 'rejected']);

        // Send rejection email
        //Mail::to($user->email)->send(new ModeratorRejected($user));

        return redirect()->back()->with('message', 'Zahtev korisnika je odbijen.');
    }

    public function acceptModeratorRequest(User $user)
    {

        DB::table('users')->where('id', $user->id)->update(['request' => 'accepted', 'role' => 'moderator']);

        // Send acceptance email
       // Mail::to($user->email)->send(new ModeratorAccepted($user));

        return redirect()->back()->with('message', 'Korisnik je postao moderator.');
    }
}
