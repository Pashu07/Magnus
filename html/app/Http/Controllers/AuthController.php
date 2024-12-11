<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use Session;
use Mail;
use Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;
use File;

class AuthController extends Controller
{
/**
* Create user
*
* @param  [string] name
* @param  [string] email
* @param  [string] password
* @param  [string] password_confirmation
* @return [string] message
*/
public function register(Request $request)
{
    // $request->validate([
    //     'name' => 'required|string',
    //     'email'=>'required|string|unique:users',
    //     'password'=>'required|string',
    //     'user_role' => 'required|string',
    //     'contact' => 'string'
    // ]);

  $user = new User([
    'name'  => $request->name,
    'email' => $request->email,
    'password' => bcrypt($request->password),
    'user_role' => $request->user_role,
    'contact' => $request->contact
  ]);

  if($user->save()){
    $tokenResult = $user->createToken('Personal Access Token');
    $token = $tokenResult->plainTextToken;

    return response()->json([
      'message' => 'Successfully created user!',
      'id'=> $user['id'],
      'name'=> $user['name'],
      'email'=> $user['email'],
      'role'=> $user['user_role'],
      'token' =>$token,
      'status' => 200
    ]);
  }
  else{
    return response()->json(['error'=>'Provide proper details','status' => 400]);
  }
}
/**
* Login user and create token
*
* @param  [string] email
* @param  [string] password
* @param  [boolean] remember_me
*/

public function login(Request $request)
{
  $request->validate([
    'email' => 'required|string|email',
    'password' => 'required|string',
    'remember_me' => 'boolean'
  ]);




  if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]))

  {

   return response()->json([
    'message' => 'Unauthorized',
    'status' => 401
  ]);

 }else{
   $user = $request->user();
   $credentials['id']=$user['id'];
   $credentials['account_id']="SHRIHR";
   $credentials['user_role']=$user['user_role'];
   $credentials['user_name']=$user['name'];
   Session::put('user', $credentials);
   $tokenResult = $user->createToken('Personal Access Token');
   $token = $tokenResult->plainTextToken;
   Session::put('token', $token);

   return response()->json([
    'message'=> "Login Successfully",
    'id'=> $user['id'],
    'name'=> $user['name'],
    'email'=> $user['email'],
    'role'=> $user['user_role'],
    'token' =>$token,
    'status' => 200
  ]);

 }


}

public function user_operation(Request $request)
{
  $value=$request->value;
  if($value=="1"){
    $value=0;
    $message="User Activated Successfully";
  }else if($value=="0"){
    $message="User De-activated Successfully";
    $value=1;
  }
  $user = User::where('id', $request->id)->update(['is_active' => $value]);
  return response()->json([
    'value'=> $value,
    'message' =>$message,
    'status' => 200
  ]);

}





public function submitForgetPasswordForm(Request $request)
{
  $request->validate([
    'email' => 'required|email|exists:users',
  ]);

  $token = Str::random(64);

  DB::table('password_resets')->insert([
    'email' => $request->email,
    'token' => $token,
    'created_at' => Carbon::now()
  ]);

  Mail::send('email.forgetPassword', ['token' => $token], function($message) use($request){
    $message->to($request->email);
    $message->subject('Reset Password');
  });

  return back()->with('message', 'We have e-mailed your password reset link!');
}


public function showResetPasswordForm($token) {
  return view('/content/authentication/auth-reset-password-cover', ['token' => $token]);

}

      /**
       * Write code on Method
       *
       * @return response()
       */
      public function submitResetPasswordForm(Request $request)
      {
        $request->validate([
          'password' => 'required|string|min:6|confirmed',
          'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
        ->where([
          'token' => $request->token
        ])
        ->first();

        $email = $updatePassword->email;

        if(!$updatePassword){
          return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $email)
        ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $email])->delete();

        return redirect('login')->with('message', 'Your password has been changed!');
      }


      public function selfresetPassword(Request $request)
      {
        $request->validate([
          'email' => 'required|string|email',
          'password' => 'required|string',
          'new_password' => 'required|string|min:6'
        ]);

  // $user = Auth::User();
        $credentials = request(['email','password']);
  // if(!$credentials)
        if(!Auth::attempt($credentials))

        {

         return back()->with('error', 'Current Password Not Matched');

       }else{

        $user = User::where('email', $request->email)
        ->update(['password' => Hash::make($request->new_password)]);

        $user = $request->user();
        $credentials['password']=$request->new_password;
        $credentials['account_id']="FAME";
        $credentials['user_role']=$user['user_role'];
        Session::put('user', $credentials);

        return back()->with('message', "Password Updated Successfully");

      }


    }
    public function updateProfile(Request $request)
    {

      if(empty($request->profile) || !isset($request->profile)){
        $user = User::where('email', $request->email)->update(['name' => $request->name,
          'contact' => $request->phoneNumber]);
      }else{
    // Image upload Start
        $imageName = time().'.'.$request->profile->extension();

        $path = public_path().'/profile/'.Auth::id();
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        $request->profile->move($path, $imageName);

    // Image Upload End

        $user = User::where('email', $request->email)->update(['name' => $request->name,
          'user_profile' => $imageName,
          'contact' => $request->phoneNumber]);

      }

      return back()->with('message', "Profile Updated Successfully");




    }

    public function adminupdateProfile(Request $request)
    {
 // return $request;
   // dd($request,"aa");

      $request->validate([
        'name' => 'required|string',
        'phoneNumber' => 'required',
        'email' => 'required|string|email'
      ]);

      if(empty($request->profile) || is_null($request->profile)){
        $user = User::where('id', $request->id)->update(['name' => $request->name,
          'contact' => $request->phoneNumber,'email' => $request->email,'user_role' => $request->user_role]);
      }else{
    // Image upload Start
        $imageName = time().'.'.$request->profile->extension();

        $path = public_path().'/profile/'.$request->id;
        echo $path;;
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        $request->profile->move($path, $imageName);

    // Image Upload End

        $user = User::where('id', $request->id)->update(['name' => $request->name,
          'user_profile' => $imageName,
          'contact' => $request->phoneNumber,'email' => $request->email,'user_role' => $request->user_role]);

      }

      return  redirect()->route('users-list')->with('message', "Profile Updated Successfully");




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
  session()->flush();
  session()->forget('user');
  redirect('login');
  return redirect('login')->with('message', 'Logout Successfully!');
}

}
