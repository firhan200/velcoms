<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use File;
use Config;
use URL;
use Mail;
use Carbon\Carbon;

class AuthController extends Controller
{
    //global variable
    protected $model;
    protected $data;

    public function __construct(){
         //init model
         $this->model = new \App\Models\Administrator;

         //set jwt user
         Config::set('jwt.user', \App\Models\Administrator::class);
         Config::set('auth.providers', ['users' => [
                'driver' => 'eloquent',
                'model' => \App\Models\Administrator::class,
            ]]);
    }

    /* ============================================================================== */
    /* ================     Authorization   ==================== */
    /* ============================================================================== */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            $expired_time = 60 * 8; //8 hours
            //check if remember me
            if($request->input('remember_me')){
                $expired_time = 60 * 24 * 7; //7 days
            }
            //set jwtt expire
            Config::set('jwt.ttl', $expired_time);
            
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials']);
            }

        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    //get logged on user
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

    //forgot password
    public function forgotPassword(Request $request){
        $response = ['is_success' => false, 'message' => ''];

        $email = $request->input('email');
        //validating email input
        $userObj = $this->model::where('email' , $email)->first();
        if($userObj!=null){
            //update reset password token
            $resetPasswordToken = sha1(md5("secret".date("d-M-Y, H:i:s", strtotime(Carbon::now()))));
            //update reset_password_token
            $userObj->reset_password_token = $resetPasswordToken;
            $userObj->reset_password_sent = Carbon::now();
            $userObj->save();
            //send email
            $resetPasswordUrl = URL::to('admin#/reset-password?token='.$resetPasswordToken);
            $data = array('name'=>$userObj->name, "url" => $resetPasswordUrl);
            $this->data['email'] = $userObj->email;
            $this->data['name'] = $userObj->name;
            try{
                Mail::send('emails.forgot_password', $data, function($message) {
                    $message->to($this->data['email'], $this->data['name'])
                    ->subject('Forgot Password');
                });
    
                $response['is_success'] = true;
                $response['message'] = 'Link to reset password has been send to your email';
            }catch(\Exception $ex){
                //error
                $response['message'] = 'Failed to send message <b>'.$ex->getMessage().'';
            }
        }else{
            $response['message'] = 'E-mail not found';
        }

        return response()->json($response);
    }

    //check link reset password
    public function resetPasswordCheckLink(Request $request){
        $response = ['is_valid' => false, 'message' => ''];

        $resetPasswordToken = $request->input('token');

        //validating
        if($resetPasswordToken!=null){
            $userObj = $this->model->where('reset_password_token', $resetPasswordToken)->first();
            if($userObj!=null){
                //check if link has been seen over 2 hour
                $reset_password_sent = Carbon::parse($userObj->reset_password_sent)->addHours(2);
                //return $reset_password_sent." > ".Carbon::now('UTC');
                if(!$reset_password_sent->gt(Carbon::now())){
                    $response['message'] = 'Link Expired!';
                }else{
                    //valid token
                    $response['is_valid'] = true;
                }
            }else{
                //trigger flash message
                $response['message'] = 'Token invalid!';
            }
        }else{
            $response['message'] = 'Token invalid!';
        }

        return response()->json($response);
    }

    //forgot password
    public function resetPassword(Request $request){
        $response = ['is_success' => false, 'message' => ''];

        $token = $request->input('token');
        $password = bcrypt($request->input('password'));
        //validating
        if($token!=null){
            $userObj = $this->model->where('reset_password_token', $token)->first();
            if($userObj!=null){
                //check if link has been seen over 2 hour
                $reset_password_sent = Carbon::parse($userObj->reset_password_sent)->addHours(2);
                if($reset_password_sent->gt(Carbon::now('UTC'))){
                    $userObj->password = $password;
                    $userObj->save();
                    $response['message'] = 'Password has been reset';
                    $response['is_success'] = true;
                }else{
                    $response['message'] = 'Link Expired!';
                }
            }else{
                //trigger flash message
                $response['message'] = 'Token invalid';
            }
        }else{
            //token cannot be null
            $response['message'] = 'Token invalid';
        }

        return response()->json($response);
    }
}
