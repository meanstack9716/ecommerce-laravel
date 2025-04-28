<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
        $registrationData = $request->session()->get($this->clientSessionKey, []);

        if (str_contains($route, 'business')) {
            if (empty($registrationData['personal']) || empty($registrationData['address1'])) {
                return redirect()->route('seller.register.personal');
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

        $address1 = [
            'line1' => $request->address1_line1,
            'line2' => $request->address1_line2,
            'city' => $request->address1_city,
            'state' => $request->address1_state,
            'postal_code' => $request->address1_code,
            'country' => $request->address1_country,
        ];
        
        $request->session()->put($this->clientSessionKey.'.personal', $personal);
        $request->session()->put($this->clientSessionKey.'.address1', $address1);
        
        return redirect()->route('seller.register.business');
    }

    public function storeBusiness(Request $request)
    {
        // $businesss = [
        //     'business_name' => $request->business_name,
        //     'business_type' =>  $request->business_type,
        //     'gst_in' =>  $request->gst_in,
        //     'business_address' =>  $request->business_address,
        // ];
        
        // $request->session()->put($this->clientSessionKey.'.business', $business);
        
        return redirect()->route('seller.register.identity');
    }
    
    // Store identity proof
    public function storeIdentity(Request $request)
    {
        // $identity = $request->validate([
        //     'id_type' => 'required|string|in:passport,driving_license,national_id',
        //     'id_number' => 'required|string|max:255',
        //     'id_front' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        //     'id_back' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        // ]);

        // $identity = [
        //     'id_type' => $request->id_type,
        //     'id_number' => $request->id_number,
        // ];
        
        // // Handle file uploads
        // if ($request->hasFile('id_front')) {
        //     $validated['id_front'] = $request->file('id_front')->store('identity_proofs');
        // }
        
        // if ($request->hasFile('id_back')) {
        //     $validated['id_back'] = $request->file('id_back')->store('identity_proofs');
        // }
        
        // $request->session()->put($this->clientSessionKey.'.identity', $identity);
        
        return redirect()->route('seller.register.complete');
    }
    
    // Complete registration
    public function completeRegistration(Request $request)
    {
        // $registrationData = $request->session()->get($this->clientSessionKey);
        
        // // Create user
        // $user = User::create([
        //     'first_name' => $registrationData['personal']['first_name'],
        //     'last_name' => $registrationData['personal']['last_name'],
        //     'email' => $registrationData['personal']['email'],
        //     'phone' => $registrationData['personal']['phone'],
        //     'address' => $registrationData['personal']['address'],
        //     'password' => Hash::make(Str::random(12)), // Generate random password
        // ]);
        
        // // Create business details
        // $business = BusinessDetail::create([
        //     'user_id' => $user->id,
        //     'business_name' => $registrationData['business']['business_name'],
        //     'business_type' => $registrationData['business']['business_type'],
        //     'tax_id' => $registrationData['business']['tax_id'],
        //     'business_address' => $registrationData['business']['business_address'],
        // ]);
        
        // // Create identity proof
        // $identity = IdentityProof::create([
        //     'user_id' => $user->id,
        //     'id_type' => $registrationData['identity']['id_type'],
        //     'id_number' => $registrationData['identity']['id_number'],
        //     'id_front_path' => $registrationData['identity']['id_front'],
        //     'id_back_path' => $registrationData['identity']['id_back'] ?? null,
        // ]);
        
        // // Clear session data
        // $request->session()->forget($this->clientSessionKey);
        
        return redirect()->route('dashboard')->with('success', 'Registration completed successfully!');
    }
}