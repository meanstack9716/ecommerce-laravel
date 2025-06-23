<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Seller;
use App\Models\Address;
use App\Models\Role;
use App\Models\IdentityProof;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use App\Constants\Constants;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private $clientSessionKey = 'client_registration_data';

    public function fetchUserList(Request $request) 
    {
        $limit = $request->input('limit', 10);
        $search = $request->input('search');
        $roleId = $request->input('role');
        $status = $request->input('status');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        if (empty($sortBy)) {
            $sortBy = 'created_at';
        }

        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleId) {
            $query->where('role_id', $roleId);
        }

        if ($status) {
            if ($status == Constants::STATUS_ACTIVATED) {
                $query->where(function ($q) {
                    $q->where('status', Constants::STATUS_ACTIVATED)
                        ->orWhereNull('status');
                });
            } else {
                $query->where('status', $status);
            }
        }
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'desc';
    
        // Add sorting
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate($limit);
        $roles = Role::all(); 
        return view('users.list', compact('users', 'limit', 'roles'));
    }

    public function showAddUserForm(Request $request)
    {
        $roles = Role::all();
        return view('users.form', compact('roles'));
    }

    public function createNewUser(Request $request)
    {
        $user = User::create([
            'email'    => $request->email,
            'first_name'    => $request->first_name,
            'last_name'    => $request->last_name,
            'phone_number'    => $request->phone_number,
            'password' => Hash::make(Str::random(12)),
        ]);

        return redirect()->route('user.list')->with('toast', [
            'type' => 'success',
            'message' => 'User registered successfully'
        ]);

    }

    public function editUserDetailsForm(Request $request, $userId)
    {
        $user = User::findOrFail($userId);        
        return view('users.form', compact('user'));
    }

    public function updateUserDetails(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $email = $request->email;
        $phone_number = $request->phone_number;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $status = $request->status;

        $emailExists = User::where('email', $email)
            ->where('id', '!=', $userId)
            ->exists();

        if ($emailExists) {
            return redirect()->back()
                ->withErrors(['email' => 'This email is already in use by another user'])
                ->withInput();

        }

        $phoneExists = User::where('phone_number', $phone_number)
            ->where('id', '!=', $userId)
            ->exists();

        if ($phoneExists) {
            return redirect()->back()
                ->withErrors(['phone_number' => 'This phone number is already in use by another user'])
                ->withInput();

        }

        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->phone_number = $phone_number;
        $user->status = $status;

        if ($status != Constants::STATUS_ACTIVATED ) {
            PersonalAccessToken::where('tokenable_id', $user->id)
                ->where('tokenable_type', get_class($user))
                ->delete();
        }

        $user->save();

        return redirect()->route('user.list')->with('toast', [
            'type' => 'success',
            'message' => 'User updated successfully'
        ]);
    }

    public function showRegistrationForm(Request $request) {
        $route = $request->route()->getName();
        $previousUrl = URL::previous();
        $registrationData = $request->session()->get($this->clientSessionKey, []);

        $routes = ['personal', 'business', 'identity', 'complete'];

        // $isComingFromLaterStep = $previousUrl && array_filter($routes, function($r) use ($previousUrl) {
        //     return str_contains($previousUrl, $r);
        // });
        
        // if (!$isComingFromLaterStep) {
        //     $request->session()->forget($this->clientSessionKey);
        // }


        if (str_contains($route, 'business')) {
            if (empty($registrationData['personal']) || empty($registrationData['userAddress'])) {
                return redirect()->route('seller.register.personal');
            }
        }

        if (str_contains($route, 'identity')) {
            if (empty($registrationData['business']) || empty($registrationData['businessAddress'])) {
                return redirect()->route('seller.register.business');
            }
        }

        if (str_contains($route, 'complete')) {
            if (empty($registrationData['identity'])) {
                return redirect()->route('seller.register.business');
            }
        }

        return view('forms.sellerRegistration.index');
    }

    public function storePersonalDetails(Request $request)
    {
        $personal = [
            'first_name' => $request->first_name,
            'last_name' =>  $request->last_name,
            'email' =>  $request->email,
            'phone_number' =>  $request->phone_number,
        ];

        $userAddress = [
            'type' => $request->address_type,
            'line1' => $request->address_line1,
            'line2' => $request->address_line2,
            'city' => $request->address_city,
            'state' => $request->address_state,
            'postal_code' => $request->address_code,
            'country' => $request->address_country,
        ];
        
        $request->session()->put($this->clientSessionKey.'.personal', $personal);
        $request->session()->put($this->clientSessionKey.'.userAddress', $userAddress);
        
        return redirect()->route('seller.register.business');
    }

    public function storeBusiness(Request $request)
    {
        $business = [
            'business_name' => $request->business_name,
            'business_type' =>  $request->business_type,
            'business_email' => $request->business_email,
            'business_mobile' => $request->business_mobile,
            'gst_num' =>  $request->gst_num,
        ];

        $businessAddress = [
            'type' => $request->address_type,
            'line1' => $request->address_line1,
            'line2' => $request->address_line2,
            'city' => $request->address_city,
            'state' => $request->address_state,
            'postal_code' => $request->address_code,
            'country' => $request->address_country,
        ];
        
        $request->session()->put($this->clientSessionKey.'.business', $business);
        $request->session()->put($this->clientSessionKey.'.businessAddress', $businessAddress);

        return redirect()->route('seller.register.identity');
    }
    
    public function storeIdentity(Request $request)
    {
        $registrationData = $request->session()->get($this->clientSessionKey);

        $identity = [
            'pan_number' => $request->pan_number,
            'id_type' => $request->id_type,
            'id_number' => $request->id_number
        ];

        // // Handle file uploads
        if ($request->hasFile('id_front')) {
            $identity['id_front'] = $request->file('id_front')->store('identity_proofs');
        }
        
        if ($request->hasFile('id_back')) {
            $identity['id_back'] = $request->file('id_back')->store('identity_proofs');
        }

        if ($request->hasFile('pan_front')) {
            $identity['pan_front'] = $request->file('pan_front')->store('identity_proofs');
        }

        if ($request->hasFile('pan_back')) {
            $identity['pan_back'] = $request->file('pan_back')->store('identity_proofs');
        }
        
        $request->session()->put($this->clientSessionKey.'.identity', $identity);
        
        return redirect()->route('seller.register.complete');
    }
    
    // Complete registration
    public function completeRegistration(Request $request)
    {
        $registrationData = $request->session()->get($this->clientSessionKey);
        
        $password = Str::random(12);
        
        $user = User::create([
            'first_name' => $registrationData['personal']['first_name'],
            'last_name' => $registrationData['personal']['last_name'],
            'email' => $registrationData['personal']['email'],
            'phone_number' => $registrationData['personal']['phone_number'],
            'password' => Hash::make($password),
        ]);
        
        $seller = Seller::create([
            'user_id' => $user->id,
            'business_name' => $registrationData['business']['business_name'],
            'business_type' => $registrationData['business']['business_type'],
            'business_email' => $registrationData['business']['business_email'],
            'business_mobile' => $registrationData['business']['business_mobile'],
            'gst_num' => $registrationData['business']['gst_num'],
        ]);


        $userAddress = Address::create([
            'user_id' => $user->id,
            'type' => $registrationData['userAddress']['type'],
            'line1' => $registrationData['userAddress']['line1'],
            'line2' => $registrationData['userAddress']['line2'],
            'city' => $registrationData['userAddress']['city'],
            'state' => $registrationData['userAddress']['state'],
            'postal_code' => $registrationData['userAddress']['postal_code'],
            'country' => $registrationData['userAddress']['country'],
        ]);

        $businessAddress = Address::create([
            'user_id' => $user->id,
            'type' => $registrationData['businessAddress']['type'],
            'line1' => $registrationData['businessAddress']['line1'],
            'line2' => $registrationData['businessAddress']['line2'],
            'city' => $registrationData['businessAddress']['city'],
            'state' => $registrationData['businessAddress']['state'],
            'postal_code' => $registrationData['businessAddress']['postal_code'],
            'country' => $registrationData['businessAddress']['country'],
        ]);

        $identity = IdentityProof::create([
            'user_id' => $user->id,
            'seller_id' => $seller->id,
            'pan_number' => $registrationData['identity']['pan_number'],
            'pan_front_path' => $registrationData['identity']['pan_front'],
            'pan_back_path' => $registrationData['identity']['pan_back'],
            'id_type' => $registrationData['identity']['id_type'],
            'id_number' => $registrationData['identity']['id_number'],
            'id_front_path' => $registrationData['identity']['id_front'],
            'id_back_path' => $registrationData['identity']['id_back'] ?? null,
        ]);
        
        $request->session()->forget($this->clientSessionKey);
        return redirect()->route('dashboard');
    }

    public function fetchSellerList(Request $request)
    {
        $limit = $request->input('limit', 10);
        $search = $request->input('search');
        $status = $request->input('status');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        if (empty($sortBy)) {
            $sortBy = 'created_at';
        }

        $query = Seller::with('userDetails');

        if ($search) {
            $query->where(function ($q) use ($search) {

            $q->where('business_name', 'like', "%{$search}%")
                ->orWhere('business_email', 'like', "%{$search}%")
                ->orWhere('business_mobile', 'like', "%{$search}%")

                ->orWhereHas('userDetails', function ($userQuery) use ($search) {
                    $userQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                });
            });
        }
        
        if ($status) {
            $query->where('status', $status);
        }

        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'desc';
    
        $query->orderBy($sortBy, $sortOrder);
        $sellers = $query->paginate($limit);
        return view('sellers.list', compact('sellers', 'limit'));
    }

    public function editSellerDetailsForm(Request $request, $sellerId)
    {
        $seller = Seller::findOrFail($sellerId);        
        return view('sellers.edit', compact('seller'));
    }

    public function updateSellerDetails(Request $request, $sellerId)
    {
        $seller = Seller::findOrFail($sellerId);
        $user = User::findOrFail($seller->user_id);
        $sellerRole = Role::where('name', Constants::SELLER_ROLE)->first();

        $emailExists = User::where('email', $request->email)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($emailExists) {
            return redirect()->back()
                ->withErrors(['email' => 'This email is already in use by another user'])
                ->withInput();

        }

        $phoneExists = User::where('phone_number', $request->phone_number)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($phoneExists) {
            return redirect()->back()
                ->withErrors(['phone_number' => 'This phone number is already in use by another user'])
                ->withInput();

        }

        $businessEmailExists = Seller::where('business_email', $request->business_email)
            ->where('id', '!=', $seller->id)
            ->exists();

        if ($businessEmailExists) {
            return redirect()->back()
                ->withErrors(['business_email' => 'This email is already in use by another seller'])
                ->withInput();

        }

        $businessPhoneExists = Seller::where('business_mobile', $request->business_mobile)
            ->where('id', '!=', $seller->id)
            ->exists();

        if ($businessPhoneExists) {
            return redirect()->back()
                ->withErrors(['phone_number' => 'This phone number is already in use by another user'])
                ->withInput();

        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->save();

        $seller->business_name = $request->business_name;
        $seller->business_type = $request->business_type;
        $seller->business_email = $request->business_email;
        $seller->business_mobile = $request->business_mobile;
        $seller->gst_num = $request->gst_num;
        $seller->status = $request->status;
        $seller->save();

        if ($request->status == Constants::STATUS_APPROVED ) {
            $user->role_id = $sellerRole->id;
            $user->save();
        }

        $existingAddressIds = $user->addresses()->pluck('id')->toArray();
        $updatedAddressIds = [];

        foreach ($request->addresses as $addressKey => $addressData) {
            $address = Address::updateOrCreate(
                [
                    'id' => $addressData['address_id'] != 'null' ? $addressData['address_id'] : null,
                    'user_id' => $user->id
                ],
                [
                    'type' => $addressData['address_type'],
                    'line1' => $addressData['line1'],
                    'line2' => $addressData['line2'] ?? null,
                    'city' => $addressData['city'],
                    'state' => $addressData['state'],
                    'postal_code' => $addressData['postal_code'],
                    'country' => $addressData['country'],
                    'user_id' => $user->id
                ]
            );
            $updatedAddressIds[] = $address->id;
        }

        if (!empty($updatedAddressIds)) {
            $addressesToDelete = array_diff($existingAddressIds, $updatedAddressIds);
            if (!empty($addressesToDelete)) {
                Address::whereIn('id', $addressesToDelete)->delete();
            }
        }

        $identityProof = IdentityProof::where('seller_id', $seller->id)->first();

        $identityProof->pan_number = $request->pan_number;
        $identityProof->pan_verify_status = $request->pan_verify_status;
        $identityProof->id_type = $request->id_type;
        $identityProof->id_number = $request->id_number;
        $identityProof->id_verify_status = $request->id_verify_status;

        if ($request->hasFile('pan_front_path')) {
            if ($identityProof->pan_front_path && Storage::exists($identityProof->pan_front_path)) {
                Storage::delete($identityProof->pan_front_path);
            }
            $img_path = $request->file('pan_front_path')->store('identity_proofs');
            $identityProof->pan_front_path = $img_path;
        }

        if ($request->hasFile('pan_back_path')) {
            if ($identityProof->pan_back_path && Storage::exists($identityProof->pan_back_path)) {
                Storage::delete($identityProof->pan_back_path);
            }
            $img_path = $request->file('pan_back_path')->store('identity_proofs');
            $identityProof->pan_back_path = $img_path;
        }

        if ($request->hasFile('id_front_path')) {
            if ($identityProof->id_front_path && Storage::exists($identityProof->id_front_path)) {
                Storage::delete($identityProof->id_front_path);
            }
            $img_path = $request->file('id_front_path')->store('identity_proofs');
            $identityProof->id_front_path = $img_path;
        }

        if ($request->hasFile('id_back_path')) {
            if ($identityProof->id_back_path && Storage::exists($identityProof->id_back_path)) {
                Storage::delete($identityProof->id_back_path);
            }
            $img_path = $request->file('id_back_path')->store('identity_proofs');
            $identityProof->id_back_path = $img_path;
        }

        $identityProof->save();

        return redirect()->route('seller.list')->with('toast', [
            'type' => 'success',
            'message' => 'Seller details updated successfully'
        ]);
    }
}