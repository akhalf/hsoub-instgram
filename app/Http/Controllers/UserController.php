<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //view edit page
        $user = User::find(Auth()->id());
        return view('auth.user_profile', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function update(Request $request, $id)
    public function update(Request $request)
    {
        //
        $img_name='';
        if ($request->hasFile('filename')){
            $file = $request->file('filename');
            $img_name = auth()->id().'-'.time().'-'.$file->getClientOriginalName();
            $file->move(public_path().'/images/avatar',$img_name);
        }
        $user = User::find(Auth()->id());
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->birth_date = $request->get('birth_date');
        if (strlen($img_name))
            $user->avatar = $img_name;
        $user->save();
        return $this->edit();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
