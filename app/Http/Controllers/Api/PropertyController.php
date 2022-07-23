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
    //
    public function getUserProperties($id)
    {
        $properties = Property::where('ownerId', $id)->get();
        return response()->json($properties);
    }
}
