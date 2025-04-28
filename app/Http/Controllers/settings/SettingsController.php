<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Faq;

class SettingsController extends Controller
{
    public function fetchFaqList(Request $request) 
    {
        $faqs = Faq::all()
            ->groupBy('category')
            ->map(function ($items, $category) {
                return [
                    'category' => $category,
                    'faqs' => $items->map(function ($item) {
                        return [
                            'question' => $item->question,
                            'answer' => $item->answer
                        ];
                    })
                ];
            })
            ->values();
        return response()->json([
            'data' => $faqs,
        ]);
    }
}