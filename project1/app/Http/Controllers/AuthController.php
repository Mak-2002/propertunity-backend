<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function GuzzleHttp\is_host_in_noproxy;
use function PHPUnit\Framework\returnSelf;

// use Twilio\Rest\Client;

class AuthController extends Controller
{
    public function adminLogin(Request $request)
    {
        // Qusai: used Laravel's validator to access Validator::fails() method
        $validator = validator($request->only('phone', 'password'), [
            'phone' => ['required', 'regex:/^[0-9+]+$/'],
            'password' => 'min:6',
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);

        if ($request->phone !== "123456789" || !auth()->attempt(request()->only('phone', 'password')))
            return response()->json([
                'success' => false,
                'message' => 'User with phone not found Or Wrong password'
            ], 401);

        return response([
            'success' => true,
            'message' => 'Logged in successfully',
            'access_token' => Auth::user()->createToken('auth-token')->plainTextToken,
        ]);
    }

    public function register(Request $request)
    {

        $validator = validator($request->only('name', 'phone', 'password'), [
            'name' => 'required|string',
            'phone' => ['required', 'unique:users', 'regex:/^[0-9+]+$/'],
            'password' => 'required|min:6',
        ]);

        // we will assume that frontend checked for all rules except for uniqueness
        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => 'A user with the entered phone number already exists',
            ], 401);

        // $authToken = $user->createToken('auth-token')->plainTextToken;


        $sms = $this->sendSMS($request->phone); //DEBUG
        return response([
            'success' => true,
            'message' => 'Waiting for OTP verification',
            // 'access_token' => $authToken,
        ]);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();
        // Auth::logout(); //DEBUG
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    ///login screen 1111111
    public function login(Request $request)
    {
        // Qusai: used Laravel's validator to access Validator::fails() method
        $validator = validator($request->only('phone', 'password'), [
            'phone' => ['required', 'regex:/^[0-9+]+$/'],
            'password' => 'min:6',
        ]);

        if ($validator->fails())
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);

        if (!auth()->attempt(request()->only('phone', 'password')))
            return response()->json([
                'success' => false,
                'message' => 'User with phone not found Or Wrong password'
            ], 401);

        return response([
            'success' => true,
            'message' => 'Logged in successfully',
            'access_token' => Auth::user()->createToken('auth-token')->plainTextToken,
        ]);
    }

    //send the otp 2222222
    public function sendSMS(string $phone)
    {
        //$basic = new \Vonage\Client\Credentials\Basic("9d3f82fc", "D6EPxByXtmjr0bUU"); //DEBUG
        //  $basic = new \Vonage\Client\Credentials\Basic("a293b6b5", "Vmbeth6UAsoyEUnA");

        // $client = new \Vonage\Client($basic); //DEBUG
        // $user = User::where('id', $id)->first();
        //    $phone=$user->phone;
        # User Does not Have Any Existing OTP
        $verificationCode = VerificationCode::where('phone', $phone)->latest()->first();

        $now = now();

        if ($verificationCode && $now->isBefore($verificationCode->expire_at)) {
            $otp = $verificationCode->otp;
        }

        // Create a New OTP
        else {
            $verificationCode = VerificationCode::create([
                'phone' => $phone,
                'otp' => 111111, // DEBUG: Set by qusai for testing purpose
                // 'otp' => rand(111111, 999999), //PRODUCTION
                'expire_at' => now()->addMinutes(10),
            ]);
            $otp = $verificationCode->otp;
        }


        // $response = $client->sms()->send( //DEBUG
        //     new \Vonage\SMS\Message\SMS($phone, 'hi', $otp)
        // );

        // $message = $response->current();

        // if ($message->getStatus() == 0) {
        //     return ([
        //         'success' => true,
        //         'otp' => $otp,
        //     ]);
        // } else {
        //     //TODO: should it return a response ?
        //     echo "The message failed with success: " . $message->getStatus() . "\n";
        // }
    }

    public function verification(Request $request)
    {
        #Validation
        $validator = validator($request->only('name', 'phone', 'otp', 'password'), [
            'name' => 'required',
            'phone' => ['required', 'regex:/^[0-9+]+$/'],
            'otp' => ['required', 'regex:/^[0-9]+$/', 'digits:6'],
            'password' => 'min:6',
        ]);

        if ($validator->fails())
            return response([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);

        #Validation Logic
        $verificationCode = VerificationCode::where('phone', $request->phone)->where('otp',  $request->otp)->first();

        $now = now();
        $data = [
            'success' => false
        ];
        if (!$verificationCode) {
            // return redirect()->back()->with('error', 'Your OTP is not correct');
            $data['message'] = 'OTP not creaeted or OTP is wrong';
            return response($data, 401);
        } elseif ($now->isAfter($verificationCode->expire_at)) {
            // return redirect()->route('otp.login')->with('error', 'Your OTP has expired');
            $data['message'] = 'Your OTP has expired';
            return response($data, 401);
        }
        // Expire The OTP
        $verificationCode->update([
            'expire_at' => now(),
        ]);
        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            $authToken = $user->createToken('auth-token')->plainTextToken;
            Auth::attempt([$request->phone, $request->password]);
            return response()->json([
                'success' => true,
                'message' => 'User verefied and logged in successfully',
                'access_token' => $authToken,
            ]);
        }
        if (!$user) {
            $user = new User;
            $user->name =  $request->name;
            $user->phone = $request->phone;
            $user->password = bcrypt($request->password);
            $user->save();
            $authToken = $user->createToken('auth-token')->plainTextToken;
            Auth::attempt($request->only('phone', 'password'));

            return response()->json([
                'success' => true,
                'message' => 'User created and verefied and logged in successfully',
                'access_token' => $authToken,
            ], 201);
        }

        /// return redirect()->route('otp.login')->with('error', 'Your OTP is not correct');
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
