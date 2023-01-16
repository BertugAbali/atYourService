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
        $this->middleware(['auth', 'verified']);
    }

    // It will return all services and service areas to home page.

    public function index()
    {
        $services = Service::paginate(8);
        $service_areas = Service_Areas::all();
        return view('home', compact('services'), compact('service_areas'));
    }

    // It will return some services based on your chosed service area and all service areas to home page.

    public function area($area)
    {
        $services = Service::where('area', $area)->paginate(8);

        $service_areas = Service_Areas::all();
        return view('home', compact('services'), compact('area'));
    }

    // It will return chosed user profile.

    public function userProfile($user_id)
    {
        $user = new User();
        $user = DB::table('users')->where('id', $user_id)->first();

        return view('userProfile', compact('user'));
    }

    // It will return page of becoming service provider page.

    public function becomeProvider()
    {
        $areas = Service_Areas::all();
        return view('becomeProvider', compact('areas'));
    }

     // It will return page of user edit page.

     public function editUser()
     {
         return view('editUser');
     }

    public function createService()
    {
        return view('startNewService');
    }

    public function showServices(User $user)
    {
        $services = Service::where('owner_id', $user->id)->paginate(20);
        return view('showServices',compact('services'));
    }
}
