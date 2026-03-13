<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member;
use App\Models\Product;
use App\Models\Order;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get current user from session
        $user = \Illuminate\Support\Facades\Session::get('user');

        // Get statistics

        $totalUsers = User::count();
        $totalMembers = Member::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalArticles = Article::count();
        $totalCategories = Category::count();

        // Get recent data
        $recentUsers = User::latest()->take(5)->get();
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $recentArticles = Article::latest()->take(5)->get();

        // Get order statistics by status
        $orderStats = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.dashboard.index', compact(
            'user',
            'totalUsers',
            'totalMembers',
            'totalProducts',
            'totalOrders',
            'totalArticles',
            'totalCategories',
            'recentUsers',
            'recentOrders',
            'recentArticles',
            'orderStats'
        ));

    }
}
