<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// use Twilio\Rest\Client;

class AuthController extends Controller
{
    public function register(Request $request)
    { 
        $request->validate([
            'name' => 'required',
            'phone' => ['required','unique:users', 'regex:/^[0-9]+$/'],
            'password' => 'min:6',
        ]);
       // $authToken = $user->createToken('auth-token')->plainTextToken;
        $sms=$this->sendSMS($request->phone);
        return response([
            'success' => true,
            // 'access_token' => $authToken,
        ]);
    }

    public function logout()
    {

        $user = Auth::user();
        $u = User::where('id', $user->id)->first();
        $u->tokens()->delete();
        return response()->json([
            'status' => true,
        ]);

    }

    ///login screen 1111111
    public function login(Request $request)
    {
      
        request()->validate([
            'phone' => ['required', 'regex:/^[0-9]+$/'],
            'password' => 'min:6',
        ]);

        $fields = request(['phone', 'password']);
        if (!auth()->attempt($fields)) {
            return response([
                'status' => false,
            ]);
        }

        $user = User::where('phone', $request->phone)->first();
        $authToken = $user->createToken('auth-token')->plainTextToken;

        return response([
            'status' => true,
            'access_token' => $authToken
          
        ]);
        
    }

    //send the otp 2222222
    public function sendSMS(string $phone)
    {   
        $basic = new \Vonage\Client\Credentials\Basic("9d3f82fc", "D6EPxByXtmjr0bUU");
        $client = new \Vonage\Client($basic);
       // $user = User::where('id', $id)->first();
    //    $phone=$user->phone;
        # User Does not Have Any Existing OTP
        $verificationCode = VerificationCode::where('phone', $phone)->latest()->first();

        $now = now();

        if ($verificationCode && $now->isBefore($verificationCode->expire_at)) {
            $otp = $verificationCode->otp;
        }

        // Create a New OTP
        else{
        $verificationCode = VerificationCode::create([
            'phone' => $phone,
            'otp' => rand(111111, 999999),
            'expire_at' => now()->addMinutes(10),
        ]);
           $otp= $verificationCode->otp;
        }
        
        
        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS($phone, 'hi', $otp)
        );
        
        $message = $response->current();

        if ($message->getStatus() == 0) {
            return ([
                'status' => 'true',
                'otp' => $otp,
            ]);
        } else {
            echo "The message failed with status: " . $message->getStatus() . "\n";
        }
        
        
    }

    //////verification of the otp 44444444
    public function verification(Request $request)
    { 
        #Validation
        $request->validate([
            'name' => 'required',
            'phone' => ['required', 'regex:/^[0-9]+$/'],
            'otp' => ['required','regex:/^[0-9]+$/','digits:6'],
            'password' => 'min:6',
        ]);
        
        #Validation Logic
        $verificationCode = VerificationCode::where('phone', $request->phone)->where('otp',  $request->otp)->first();
          
        $now = now();
        if (!$verificationCode) {
            // return redirect()->back()->with('error', 'Your OTP is not correct');
            return response([
                'status' => 'Your OTP is not correct',
            ]);
        } elseif ($verificationCode && $now->isAfter($verificationCode->expire_at)) {
            // return redirect()->route('otp.login')->with('error', 'Your OTP has been expired');
            return response([
                'status' => 'Your OTP has been expired',
            ]);
        }
        // Expire The OTP
        $verificationCode->update([
            'expire_at' => now(),
        ]);
        $user = User::where('phone', $request->phone)->first();

        if($user){
        $authToken = $user->createToken('auth-token')->plainTextToken;
        return response()->json([
            'status' => true,
            'access_token' => $authToken,
        ]);
         }
         if(!$user){
            $user = new User;
        $user->name =  $request->name;
        $user->phone = $request->phone;
        $user->password = bcrypt( $request->password);
        $user->save();
        $authToken = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'access_token' => $authToken,
        ]);
         }

        /// return redirect()->route('otp.login')->with('error', 'Your Otp is not correct');
    }

    // ///sending message 333333
    // public function index()
    // {
    //     // $gg=$this->generateOtp($request->phone);
    //     // $receiverNumber=$request->phone;
    //     // $message="Your OTP To Login is - ".$request->otp;
    //     // $message="Your OTP To Login is - ".$gg->otp;
    //     $twilio_number=getenv("TWILIO_PHONE");
    //     $auth_token=getenv("TWILIO_TOKEN");
    //     $account_sid=getenv("TWILIO_SID");

    //     $client = new Client($account_sid , $auth_token);
    //     $client->messages->create("+48699510652",[
    //     'from'=>$twilio_number,
    //     'body'=>'hi']);
    // }

    // public function generate(Request $request)
    // {
    //     // # Validate Data
    //     // $request->validate([
    //     //     'phone' => 'required|exists:users,phone'
    //     // ]);

    //     # Generate An OTP
    //    /// $verificationCode = $this->generateOtp($request->phone);

    //   ///  $message = "Your OTP To Login is - ".$verificationCode->otp;
    //     # Return With OTP

    //     return redirect()->route('otp.verification', ['user_id' => $verificationCode->user_id])->with('success',  $message);
    // }
}
