<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use stdClass;

class HomeController extends Controller
{
   
  

    // It will return all services and service areas to home page.

    public function index()
    {
        $services = Service::paginate(8);
        return view('home', compact('services'));
    }

    // It will return some services based on your chosed service area and all service areas to home page.

    public function area($area)
    {
        $services = Service::where('area', $area)->paginate(8);

        return view('home', compact('services','area') );
    }

   
}
