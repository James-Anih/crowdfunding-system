<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CampaignController extends Controller
{
    /**
     * TODO
     * create a campaign for a crowdfunding 
     */

     public function create(Request $request)
     {
        $validator = Validator::make($request->all(),[
            'name'=>'required|string',
            'description'=>'required|string',
            'targetAmount'=>'required|numeric|gt:0',
            'closeDate'=>['required', 'date', function($attribute,$value,$fail){
                $inputDate = Carbon::parse($value)->format('Y-m-d');
                $todayDate = Carbon::today()->format('Y-m-d');

                if($inputDate < $todayDate){
                    $fail("The closeDate must be valid date.");
                }

                if($inputDate === $todayDate){
                    $fail("The closeDate must not be equal to today's date.");
                }
            }]
        ]);

        if($validator->fails()){
            return $this->sendValidationErrorResposnse($validator->errors());
        }

        $campaign = new Campaign();
        $campaign->name = $request->get('name');
        $campaign->description = $request->get('description');
        $campaign->targetAmount = $request->get('targetAmount');
        $campaign->closeDate = $request->get('closeDate');
        $campaign->user()->associate($request->user());
        $campaign->save();

        return $this->sendSuccessResponse($campaign, 'Funding campaign created successfully');
        
     }

     public function update(Request $request, int $campaignId)
     {
        $campaign = Campaign::where(['id'=>$campaignId, 'userId'=>$request->user()->id])->first();

        if(!$campaign){
            return $this->sendBadRequestResponse('Campaign not found');
        }

        if($request->get('closeDate')){
            $inputDate = Carbon::parse($request->get('closeDate'))->format('Y-m-d');
            $todayDate = Carbon::today()->format('Y-m-d');

            if($inputDate < $todayDate){
                return $this->sendBadRequestResponse('The closeDate must be valid date.');
            }

            if($inputDate === $todayDate){
                return $this->sendBadRequestResponse("The closeDate must not be equal to today's date.");
            }
        }
        $campaign->name = $request->get('name') ? $request->get('name'): $campaign->name;
        $campaign->description = $request->get('description') ? $request->get('description'): $campaign->description;
        $campaign->targetAmount = $request->get('targetAmount')? $request->get('targetAmount'):$campaign->targetAmount;
        $campaign->closeDate = $request->get('closeDate')? $request->get('closeDate'):$campaign->closeDate;
        $campaign->status = $request->get('status')?$request->get('status'):$campaign->status;
        $campaign->save();

        return $this->sendSuccessResponse($campaign, "Campaign updated successfully");
     }
    
     /**
      * TODO
      * Get all active funding campaigns request
      */

     public function campaigns()
     {
        $campaigns = Campaign::where('status','open')->orderBy('created_at','desc')->get();
        return $this->sendSuccessResponse($campaigns, 'Active campaigns fetched');
     }

     /**
      * TODO
      * Get all logged in user campaigns
      */
      public function userCampaigns(Request $request)
      {
        $user = $request->user();
        $campaigns = Campaign::where('userId', $user->id)->orderBy('created_at','desc')->get();
        return $this->sendSuccessResponse($campaigns, 'Campaigns fetched');
      }

      public function closeCampaign(Request $request,int $campaignId)
      {
        $user = $request->user();
        $campaign = Campaign::where(['userId'=>$user->id, 'id'=> $campaignId])->first();
        if(!$campaign){
            return $this->sendValidationErrorResposnse('Campaign not found');
        }
        $campaign->status = "close";
        $campaign->save();
        return $this->sendSuccessResponse('','Campaign closed successfully');
      }

      public function deleteCampaign(Request $request, int $campaignId)
      {
        $campaign = Campaign::where(['id'=>$campaignId, 'userId'=>$request->user()->id])->first();
        if(!$campaign){
            return $this->sendBadRequestResponse('Campaign not found');
        }

        $campaign->delete();
        return $this->sendSuccessResponse('', 'Campaign Deleted successfully');
      }
     
}
