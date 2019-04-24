<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    
    public function index()
    {
        return User::latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name'=> 'required',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:5'
        ]);


        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'bio' => $request['bio'],
            'type' => $request['type'],
            'photo' => $request['photo'],
            'password' => Hash::make($request['password'])
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function profile()
    {
        return auth('api')->user();
    }

    public function updateProfile(Request $request)
    {
        $user =  auth('api')->user();

         
        $this->validate($request, [
            'name'=> 'required',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|string|min:5'
        ]);
    

        $currentPhoto = $user->photo;

        if ($request->photo != $currentPhoto  ) {
            //name the image
            $name = time().'.'. explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            //save the image
            \Image::make($request->photo)->save(public_path('img/profile/'). $name);

            //change the value of profile photo before updating the profile
            $request->merge(['photo' => $name ]);
           // $request->photo = $name;

           $userPhoto = public_path('img/profile/').$currentPhoto;

           if(file_exists($userPhoto)){
               @unlink($userPhoto);
           }


        } 

        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);

        }

        $user->update($request->all());

        return ['message' => 'success'];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $user = User::findOrFail($id);

      
      $this->validate($request, [
        'name'=> 'sometimes',
        'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
        'password' => 'sometimes|string|min:5'
    ]);


      $user->update($request->all());

      return ['message' => 'Updates User Information'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return ['message' => 'user deleted'];

    }
}
