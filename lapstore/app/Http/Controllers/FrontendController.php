<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontendController extends Controller
{

    public function index()
    {
        //session()->forget('favourite');
        //session()->forget('cart');

        // "https://res.cloudinary.com/ddrivhxfq/image/upload/f_auto,q_auto/qzrvdo3bxb4yd0bigs1u",
        // "https://res.cloudinary.com/ddrivhxfq/image/upload/f_auto,q_auto/rhaojzxt0rbkbjtaw7sl",
        $recommended = Product::with('category', 'images')->where('recommended', true)->latest()->get();
        $categories = Category::get();
        // dd($recommended[1]->images[0]->image_url);
        $slidder_images = [
            "https://res.cloudinary.com/ddrivhxfq/image/upload/f_auto,q_auto/k3puaksoohradi1bfvrm",
            "https://res.cloudinary.com/ddrivhxfq/image/upload/f_auto,q_auto/rutkip9kllbmf7mgvqp4",
            "https://res.cloudinary.com/ddrivhxfq/image/upload/f_auto,q_auto/kamnkc10tzwjngjo00lh",
        ];
        return view('home', [
            "slidder_images" => $slidder_images,
            'recommended_products' => $recommended,
            'categories' => $categories
        ]);
    }
}