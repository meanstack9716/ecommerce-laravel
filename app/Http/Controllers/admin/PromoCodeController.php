<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use App\Constants\Constants;
use App\Models\PromoCode;
use App\Models\Product;
use App\Models\Seller;

class PromoCodeController extends Controller
{
    public function showAddNewPromoCodeForm(Request $request)
    {
        $products = Product::all();
        return view('promo.form', compact('products'));
    }

    public function createNewPromoCodeForm(Request $request)
    {
        $seller = Seller::where('user_id', $request->user()->id)->first();
        $promo = [
            'code' => $request->code,
            'discount_type' => $request->discount_type,
            'discount_value' => (float)$request->discount_value,
            'max_discount_amount' => $request->discount_type == 'percentage' && $request->max_discount_amount ? (float)$request->max_discount_amount : null,
            'min_order_amount' => $request->min_order_amount ? (float)$request->min_order_amount : null,
            'start_date' => Carbon::createFromFormat('Y-m-d\TH:i', $request->start_date),
            'expiry_date' => $request->expiry_date ? Carbon::createFromFormat('Y-m-d\TH:i', $request->expiry_date) : null,
            'max_uses' => $request->max_uses ? (float)$request->max_uses : null,
            'uses_per_user' => (float)$request->uses_per_user,
            'description' => $request->description,
            'applicable_to' => $request->applicable_to,
            'used_count' => 0,
            'applicable_products' => $request->applicable_to == 'all' ? null : $request->products,
            'created_by' => $seller->id,
            'is_active' => $request->is_active == 1 ? true : false,  
            'only_first_order' => $request->only_first_order == 1 ? true : false,  
        ];
        PromoCode::create($promo);

        return redirect()->route('promo-code.list');
    }

    public function getAllPromoCodeList(Request $request) 
    {
        $user = $request->user();
        $limit = $request->input('limit', 10);
        $sellerId = $request->input('sellerId');
        $sellers =  Seller::where('status', Constants::STATUS_APPROVED )->get();

        $query = PromoCode::query()->orderBy('created_at', 'desc');

        if (!$user->is_admin) {
            $query->where('created_by', $user->sellerDetails->id);
        }

        if($sellerId) {
            $query->where('created_by', $sellerId);
        }

        $codes = $query->paginate($limit);
        return view('promo.list', compact('codes', 'limit', 'sellers'));

    }

    public function editPromoCodeDetails(Request $request, $codeId)
    {
        $promocode = PromoCode::findOrFail($codeId);
        $products = Product::all();
        return view('promo.form', compact('promocode', 'products'));
    }

    public function updatePromoCode(Request $request, $codeId)
    {
        $promocode = PromoCode::findOrFail($codeId);

        if ($request->code !== $promocode->code) {
            $existingCode = PromoCode::where('code', $request->code)->where('id', '!=', $codeId)->first();
            if ($existingCode) {
                return redirect()->back()
                    ->withErrors(['code' => 'The promo code already exists!'])
                    ->withInput();
            }
        }

        $updatedPromo = [
            'code' => $request->code,
            'discount_type' => $request->discount_type,
            'discount_value' => (float)$request->discount_value,
            'max_discount_amount' => $request->discount_type == 'percentage' && $request->max_discount_amount ? (float)$request->max_discount_amount : null,
            'min_order_amount' => $request->min_order_amount ? (float)$request->min_order_amount : null,
            'start_date' => Carbon::createFromFormat('Y-m-d\TH:i', $request->start_date),
            'expiry_date' => $request->expiry_date ? Carbon::createFromFormat('Y-m-d\TH:i', $request->expiry_date) : null,
            'max_uses' => $request->max_uses ? (float)$request->max_uses : null,
            'uses_per_user' => (float)$request->uses_per_user,
            'description' => $request->description,
            'applicable_to' => $request->applicable_to,
            'used_count' => 0,
            'applicable_products' => $request->applicable_to == 'all' ? null : $request->products,
            'created_by' => $promocode->created_by,
            'is_active' => $request->is_active == 1 ? true : false,  
            'only_first_order' => $request->only_first_order == 1 ? true : false,  
        ];

        try {
            $promocode->update($updatedPromo);
            return redirect()->route('promo-code.list')
                ->with('success', 'Promo code updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['code' => 'Failed to update promo code: ' . $e->getMessage()])
                ->withInput();
        }

    }

}