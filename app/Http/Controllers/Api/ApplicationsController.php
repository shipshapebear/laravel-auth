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
       
        
        $application = Applications::find($request->id);
        $application->status = 'approved';
        $application->save();

        //set property the id of the applicantid/owner id into the property
        if($application->status == 'approved') {
            $property->coordinates = $request->coordinates;
            $property->ownerId = $request->applicantId;
            $property->save();
        }
       
        $property = Property::find($id);
        return response()->json([
            'id' => $application->id,
            'status' => $application->status,
            'message' => 'Property successfully approved.',
        ], 200);
       
    }
    public function rejectApplication($id, Request $request)
    {
        //set applicantId to userId
        $application = Applications::find($request->id);
        $application->status = 'rejected';
        $application->save();

        return response()->json([
            'id' => $application->id,
            'status' => $application->status,
            'message' => 'Property successfully rejected.',
        ], 200);
       
    }


    public function revertApplication($id, Request $request)
    {
        
      
        $application = Applications::find($request->id);
        $application->status = 'pending';
        $application->save();

        $property = Property::find($id);
        $property->coordinates = null;
        $property->ownerId = null;
        $property->save();

        return response()->json([
            'id' => $application->id,
            'status' => $application->status,
            'message' => 'Property successfull approved.',
        ], 200);
       
    }

}
