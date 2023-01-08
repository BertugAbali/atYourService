<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use App\Models\Service_Areas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $services = Service::all();
        $service_areas = Service_Areas::all();
        return view('home', compact('services'), compact('service_areas'));
    }

    public function area($area)
    {
        $services = Service::where('area', $area)->get();

        $service_areas = Service_Areas::all();
        return view('home', compact('services'), compact('service_areas'));
    }

    public function userProfile($user_id)
    {
        $user = new User();
        $user = DB::table('users')->where('id', $user_id)->first();

        return view('userProfile', compact('user'));
    }
    
    public function profile()
    {
        return view('profile');
    }

    public function becomeProvider()
    {
        $areas = Service_Areas::all();
        return view('becomeProvider', compact('areas'));
    }

    public function startNewService()
    {
        return view('startNewService');
    }
}
