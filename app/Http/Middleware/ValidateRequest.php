<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\ValidationSchemas\Schemas;
use Illuminate\Validation\Rule;

class ValidateRequest
{

    public function handle(Request $request, Closure $next, $key=null )
    {
        $schemas =  Schemas::get();
        $schema = $schemas[$key] ?? null;

        if (!$key || !isset($schemas[$key])) {
            return $next($request);
        }

        if (isset($schema['isUniqueMobileExceptUsers']) && $schema['isUniqueMobileExceptUsers'] && $request->user()) {
            unset($schema['isUniqueMobileExceptUsers']);
            $mobileRule = $schema['phone_number'] ?? [];

            if (is_string($mobileRule)) {
                $mobileRule = explode('|', $mobileRule);
            }

            $mobileRule[] = Rule::unique('users', 'phone_number')->ignore($request->user()->id, '_id');
            $schema['phone_number'] = $mobileRule;
        }

        if (isset($schema['isUniqueEmailExceptUsers']) && $schema['isUniqueEmailExceptUsers'] && $request->user()) {
            unset($schema['isUniqueEmailExceptUsers']);
            $emailRule = $schema['email'] ?? [];

            if (is_string($emailRule)) {
                $emailRule = explode('|', $emailRule);
            }

            $emailRule[] = Rule::unique('users', 'email')->ignore($request->user()->id, '_id');
            $schema['email'] = $emailRule;
        }

        $validator = Validator::make($request->all(), $schema, $schemas['errorMessages']);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                // API response
                $errors = collect($validator->errors())->map(function ($errorMessages, $field) {
                    return $errorMessages[0];
                });
                
                return response()->json([
                    'errors' => $errors,
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        return $next($request);
    }
}
