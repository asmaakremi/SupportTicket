<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function signUp(Request $request)
    {
        $request->validate([
            'username' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        //Check if email exists in database
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return [
                "success" => false,
                "message" => "user exists"
            ];
        }


        // Send a welcome email after user registration
        Mail::send('emails.welcome', ['username' => $request->username], function (Message $message) use ($request) {
            $message->to($request->email);
            $message->subject('Welcome To Support Tickets');
        });
        if (count(Mail::failures()) > 0) {
            return ['success' => false, 'message' => 'Mail does not exist'];
        }

        //Create a new user
        try {


            $user = new User([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),

            ]);

            $user->save();


            return [
                "success" => true,
                'message' => 'user added'
            ];
        } catch (\Exception $exception) {
            return response([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }


    public function signIn(Request $request)
    {
        try {
            $validator = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required']
            ]);
            // Validate email and password to check if it is the same as in database
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return [
                    "success" => false,
                    'message' => 'authentification error'
                ];
            }

            //Create an access token manually to authenticate users using Passport
            return [
                "success" => true,
                "token" => $user->createToken('Auth-token')->accessToken
            ];
        } catch (\Exception $exception) {
            return response([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }
}
