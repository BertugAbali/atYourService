<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;


class ServiceController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'title' => ['required', 'string', 'max:255'],
            'detail' => ['required', 'string', 'max:2000'],
            'price' => ['required', 'integer', 'min:1'],
        ]);

        $request->file('image')->store('public/images');


        $service = new Service();
        $service->title = $request->title;
        $service->detail = $request->detail;
        $service->price = $request->price;
        $service->area = $request->area;
        $service->owner_id = Auth::user()->id;
        $service->path = $request->file('image')->hashName();
        $service->save();
        
        $services = Service::where('owner_id', Auth::user()->id)->paginate(20);
        return view('showServices', compact('services'));
    }

    public function show(Service $service)
    {
        $user= User::find($service->owner_id);
        return view('showService', ['service' => $service,'ownerName' => $user->name]);
    }

    public function delete(Service $service)
    {
        if(file_exists(public_path() . '/storage/images/' .$service->path)){
            unlink(public_path() . '/storage/images/' .$service->path);
        }
        $service->delete();
        $services = Service::where('owner_id',Auth::user()->id)->paginate(20);
        return view('showServices',compact('services'));
    }

     // It will return page of creating new service page.

     public function createService()
     {
         return view('startNewService');
     }

}
