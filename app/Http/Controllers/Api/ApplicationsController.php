<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Applications;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Validator;
class ApplicationsController extends Controller
{
    //

    public function apply(Request $request)
    {
        $validator=Validator::make($request->all(), [

            'tdId' => 'required',
            'applicantId' => 'required',
            'name' => 'required',
            'address' => 'required',
            'classification' => 'required',
            'assessedValue' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg|max:5000',
            'coordinates' => 'required',
            'status' => 'required',
        ]);
      
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'error']);
        }
       
        $name = $request->file('image')->getClientOriginalName();
 
        $path = $request->file('image')->store('public/images');


        $applications = Applications::create([
            'tdId' => $request->tdId,
            'applicantId' => $request->applicantId,
            'name' => $request->name,
            'address' => $request->address,
            'classification' => $request->classification,
            'assessedValue' => $request->assessedValue,
            'image' => $path,
            'coordinates' => $request->coordinates,
            'status' => 'pending',

        ]);
  
        return response()->json(['message' => 'Your application sent succesfully.'], 200);
    }

    public function getApplications()
    {
        //$applications = Applications::where('status', 'pending')->get();
        $applications = Applications::all();
        return response()->json($applications);
    }

    public function getApplication($id)
    {
        $application = Applications::find($id);
        return response()->json($application);
    }

    public function approveApplication($id, Request $request)
    {
        $property = Property::find($id);
        //set applicantId to userId
        //$application = Applications::find($request->id);
        //$application->status = 'approved';
        //$application->save();

        $property->ownerId = $request->applicantId;
        $property->save();
        return response()->json(['message' => 'Property owner updated successfully.']);
    }

}
