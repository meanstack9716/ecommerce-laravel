<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Seller;
use App\Models\Address;
use App\Models\Role;
use App\Models\IdentityProof;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private $clientSessionKey = 'client_registration_data';

    public function fetchUserList(Request $request) 
    {
        $limit = $request->input('limit', 10);
        $search = $request->input('search');
        $roleId = $request->input('role');

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

        $users = $query->paginate($limit);
        $roles = Role::all(); 
        return view('users.list', compact('users', 'limit', 'roles'));
    }

    public function showRegistrationForm(Request $request) {
        $route = $request->route()->getName();
        $referer = $request->headers->get('referer');
        $registrationData = $request->session()->get($this->clientSessionKey, []);

        $routes = ['personal', 'business', 'identity', 'complete'];

        $isComingFromLaterStep = $referer && array_filter($routes, function($r) use ($referer) {
            return str_contains($referer, $r);
        });
        
        if (!$isComingFromLaterStep) {
            $request->session()->forget($this->clientSessionKey);
        }


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

        $query = Seller::query()
            ->with(['userDetails']);
        $sellers = $query->paginate($limit);
        return view('sellers.list', compact('sellers', 'limit'));
    }
}