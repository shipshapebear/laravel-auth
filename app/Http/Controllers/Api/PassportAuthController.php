<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Aceraven777\PayMaya\PayMayaSDK;
use Aceraven777\PayMaya\API\Checkout;
use Aceraven777\PayMaya\Model\Checkout\Item;
use App\Libraries\PayMaya\User as PayMayaUser;
use Aceraven777\PayMaya\Model\Checkout\ItemAmount;
use Aceraven777\PayMaya\Model\Checkout\ItemAmountDetails;

class PassportAuthController extends Controller
{
  /**
     * Registration Req
     */
    public function register(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'name' => 'required|min:8|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'c_password' => 'required|same:password',
            'role' => 'required',
            
        ]);
        
        //check if validation fails
       
        //check if email already exists
        if ($user=User::where('email', $request->email)->first()) {
            return response()->json(['error'=>'Email is already taken.'], 400);
        } 
  
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'status' => 'active',
        ]);
  
        $token = $user->createToken('Laravel9PassportAuth')->accessToken;
  
        return response()->json(
            [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'role' => $user->role,
                'status' => $user->status,
                'token' => $token
            ], 200);
    }
    public function signup(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'name' => 'required|min:8|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'c_password' => 'required|same:password',
            'role' => 'required',
        ]);
        
        //check if validation fails
       
        //check if email already exists
        if ($user=User::where('email', $request->email)->first()) {
            return response()->json(['error'=>'Email is already taken.'], 400);
        } 
  
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'taxpayer',
            'status' => 'active',
        ]);
  
        $token = $user->createToken('Laravel9PassportAuth')->accessToken;
  
        return response()->json(
            [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'role' => $user->role,
                'status' => $user->status,
                'token' => $token
            ], 200);
    }
  
    /**
     * Login Req
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|max:255',
            'password' => 'required'
        ]);

        $login = $request->only('email', 'password');
        
        if (!Auth::attempt($login)) {
            return response()->json(['message' => 'Invalid username or password.'], 401);
            
        }
        
        /**
         * @var User $user
         */
        $user = Auth::user();
        $token = $user->createToken($user->name);
        //check if active
        if ($user->status != 'active') {
            return response()->json(['message' => 'User is not active.'], 401);
        }
        return response([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'token' => $token->accessToken,
            'token_expires_at' => $token->token->expires_at,
        ], 200);
    }

    public function forgotPassword(Request $request) {
        $this->validate($request, [
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );
        if ($status == Password::RESET_LINK_SENT) {
            return ['status' => __($status)];
        }
        throw ValidationException::withMessage([
            'email' => [trans($status)]
        ]);
    }

    public function resetPassowrd(Request $request) {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|max:255',
            'password' => 'required|min:6',
            'c_password' => 'required|same:password',
        ]);

        $status = Password::reset(
            $request->only('email', 'passsword', 'c_password', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => str::random(60),
                ])->save();
                event(new PasswordReset($user));
            }

        );

        if($status == Password::PASSWORD_RESET) {
            return response([
                'message' => 'Password reset successfully'
            ]);
            
        }return response ([
            'message' => __($status)
        ], 500);
    }

    public function userInfo() 
    {
 
        return Auth::user();
    }

    public function logout() {
        Auth::user()->tokens->each(function($token, $key) {
            $token->delete();
        });
    
        return response()->json('Successfully logged out');
        return redirect()->to(url('/login'));
    }

    //get all users
    public function getUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    //delete user
    public function deleteUser($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully.']);
    }
    
    //update user status to active or inactive
    public function updateUserStatus($id)
    {
        $user = User::find($id);
        $user->status = $user->status == 'active' ? 'inactive' : 'active';
        $user->save();
        return response()->json([
            'message' => 'Account status updated successfully',
            'status' => $user->status,
        ]);
    }

    //update user role
    public function updateUserRole($id)
    {
        $user = User::find($id);
        $user->role = $user->role == 'admin' ? 'user' : 'admin';
        $user->save();
        return response()->json(['message' => 'User role updated successfully.']);
    }

    //update user name and email
    public function updateUserAccount($id, Request $request)
    {
        $user = User::find($id);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        return response()->json([
            'message' => 'User account updated successfully.',
            'email' => $user->email,
            'name' => $user->name,
        ]);
    }

    

    //update ownerId of a property to the userId
    public function updateOwner($id, Request $request)
    {
        $property = Property::find($id);
        //set ownerId to userId
        $property->ownerId = auth()->user()->id;
        $property->save();
        return response()->json(['message' => 'Property owner updated successfully.']);
    
    }
    
   
    //get all properties of a user
    public function getUserProperties($id)
    {
        $properties = Property::where('ownerId', $id)->get();
        return response()->json($properties);
    }

    //remove the ownerId of a property
    public function removeOwner($id)
    {
        $property = Property::find($id);
        $property->ownerId = null;
        $property->save();
        return response()->json(['message' => 'Property owner removed successfully.']);
    }

    //get specific property
    public function getProperty($id)
    {
        $property = Property::find($id);
        return response()->json($property);
    }

    
    
public function checkout()
{
    PayMayaSDK::getInstance()->initCheckout(
        env('PAYMAYA_PUBLIC_KEY'),
        env('PAYMAYA_SECRET_KEY'),
        (\App::environment('production') ? 'PRODUCTION' : 'SANDBOX')
    );

    $sample_item_name = 'Product 1';
    $sample_total_price = 1005.00;

    $sample_user_phone = '1234567';
    $sample_user_email = 'test@gmail.com';
    
    $sample_reference_number = '1234567890';

    // Item
    $itemAmountDetails = new ItemAmountDetails();
    $itemAmountDetails->tax = "0.00";
    $itemAmountDetails->subtotal = number_format($sample_total_price, 2, '.', '');
    $itemAmount = new ItemAmount();
    $itemAmount->currency = "PHP";
    $itemAmount->value = $itemAmountDetails->subtotal;
    $itemAmount->details = $itemAmountDetails;
    $item = new Item();
    $item->name = $sample_item_name;
    $item->amount = $itemAmount;
    $item->totalAmount = $itemAmount;

    // Checkout
    $itemCheckout = new Checkout();

    $user = new PayMayaUser();
    $user->contact->phone = $sample_user_phone;
    $user->contact->email = $sample_user_email;

    $itemCheckout->buyer = $user->buyerInfo();
    $itemCheckout->items = array($item);
    $itemCheckout->totalAmount = $itemAmount;
    $itemCheckout->requestReferenceNumber = $sample_reference_number;
    $itemCheckout->redirectUrl = array(
        "success" => url('returl-url/success'),
        "failure" => url('returl-url/failure'),
        "cancel" => url('returl-url/cancel'),
    );
    
    if ($itemCheckout->execute() === false) {
        $error = $itemCheckout::getError();
        return redirect()->back()->withErrors(['message' => $error['message']]);
    }

    if ($itemCheckout->retrieve() === false) {
        $error = $itemCheckout::getError();
        return redirect()->back()->withErrors(['message' => $error['message']]);
    }

    return redirect()->to($itemCheckout->url);
}
  
//get all user



}
