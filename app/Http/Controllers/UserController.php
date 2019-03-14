<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Image;
use Auth;
use App\User;
use Alert;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{

    public function index(Request $request)
    {
        $users = User::orderBy('id','DESC')->paginate(10);
        $roles = Role::where('name','!=','Admin')->pluck('name','id');
        return view('users.index',compact('users','roles'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function show($id){
        $user = User::find($id);
    	return view('users.show',compact('user'));
    }

    public function update_avatar(Request $request){
		$this->validate($request, [
            'avatar' => 'required',
        ],
        [
            'avatar.required' => 'Debe seleccionar una imagen',
        ]);

    	if($request->hasFile('avatar')){
            $user = Auth::user();
            //borrar el avatar actual en caso que no sea el default
            if($user->avatar != 'default.jpg'){
                unlink('uploads/avatars/'.$user->avatar);
            }
    		$avatar = $request->file('avatar');
            $filename  = time().'.'.$avatar->getClientOriginalExtension();
            $path = 'uploads/avatars/' . $filename;
            Image::make($avatar->getRealPath())->resize(300,300)->save($path);
            $user->avatar = $filename;
            $user->save();                  
        }
        Alert::success('Foto de Perfil actualizada')->autoclose(1000);
    	return redirect()->back();
    }

    public function destroy($id)
    {
        User::find($id)->delete();

        Alert::success('Usuario Elminado!')->autoclose(1000);

        return redirect()->back();
    }
}