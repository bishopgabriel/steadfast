<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\User;
use App\Http\Requests\StoreVoteRequest;
use App\Http\Requests\UpdateVoteRequest;
use Illuminate\Http\Request;
use App\Services\EstimateLocation;

use Illuminate\Support\Str;
use App\Mail\RegisterMail; // for email sending
use Illuminate\Support\Facades\Mail;
use App\Jobs\StoreVoteJob; //store votes job queue


class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return Vote::all();
    }

    /**
     * Verify and Try voting
     */
    public function vote(UpdateVoteRequest $request, EstimateLocation $locationService) {
        $request->validated($request->all());
        // Check if the user has already voted for the item
        $email_ = $request->email;
        $item = $request->item;
        $user = User::where('email', $email_)->first();
        $res = ['status' => 'error', 'message' => ''];

        if (!$user) {
            // register the user and send email
            $res['message'] = 'Invalid user';
            return response()->json($res,422);
        }

        // check verification status
        if(!$this->verifyemail($request, $user)) {
            $res['message'] = 'Invalid code';
        } 

        // Check if the user has a vote
        if (!$res['message'] && !$user->vote()->exists()) {
            // add user's vote and estimated location
            return $this->addVote($request, $user, $locationService);
        } else {
            // If the user has already voted, return a response indicating so
            if (!$res['message']) $res['message'] = 'You already voted.';
        }

        return response()->json($res,422);
    }

    /**
     * Register user
     */
    public function register(StoreVoteRequest $request) {
        $request->validated($request->all());
        // Check if the user has already voted for the item
        $email_ = $request->email;
        $user = User::where('email', $email_)->first();

        if (!$user) {
            // register the user and send email
            return $this->store($request);
        }
        
        $res = ['status' => 'success', 'message' => 'Already registered. Use the code in your email to cast a vote.'];
        return response()->json($res, 201);
    }

    /**
     * Verify email
     */
    public function verifyemail(Request $request, $user) {
        // user is already verified
        if($user->email_verified_at){
            return true;
        }

        // Check verification code
        if($request->verifycode!=$user->verification_code) {
            return false;
        }

        // verify
        $user->email_verified_at = time();
        $user->save();
        
        return true;
    }

    public function addVote($request, $user, $locationService){
        // user IP
        $userIp = $request->ip();
        // user estimated location
        $location = $locationService->getUserLocation($request);

        if($location["status"]){
            $estim_local = $location["city"].", ".$location["country"];
        } else {
            // mostly return when 127.0.0.1  or  ::1  or  couldn't find it
            $estim_local = "Not found";
        }

        // Dispatch the job to store the vote
        StoreVoteJob::dispatch([
            'user_id' => $user->id,
            'item' => $request->item,
            'user_ip' => $userIp,
            'estimated_location' => $estim_local,
        ]);

        return response()->json(["status"=>"success", "message"=>"You have successfully voted."], 201);
    }

    /**
     * Register the user
     */
    public function store(Request $request) {        
        $verificationcode = Str::upper(Str::random(5));
        $user = User::create([
            'email' => $request->email,
            'verification_code' => $verificationcode,
        ]);

        // send mail to new user. It can be verification link
        $mailSubj = "Verify your email";
        $mailRes = Mail::to($user->email)->send(new RegisterMail($verificationcode, $mailSubj));
        
        return response()->json(["status" => "success", "message" => "Check your email and use the code to cast a vote."], 201);
    }
}

