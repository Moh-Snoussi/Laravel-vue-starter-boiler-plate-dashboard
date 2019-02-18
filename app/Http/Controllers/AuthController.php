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

/**
 * A class responsible for authentication
 * Methods:
 * Social registration:
 *    redirectToProvider
 *    handelProviderCallback
 * Registration:
 *    signup: validate users and save them the database.
 * User log in: 
 *    login: not in use 
 *    loginWithCard
 * refresh JWT token:
 *    refresh:
 * email confirmation clicked:
 *    signupActivate
 * get user information
 *    user
 * log out
 *    logout 
 * saves in users in the database 
 * this class is called by 
 * file: route/api.php     route/web.php
 *
 * @author Mohamed Snoussi
 *
 */
class AuthController extends Controller
{
    private $provider; // facebook, google or github
    /**
     * Redirect the user to the provider authentication page the 
     * client id, client secret, need to be specified in .env file on the root folder.
     * call back link need to be obtained with all the above information from provider platform.
     * the method is called from route/web.php 
     * @param  [provider] google, facebook, github, twitter
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
     * this function 
     * @param  [provider] google, facebook, github, twitter
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
    // maybe we can set all the values on our user model if it is new... right now we only have name
    // but you could set other things like avatar or gender
        if (!$user->exists) {
            $user = new User([
                'provider' => $provider,
                'name' => $providerUser->getName(),
                'email' => $providerUser->getEmail(),
                'avatar' => $providerUser->getAvatar(),
                'familyName' => $providerUser['name']['familyName'],
                'givenName' => $providerUser['name']['givenName'],
                'providerId' => $providerUser->getId(),
                'comingFromBeforeRegistering' => serialize($_SERVER['HTTP_REFERER']),
                'ipAddressOnRegistration' => serialize($_SERVER['REMOTE_ADDR']),
                'deviceOnRegistration' => serialize($_SERVER['HTTP_USER_AGENT']),
                'languages' => serialize($_SERVER['HTTP_ACCEPT_LANGUAGE']),
                'activationToken' => str_random(60)
            ]);
            $user->save();
            $user->notify(new SignupActivate($user));
            return redirect()->route('login', ['email' => $user->email, 'confirmed' => false]);
        } else {

            $user->comingFromBeforeLastLogin = serialize($_SERVER['HTTP_REFERER']);
            $user->deviceOnLastLogin = serialize($_SERVER['HTTP_USER_AGENT']);
            $user->ipAddressOnLastLogin = serialize($_SERVER['REMOTE_ADDR']);
            $user->lastLoginDate = date('Y-m-d G:i:s');
            $user->save();
        }

        return redirect()->route('login', ['user' => $user->name, 'confirmed' => true]);
    }
    /**
     * Create user on signup success and send email
     * We want to register the user and save as much information as possible ip, device, languages, elc...
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return //json response // database save 
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
            'comingFromBeforeRegistering' => serialize($request->header('HTTP_REFERER')),
            'ipAddressOnRegistration' => serialize($request->getClientIps()),
            'deviceOnRegistration' => serialize($request->userAgent()),
            'languages' => serialize($request->getLanguages()),
            'activationToken' => str_random(60)
        ]); // save user in database
        $user->save();
        $user->notify(new SignupActivate($user)); // email is living on App\Notifications\SignupActivate.php
        return response()->json([
            'success' => true, 'message' => 'Successfully registered, we send you credit card information to your email. please confirm your email and then login with your new pin and credit card.'
        ], 201); // response 
    }
    /**
     * Account activated from email.  email is living on app/notification/SignupActive.php 
     * redirect with welcome message 
     */
    /**
     * Login user and create token we will not use on our project but it is useful for another projects 
     *
     * @param  [string] $id // user id
     * @param  [string] $token // email token
     * @return void database save and redirect to login rout (in route/api.php)
     */

    public function signupActivate($id, $token)
    {
        $user = User::find($id); // get the user id from the url and search in the database database 

        if ($user['activationToken'] == $token && $token) { // if activation_token field match the token (registering token on line 79)
            // activating the user and deleting the activation token 
            $user->active = true; // delete activation token
            // users already confirmed will not get here because we will lear their activation token
            $user->activationToken = '';
            $user->emailVerifiedAt = date('Y-m-d G:i:s');;  // clearing of activation token 
            $account = Account::where('user_id', $user['id'])->first(); // get the account associated with the user from the database
            $account->Active = true;// user is active 

            $user->pin = $account->pin;
            $user->cardNumber = $account->cardNumber;
            $user->save();
            // if the user clicked the confirmation link for first time we give him 300 euro 
            $account->amount = 300;
            $account->save();
            return redirect('/login?user=' . $user->name . '&confirmed=1');
        } else if ($user) {
            return redirect('/login?user=' . $user->name . '&confirmed=0'); // we send back user name and confirmed
        }

        return redirect('login');
    }
    /**
     * Login user and create token we will not use on our project but it is useful for another projects 
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
            'rememberMe' => 'boolean'
        ]);// login validation 
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json([
            'message' => 'Unauthorized'
        ], 401);
        $user = $request->user();
        if ($user->activation_token) {
            return response()->json([
                'errors' => [
                    'message' => 'Waiting for confirmation'
                ]
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
     * @param  [string] cardNumber
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function loginWithCard(Request $request)
    {
        $request->validate([
            'cardNumber' => 'required|string',
            'pin' => 'required|string',
            'rememberMe' => 'boolean'
        ]); // validation
        $user = User::where('cardNumber', $request->only(['cardNumber']))->first();// get the card code from request and check if the card code in database 
        // check authentication    
        if ($user) {
           
        // card number where found
        // comparing the hashed pin with the provided pin
            if (password_verify(request(['pin'][0]), $user->pin)) {
            // user authenticated 
                $tokenResult = $user->createToken(env('JWT_SECRET', 'Personal Access Token')); // creating JWT
                $token = $tokenResult->token;
                $token->expires_at = Carbon::now()->addDays(1);
                if ($request->remember_me)
                    $token->expires_at = Carbon::now()->addWeeks(1); // adding one week if remember me 
                $token->save();
                $user->ipAddressOnLastLogin = serialize($request->getClientIps());
                $user->comingFromBeforeLogin = serialize($request->header('HTTP_REFERER'));
                $user->deviceOnLastLogin = serialize($request->userAgent());
                return response()->json([
                    'success' => [
                        'message' => 'Successfully logged in',
                        'expires_at' => Carbon::parse(
                            $tokenResult->token->expires_at
                        )->toDateTimeString()
                    ]
                ], 200)->header('Authorization', $tokenResult->accessToken); // response success
            }
        }// authorization failed
        // we check if user didn't confirm his email and provide a better error 

        $account = Account::where('cardNumber', $request->only(['cardNumber']))->first(); // we check if the card number is correct

        // we check if the provided pin is correct otherwise we send an Unauthorized message with a 401 response  
        return ($account && password_verify(request(['pin'][0]), $account->pin))
            ?
            response()->json([
            'errors' => [
                'message' => 'Awaiting confirmation.',
                'details' => 'You may need to confirm your email.'
            ]
        ], 351)
            :
            response()->json([
            'errors' => [
                'message' => 'Unauthorized'
            ]
        ], 401);

    }

    /**
     * refresh token for api authorization
     * @param  [$request] current request
     * @return [json] refresh json web token
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
     * Get the authenticated User
     * @param  [$request] current request
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json(['data' => $request->user()]);
    }
    /**
     * Logout user (Revoke the token)
     * @param  [$request] current request
     * @return [json] response with statue 205
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
            'success' => [
                'message' => 'Successfully logged out'
            ]
        ], 205);// response success
    }

}