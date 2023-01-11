<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class UserController extends Controller
{
   
    public function show(String $user_id)
    {
        $user = new User();

        $user = User::where('id', $user_id)->get();
       
        return view('profile', ['user' => $user]);
    }

    

    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    // This function update the player as a service provider

    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        return redirect('profile');
    }

    // This functions delete the user and his/her belonged services with their images.

    public function destroy(User $user)
    {
        $services = DB::table('services')->where('owner_id', $user->id)->get()->toArray();
        foreach($services as $service){
            unlink(public_path() . '/storage/images/' .$service->path);
        }
        DB::table('services')->where('owner_id', $user->id)->delete();
        $user->delete();
        return redirect('/');
    }
}


