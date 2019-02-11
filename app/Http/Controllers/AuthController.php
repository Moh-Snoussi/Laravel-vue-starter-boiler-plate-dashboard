<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Account;
use Socialite;
use App\Notifications\SignupActivate;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use GuzzleHttp\Client;
use Google_Client;
use Google_Service_People;

class AuthController extends Controller
{


    private $provider; // facebook, google or github

    /**
     * Redirect the user to the provider authentication page.
     *
     * @return \Illuminate\Http\Response
     */

    public function redirectToProvider($provider)
    {
        switch ($provider) {
            case 'google':
                return Socialite::driver('google')->scopes(['profile', 'email'])->redirect();

            case 'facebook':
                return Socialite::driver('facebook')->scopes(['profile', 'email'])->redirect();

            case 'github':
                return Socialite::driver('github')->scopes(['profile', 'email'])->redirect();

            default:
                break;
        }

    }

    /**
     * Obtain the user information from provider.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $providerUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect(env('PROVIDERS_CALL' . $provider));
        }

        $user = User::query()->firstOrNew(['email' => $providerUser->getEmail()]);



    // register (if no user)
        $ref = 1;
    // maybe we can set all the values on our user model if it is new... right now we only have name
    // but you could set other things like avatar or gender
        if (!$user->exists) {
            $user = new User([
                'name' => $providerUser->getName(),
                'email' => $providerUser->getEmail(),
           //

                'comming_from_before_registring' => serialize($ref),
                'device' => serialize($_SERVER['HTTP_USER_AGENT']),
                'languages' => serialize($_SERVER['HTTP_ACCEPT_LANGUAGE']),
                'activation_token' => str_random(60)
            ]);
            $user->save();
            $user->notify(new SignupActivate($user));
            return redirect()->route('login', ['email' => $user->email, 'confirmed' => false]);




        }
        /*    return response()->json([
            'success' => true ,'message' => "Successfully registred, we send you credit card information to $user->email . please confirm your email and then login with your new pin and creadit card."
        ], 201);
         */

        return redirect()->route('login', ['email' => $user->email, 'confirmed' => true]);

    }


    /**
     * Create user on signup success and send email
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            //'password' => 'required|string|confirmed'
        ]);  // validation
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            //'password' => bcrypt($request->password),
            'ip_adress_on_registration' => serialize($request->getClientIps()),
            'comming_from_before_registring' => serialize($request->header('HTTP_REFERER')),
            'device' => serialize($request->userAgent()),
            'languages' => serialize($request->getLanguages()),
            'activation_token' => str_random(60)
        ]); // save user in database
        $user->save();
        $user->notify(new SignupActivate($user)); // email is living on App\Notifications\SignupActivate.php
        return response()->json([
            'success' => true, 'message' => 'Successfully registred, we send you credit card information to your email. please confirm your email and then login with your new pin and creadit card.'
        ], 201); // response 
    }

    /**
     * Login user and create token we will not use on our project but it is usefull for another projects 
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);// login validation 
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json([
            'message' => 'Unauthorized'
        ], 401);
        $user = $request->user();
        if ($user->activation_token) {
            return response()->json([
                'success' => false,
                'message' => 'Waiting for confirmation'
            ], 351);
        }
        $tokenResult = $user->createToken(env('JWT_SECRET', 'Personal Access Token'));
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Login user with card number and create token the one we need in our project
     *
     * @param  [string] CARD_NUMBER
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */

    public function loginWithCard(Request $request)
    {



        $request->validate([
            'CARD_NUMBER' => 'required|string',
            'PIN' => 'required|string',
            'remember_me' => 'boolean'
        ]); // validation
        $user = User::where('CARD_NUMBER', $request->only(['CARD_NUMBER']))->first();// get the card code from request and check if the card code in database 

        // check authentication    
        if ($user) {
        // coar number where found
        // comparing the hashed pin with the provided pin

            if (password_verify(request(['PIN'][0]), $user->PIN)) {
            // user authenticated 



                $tokenResult = $user->createToken(env('JWT_SECRET', 'Personal Access Token')); // creating JWT
                $token = $tokenResult->token;
                $token->expires_at = Carbon::now()->addDays(1);
                if ($request->remember_me)
                    $token->expires_at = Carbon::now()->addWeeks(1); // adding one week if remeber me 
                $token->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully logged in',
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString()
                ], 200)->header('Authorization', $tokenResult->accessToken); // response success
            }
        }// authorization failed
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    /**
     * refresh token for api authorization 
     *
     */

    public function refresh(Request $request)
    {
        $http = new Client;
        $response = $http->post('http://your-app.com/oauth/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => 'the-refresh-token',
                'client_id' => 'client-id',
                'client_secret' => 'client-secret',
                'scope' => '',
            ],
        ]);
        return json_decode((string)$response->getBody(), true);
    }





    /**
     * Acount activated from email.  email is living on app/notification/SignupActive.php 
     * redirect with welcome message 
     */

    public function signupActivate($id, $token)
    {

        $user = User::find($id); // get the user id from the url and search in the database database 

        if ($user['activation_token'] == $token && $token) { // if activation_token field match the token (registering token on line 79)

            $user->active = true; // delete activation_token
            $user->activation_token = '';  // delete activation_token 
            $account = Account::where('user_id', $user['id'])->first(); // get the account assosiated with the user from the database
            $account->Active = true;
            $user->PIN = $account->PIN;
            $user->CARD_NUMBER = $account->CARD_NUMBER;
            $account->save();
            $user->save();

            return redirect('/login?user=' . $user->name . '&confirmed=1');
        } else if ($user) {
            return redirect('/login?user=' . $user->name . '&confirmed=1'); // we send back user name and confirmed
        }

        return redirect('login');

    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }




    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {

        $accessToken = $this->auth->user()->token(); // get token from response
        $refreshToken = $this->db
            ->table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]); // revoke token from data base
        $accessToken->revoke();
        $this->cookie->queue($this->cookie->forget(self::REFRESH_TOKEN)); // forget token from cookie 


        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ], 205);// response sucess
    }

}