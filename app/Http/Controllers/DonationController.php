<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DonationController extends Controller
{
    protected $authUser;

    public function __construct(Request $request) {
        $this->authUser = $request->user();
    }
    /**
     * TODO
     * Making a donation to running funding campaign
     */

     public function donate(Request $request)
     {
        $validator = Validator::make($request->all(), [
            'campaignId'=> 'required|integer',
            'amount'=>'required|numeric|gt:0'
        ]);
        if($validator->fails()){
            return $this->sendValidationErrorResposnse($validator->errors());
        }

        $campaign = Campaign::where('id', $request->get('campaignId'))->first();

        if(!$campaign){
            return $this->sendBadRequestResponse('Campaign not found');
        }

        if($campaign->userId == $request->user()->id){
            return $this->sendBadRequestResponse('Campaign owner not allowed to donate');
        }

        // check if the campaign is still open
        if($campaign->status == "close"){
            return $this->sendBadRequestResponse('Donation cannot be made for campaign is closed');
        }

        if($campaign->status == "completed"){
            return $this->sendBadRequestResponse('Campaign already completed');
        }

        $campaignCloseDate = Carbon::parse($campaign->closeDate)->format("Y-m-d");
        $todayDate = Carbon::today()->format('Y-m-d');

        if ($campaignCloseDate <= $todayDate){
            return $this->sendBadRequestResponse('Donation cannot be made for campaign is closed');
        }

        $donate = new Donation();
        $donate->amount = $request->get('amount');
        $donate->user()->associate($request->user());
        $donate->campaign()->associate($campaign);
        
        if($donate->save()){
            $updateAmount = $campaign->amountReceived + $request->get('amount');
            $campaign->amountReceived = $updateAmount;
            if($updateAmount >= $campaign->targetAmount){
                $campaign->status = "completed";
            }
            $campaign->save();
        }

        return $this->sendSuccessResponse($donate, 'Donation made successfully');
     }


     /**
      * TODO
      * get campaign donation
      */
      public function campaignDonations(int $campaignId)
      {
        $campaignDonations = DB::table('campaigns')
                                ->join('donations', 'campaigns.id','=','donations.campaignId')
                                ->join('users', 'users.id', '=', 'donations.userId')
                                ->where('campaigns.id', $campaignId)
                                ->select('users.id', 'users.name  AS donatorName', 'users.email As donatorEmail', 'donations.*','campaigns.id', 'campaigns.name AS campaignName', 'campaigns.description AS campaignDescription')
                                ->get();
        return $this->sendSuccessResponse($campaignDonations, 'Donations fetched');
      }

}
