<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function userDashboard()
    {
        return view('pages.user.dashboard');
    }

    public function adminDashboard()
    {
        return view('pages.admin.dashboard');
    }

    public function managerDashboard()
    {
        return view('pages.manager.dashboard');
    }
}
