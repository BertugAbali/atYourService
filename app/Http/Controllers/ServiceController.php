<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;

use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'detail' => ['required', 'string', 'max:1000'],
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

        $user = new User();
        $user = DB::table('users')->where('id', $service->owner_id)->first();

        $user->owned_services = $user->owned_services . $service->id . ',' ;

        $affected = DB::table('users')
              ->where('id', $user->id)
              ->update(['owned_services' => $user->owned_services]);

        return redirect('profile');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {

        return view('showService', ['service' => $service]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        //
    }
}
