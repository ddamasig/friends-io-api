<?php

namespace App\Http\Controllers;

use Core\Models\User;
use Core\Models\UserSocial;
use Exception;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class SocialLoginController extends Controller
{
    protected $auth;

    public function __construct(Sanctum $auth)
    {
        /**
         * Pass the JWT object
         */
        $this->auth = $auth;
        $this->middleware('social');
    }

    public function redirect($service)
    {
        return Socialite::driver($service)->stateless()->redirect();
    }

    public function callback(Request $request, $service)
    {
        try {
            $serviceUser = Socialite::driver($service)->stateless()->user();
        } catch (InvalidStateException $exception) {
            return redirect(env('CLIENT_BASE_URL') . '/auth/social-callback?error=Unable to login using ' . $service . '. Please try again.?origin=login');
        } catch (Exception $e) {
            dd($e);
        }

        $email = $serviceUser->getEmail();
        if ($service != 'google') {
            $email = $serviceUser->getId() . '@' . $service . '.local';
        }

        $user = $this->getExistingUser($serviceUser, $email, $service);
        $newUser = false;

        if (!$user) {
            $newUser = true;
            $user = User::create([
                'name'  => $serviceUser->getName(),
                'email' => $email,
                'password' => ''
            ]);
        }

        if ($this->needsToCreateSocial($user, $service)) {
            UserSocial::create([
                'user_id' => $user->getKey(),
                'social_id'   => $serviceUser->getId(),
                'service' => $service
            ]);
        }

        return redirect(env('CLIENT_BASE_URL') . '/auth/social-callback?token=' . $serviceUser->token . '&origin=' . ($newUser ? 'register' : 'login'));
    }

    public function needsToCreateSocial(User $user, $service)
    {
        return !$user->hasSocialLinked($service);
    }

    public function getExistingUser($serviceUser, $email, $service)
    {
        if ($service === 'google') {
            return User::where('email', $email)
                ->orWhereHas('social', function ($query) use ($serviceUser, $service) {
                    $query->where('social_id', $serviceUser->getId())
                        ->where('service', $service);
                })->first();
        } else {
            $userSocial = UserSocial::where('social_id', $serviceUser->getKey())
                ->first();
            return $userSocial ? $userSocial : null;
        }
    }
}
