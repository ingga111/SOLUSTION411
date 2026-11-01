<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\FlashSale;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the digital store catalog homepage.
     */
    public function index(Request $request): View
    {
        $categories = Category::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->with(['products' => function ($query) {
                $query->where('is_active', true)
                    ->with(['variants' => fn ($v) => $v->where('is_active', true)->orderBy('price')])
                    ->orderBy('sort_order');
            }])
            ->get();

        $banners = Banner::active()->orderBy('sort_order')->latest('id')->get();

        $flashSale = FlashSale::running()->latest()->first();

        return view('welcome', compact('categories', 'banners', 'flashSale'));
    }
}
