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

    public function getAllTransactions(){
        $transactions = Payment::all();
        return response()->json($transactions);
    }
    public function getPropertyPayment($id){
        $payment = Payment::where('tdId', $id)->get();
        return response()->json($payment);
    }
    public function getUserPayment($id){
        $payment = Payment::where('ownerId', $id)->get();
        return response()->json($payment);
    }

    public function getLatestPayment($id) {
        $latestPayment = Payment::where('tdId', $id)->where('payment_status', 'success')->orderBy('date_of_payment','DESC')->first();
        return response()->json($latestPayment);
    }

    public function getAllLatestPayments($id) {
        $latestPayment = Payment::where('ownerId', $id)->orderBy('date_of_payment', 'DESC')->first();
        return response()->json($latestPayment);
    }

    public function checkout() {

        
    }
}
