<?php

namespace App\Http\Controllers;
use App\Models\User;


use Illuminate\Http\Request;

class UserController extends Controller
{
   
    public function show(String $user_id)
    {
        $user = new User();

        $user = User::where('id', $user_id)->get();
       
        return view('profile', ['user' => $user]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        // $student = new Student;
        // $student->firstName = $request->firstName;
        // $student->lastName = $request->lastName;

        // $student->save();


        $request->validate([
            'firstName' => 'required|min:5|max:20',
            'lastName' => 'required|min:5|max:20',
            'email' => 'required|min:5',
            'email' => 'required',
            'password' => 'required|min:5',
        ]);

        $create=User::create($request->all());

        if ($create){
            return redirect('/users/profile');

        }else{
            return view('users.create');
        }
        

    //    return view('users.profile', ['user' => $request]);
    }

    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        return redirect('profile');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect('/');
    }
}


