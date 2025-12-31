<?php

namespace App\Services\Auth;

use Carbon\Carbon;
use App\Models\User;
use Pest\Support\Str;
use Illuminate\Http\Response;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    
    public function userRegister($request){

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $token = auth()->login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'User is registered successful.',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
            ],
        ]);
    }
    public function userLogin($request){
        
        $credentials = $request->only('email', 'password');
        if(!$token = auth()->attempt($credentials)){
            return response()->json([
                'status' => 'error',
                'message' => 'Credentials was invalid!',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User is logged in successful.',
            'data' => [
                'user' => auth()->user(),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
            ],
        ]);
        
    }
    public function resetPasswordLink($request){
        $email = $request->email;
        
        $user = User::where('email', $email)->first();

        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'User not found | If your email exists, a password reset link has been sent.',
            ], Response::HTTP_NOT_FOUND);
        }

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            [
                'email' => $email
            ],
            [
                'email' => $email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );
        
        Mail::to($user->email, $user->name)->send(new ResetPasswordMail($user, $token));

        return response()->json([
            'status' => 'success',
            'message' => 'If your email exists, a password reset link has been sent.',
        ], Response::HTTP_OK);
    }
    public function updatePassword($request) {

        $email = $request->email;
        $password = $request->password;
        $token = $request->token;

        $tokenRecord = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$tokenRecord || !Hash::check($token, $tokenRecord->token)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token is invalid',
            ], Response::HTTP_NOT_FOUND);
        }

        // dd(
        //     Carbon::parse($tokenRecord->created_at)->toDateTimeString(),
        //     now()->toDateTimeString(),
        //     now()->diffInMinutes(Carbon::parse($tokenRecord->created_at))
        // );

        if(Carbon::parse($tokenRecord->created_at)->addHour()->isPast()){
            DB::table('password_reset_tokens')->where('token', $token)->delete();
            return response()->json([
                'status' => 'error',
                'message' => 'Token/Time is expired',
            ], Response::HTTP_NOT_FOUND);
        }
        
        $user = User::where('email', $email)->first();
        if(!$user){
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $user->update(['password' => $password]);
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Password updated successfully.',
        ], Response::HTTP_OK);
    }
}
