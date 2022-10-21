<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $formFields = $request->validate([
            "name" => "required|string|unique:users,name",
            "email" => "required|email|unique:users,email",
            "password" => "required|string|unique:users,password|confirmed",
        ]);
        $formFields['password'] = bcrypt($formFields['password']);

        /**
         * @var User
         */
        $user = User::create($formFields);
        $token = $user->createToken(env("APP_KEY"))->plainTextToken;
        auth()->login($user);

        return response()->json([
            "user" => $user,
            "token" => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            "message" => "user loged out successfully"
        ]);
    }

    public function login(Request $request)
    {

        $formFields = $request->validate([
            "email" => "required|email",
            "password" => "required|string",
        ]);

        /**
         * @var User
         */
        $user = User::where("email", $formFields['email'])->first();
        if (!$user || !Hash::check($formFields['password'], $user['password'])) {
            return response()->json([
                "error" => "Invalid Credentials"
            ], 401);
        }
        $token = $user->createToken(env("APP_KEY"))->plainTextToken;
        return response()->json(
            [
                "user" => $user,
                "token" => $token
            ]
        );
    }

    public function publishTweet(Request $request)
    {

        $tweetData = $request->validate([
            "content" => "required|string",
            "attachment" => "file",
        ]);

        $tweetData['user_id'] = $request->user()->id;
        $request->file("attachment")->store("");

        $tweet = Tweet::create($tweetData);

        return $tweet;
    }
}
