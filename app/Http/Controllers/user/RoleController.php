<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;

class RoleController extends Controller
{
    public function fetchRolesList(Request $request) 
    {
        return response()->json([
            'data' => Role::all(),
        ]);
    }
}