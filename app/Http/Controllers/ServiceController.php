<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;

use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class ServiceController extends Controller
{

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
        $service->owner = $request->owner;
        $service->area = $request->area;
        $service->owner_id = $request->owner_id;
        $service->path = $request->file('image')->hashName();
        $service->save();
        
        return redirect('profile');
    }

    public function show(Service $service)
    {

        return view('showService', ['service' => $service]);
    }

    public function delete(Service $service)
    {
        unlink(public_path() . '/storage/images/' .$service->path);
        $service->delete();
        return view('profile');
    }

}
