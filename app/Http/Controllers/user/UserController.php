<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RewardPoint;
use App\Models\Address;
use App\Enums\AddressType;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Storage;
use App\Constants\Constants;

class UserController extends Controller
{
    public function fetchCurrentUser(Request $request) 
    {
        return response()->json([
            'data' => $request->user()
        ]);
    }

    public function updateUserDetails(Request $request) 
    {
        $user = $request->user();
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
        ]);       
        return response()->json([
            'data' => $user->fresh()
        ]);
    }

    public function updateProfilePic(Request $request) 
    {
        $user = $request->user();
        if ($user->profile_path) {
            Storage::delete($user->profile_path);
        }

        $img_path = $request->file('image')->store('profile-pic');
    
        $user->update([
            'profile_path' => $img_path,
        ]);
        
        return response()->json([
            'message' => 'Your profile image has been updated',
            'data' => $user->fresh()
        ]);
    }

    public function deleteProfilePic(Request $request) 
    {
        $user = $request->user();
        if ($user->profile_id) {
            Storage::delete($user->profile_path);
            $user->update([
                'profile_path' => null,
            ]);
        }
        return response()->json([
            'message' => 'Your profile image has been deleted',
            'data' => $user->fresh()
        ]);
    }

    public function fetchAddressTypeList()
    {
        return response()->json([
            'data' => AddressType::values()
        ]);
    }

    public function addNewAddress(Request $request)
    {
        $userId = $request->user()->id;

        $isFirstAddress = Address::where('user_id', $userId)->count() === 0;

        $isPrimary = $request->has('is_primary') 
            ? $request->is_primary 
            : $isFirstAddress;
    
        if ($isPrimary) {
            Address::where('user_id', $userId)->update(['is_primary' => false]);
        }

        Address::create([
            'user_id' => $userId,
            "contact_name" => $request->contact_name,
            "contact_number" => $request->contact_number,
            'type' => $request->type,
            'line1' => $request->line1,
            'line2' => $request->line2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'is_primary' => $isPrimary,
        ]);

        return response()->json([
            'message' => 'Address added successfully.',
        ]);
    }

    public function fetchUserAddressList(Request $request)
    {
        $userId = $request->user()->id;
        $addresses = Address::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'data' =>$addresses,
        ]);
    }

    public function deleteAddress(Request $request, $id)
    {
        $userId = $request->user()->id;
        $address = Address::where('id', $id)->where('user_id', $userId)->first();

        if (!$address) {
            return response()->json(['message' => 'Address not found.'], 404);
        }
        $wasPrimary = $address->is_primary;

        $address->delete();

        if ($wasPrimary) {
            $newPrimary = Address::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        return response()->json(['message' => 'Address deleted successfully.']);
    }

    public function updateUserAddress(Request $request)
    {
        $userId = $request->user()->id;
        $address = Address::where('id', $request->address_id)->where('user_id', $userId)->first();

        $fields = collect([
            'type' => $request->input('type'),
            'contact_name' => $request->input('contact_name'),
            'contact_number' => $request->input('contact_number'),
            'line1' => $request->input('line1'),
            'line2' => $request->input('line2'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'postal_code' => $request->input('postal_code'),
            'country' => $request->input('country'),
            'is_primary' => $request->input('is_primary'),
        ])->filter(function ($value) {
            return !is_null($value);
        })->toArray();

        if (isset($fields['is_primary']) && $fields['is_primary']) {
            Address::where('user_id', $userId)
               ->where('id', '!=', $request->address_id)
               ->update(['is_primary' => false]);
        }

        $address->update($fields);

        return response()->json(['message' => 'Address updated successfully.']);
    }

    public function fetchCurrentAvailablePoints(Request $request)
    {
        $userId = $request->user()->id;

        // Sum of valid earned points
        $totalEarned = RewardPoint::where('user_id', $userId)
            ->where('points', '>', 0)
            ->sum('points');

        // Sum of all redeemed points (negative points)
        $redeemed = RewardPoint::where('user_id', $userId)
            ->where('points', '<', 0)
            ->sum('points'); // will be negative

        $available = $totalEarned + $redeemed;

        return response()->json([
            'available_points' => max(0, $available), // Prevent negative balance
            'total_earned' => $totalEarned,
            'total_redeemed' => abs($redeemed),
        ]);
    }

    public function fetchRewardPointsHistory(Request $request)
    {
        $userId = $request->user()->id;
        $limit = $request->input('limit', Constants::REWARD_HISTORY_DEFAULT_LIMIT);
        $page = $request->input('page', 1);

        // Optional filters
        $fromDate = $request->input('from_date'); // format: YYYY-MM-DD
        $toDate = $request->input('to_date');     // format: YYYY-MM-DD

        $query = RewardPoint::where('user_id', $userId)->orderBy('created_at', 'desc');

        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        $history = $query->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'data' => $history->items(),
            'total_items' => $history->total(),
            'per_page' => $history->perPage(),
            'current_page' => $history->currentPage(),
            'last_page' => $history->lastPage(),
        ]);
    }
}