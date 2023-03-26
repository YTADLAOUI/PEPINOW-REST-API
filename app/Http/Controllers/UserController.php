<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    public function register(Request $request)
    {
            $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id'=>3
            ]);
        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    public function getAuthenticatedUser()
        {
                try {

                        if (! $user = JWTAuth::parseToken()->authenticate()) {
                                return response()->json(['user_not_found'], 404);
                        }

                } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                        return response()->json(['token_expired'], $e->getStatusCode());

                } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                        return response()->json(['token_invalid'], $e->getStatusCode());

                } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                        return response()->json(['token_absent'], $e->getStatusCode());

                }

                return response()->json(compact('user'));
        }


        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
        public function update(Request $request, $id)
        {
            $user=User::find($id);
            $validator = Validator::make($request->all(), [
                'role_id' => 'required|integer|in:1,2,3',
            ]);
    
            if($validator->fails()){
                    return response()->json($validator->errors(), 400);
            }
            $role_id = $request->input('role_id');
            $user->role_id = $role_id;
            $user->save();
            return response()->json($user);
        }
        public function updateProfile(Request $request){
            $user=Auth::user();
            // return $user;
            $validator = Validator::make($request->all(), [
                'name' => 'string|min:4',
                'email' => 'string|email|unique:users,email,'.$user->id,   
            ]);
            
            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }
            
            $user->update($validator->getData());
            return  response()->json(['update profile ok']);
        }
        public function logout(){
            auth()->logout();
            return response()->json([
                'message'=>'logOut success'
            ]);
        }
        public function resetpassword(Request $request)
        {
            $user = Auth::user();
            $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|min:6|confirmed',
            ]);
    
            if(Hash::check($request->old_password, $user->password)){
                $user->update([
                    'password' => hash::make($request->new_password),
                ]);
                return response()->json(['statut'=>true, 'msg'=>'updated succesfuly']);
            }else{
                return response()->json(['statut'=>false, 'msg'=>'old password does not matched!']);
            }
        }    
        public function forgotPassword(Request $request)
        {
            $validatedData =$request->validate([
                'email' => 'required|string|email|max:255|exists:users',
            ]);
    
            $response = Password::sendResetLink($validatedData);
    
            return $response == Password::RESET_LINK_SENT
                ? response()->json(['success' => true])
                : response()->json(['error' => 'Failed to send reset link'], 500);
        }
        public function reset(Request $request){
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required'
            ]);
    
            $updatePassword = DB::table('password_resets')
                                ->where([
                                  'email' => $request->input('email'), 
                                  'token' => $request->input('token')
                                ])
                                ->first();
    
            if(!$updatePassword){
                return response()->json(['error'=>'Invalid token!']);
            }
    
            $user = User::where('email', $request->email)
                        ->update(['password' => Hash::make($request->password )]);
    
            DB::table('password_resets')->where(['email'=> $request->email])->delete();
    
            return response()->json(['message'=>'Your password has been changed!']);
        }    

}