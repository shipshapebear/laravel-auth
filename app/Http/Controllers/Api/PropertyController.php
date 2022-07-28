<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Applications;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Validator;
class PropertyController extends Controller
{
    //get the current user's property
    public function getUserProperties($id)
    {
        $properties = Property::where('ownerId', $id)->get();
        return response()->json($properties);
    }

     //get all properties
     public function getProperties()
     {
         $properties = Property::all();
         return response()->json($properties);
     }
     //get all properties
     public function getPropertiesWithCoordinates()
     {
         $properties = Property::whereNotNull('coordinates')->get();
         return response()->json($properties);
     }
 //delete user
 public function deleteProperty($id)
 {
     $property = Property::find($id);
     $property->delete();
     return response()->json(['message' => 'Property deleted successfully.']);
 }

}
