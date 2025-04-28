<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
        $user->update($request->all());        
        return response()->json([
            'data' => $user->fresh()
        ]);
    }

    public function updateProfilePic(Request $request) 
    {
        $user = $request->user();
        if ($user->profile_id) {
            Cloudinary::uploadApi()->destroy($user->profile_id);
        }
        $uploadedFile = $request->file('image');
    
        $result = $result = Cloudinary::uploadApi()->upload($uploadedFile->getRealPath(), [
            'folder' => 'profile-pictures'
        ]);
        $user->update([
            'profile_pic' => $result['secure_url'],
            'profile_id' => $result['public_id']
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
            Cloudinary::uploadApi()->destroy($user->profile_id);
            $user->update([
                'profile_pic' => null,
                'profile_id' => null
            ]);
        }
        return response()->json([
            'message' => 'Your profile image has been deleted',
            'data' => $user->fresh()
        ]);
    }
}