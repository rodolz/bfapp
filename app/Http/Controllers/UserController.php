<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Image;
use Auth;


class UserController extends Controller
{
    public function perfil(){
    	return view('user.perfil');
    }

    public function update_avatar(Request $request){
		$customMessages = [
                'avatar.required' => 'Debe seleccionar una foto',
        ];	
		$this->validate($request, [
            'avatar' => 'required',
        ], $customMessages);

    	if($request->hasFile('avatar')){
    		$user = Auth::user();

    		$avatar = $request->file('avatar');
            $filename  = time() . '.' . $avatar->getClientOriginalExtension();
            $path = 'uploads/avatars/' . $filename;
            // if (!file_exists($path)) {
            //     mkdir($path, 666, true);
            // }
            Image::make($avatar->getRealPath())->resize(300, 300)->save($path);
            $user->avatar = $filename;
            $user->save();                  
    		// $filename = $user->id . '.' . $avatar->getClientOriginalExtension();
    		// Image::make($avatar)->resize(300, 300)->save(public_path('/uploads/avatars/'. $filename));

    		// $user->avatar = $filename;
    		// $user->save();
    	}
    	return redirect()->back()->with('success','Nuevo avatar cargado!');
    }

}