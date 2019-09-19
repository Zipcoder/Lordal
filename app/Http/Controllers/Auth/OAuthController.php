<?php
namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use Socialite;
use App\Student;
use App\Http\Controllers\Controller;

class OAuthController extends Controller
{

    protected $redirectStudent = '/student/profile';
    protected $redirectStaff = '/staff/feed';
    protected $redirectError = '/';

    /**
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
    public function redirectToGithubProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
    public function redirectToGoogleProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that 
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderGithubCallback()
    {
        $user = Socialite::driver('github')->user();

        $auth_student = Student::with('user')->where('github_username', strtolower($user->nickname))->first();

        if( !$auth_student ) {
            toastr()->error('Students must be registered with their github username before being able to sign in. Please contact an admin and request access', 'Not Registered');
            return redirect($this->redirectError);
        }

        $auth_user = $auth_student->user;

        if( !$auth_user ) {
            toastr()->error('Students must be registered with their github username before being able to sign in. Please contact an admin and request access', 'Not Registered');
            return redirect($this->redirectError);
        }

        if(! $auth_student->github_id) {
            $auth_student->github_id = $user->getId();
            $auth_student->save();
        }

        Auth::login($auth_user);
        toastr()->success("Welcome {$auth_user->name}");

        return redirect($this->redirectStudent);
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that 
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderGoogleCallback()
    {
        $user = Socialite::driver('google')->user();

        if( !isset($user->user['hd']) ) {
            toastr()->error('To login as a staff member you must log in with a Zip Code Wilmington email address', 'Invalid Domain');

            return redirect($this->redirectError);
        }

        if( $user->user['hd'] != 'zipcodewilmington.com' ) {
            toastr()->error('To login as a staff member you must log in with a Zip Code Wilmington email address', 'Invalid Domain');
            return redirect($this->redirectError);
        }

        $auth_user = User::where('email', $user->email)->first();

        if( !$auth_user ) {
            $auth_user = User::create([
                'email' => $user->email,
                'name' => $user->name,
            ]);
            $auth_user->staff = true;
            $auth_user->save();
        }

        Auth::login($auth_user);
        toastr()->success("Welcome {$auth_user->name}");

        return redirect($this->redirectStaff);
    }

}