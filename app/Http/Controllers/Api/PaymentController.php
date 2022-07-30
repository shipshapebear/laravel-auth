<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Validator;

class PaymentController extends Controller
{
    public function getPropertyPayment($id){
        $payment = Payment::where('tdId', $id)->get();
        return response()->json($payment);
    }
    public function getUserPayment($id){
        $payment = Payment::where('ownerId', $id)->get();
        return response()->json($payment);
    }

    public function getLatestPayment($id) {
        $latestPayment = Payment::where('tdId', $id)->orderBy('date_of_payment','DESC')->first();
        return response()->json($latestPayment);
    }
}
