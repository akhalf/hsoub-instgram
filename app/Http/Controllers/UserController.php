<?php

namespace App\Http\Controllers;

use App\Follower;
use App\Like;
use App\Post;
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
        $users = User::where('id', '!=', auth()->id())->get();
        $requests=Follower::with('to_user')->where(['from_user_id'=> auth()->id(), 'accepted' => 0])->get();
        $active_users = 'primary';
        return view('user.index', compact('users', 'active_users', 'requests'));
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
    public function show(Request $request, $id)
    {
        //
        $request->id = $id;
        $user = User::find($request->id);
        $posts = Post::where(['user_id'=>$request->id])->limit(3)->get();
        $posts_count = Post::where(['user_id'=>$request->id])->count();
        $likes_count =Like::whereIn('post_id', Post::where(['user_id'=>$request->id])->get()->pluck('id'))->count();
        $is_follower = Follower::where(['from_user_id' => auth()->id(), 'to_user_id' => $request->id])->first();

        return view('auth.user_info',compact('user', 'posts', 'posts_count', 'likes_count', 'is_follower'));
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
        $active_profile='primary';
        return view('auth.user_profile', compact('user', 'active_profile'));
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

    public function autocomplete(Request $request){
        $results=array();
        $item = $request->searchname;
        $data = User::where('first_name', 'LIKE', '%'.$item.'%')->orWhere('last_name', 'LIKE', '%'.$item.'%')
            ->take(5)
            ->get();
        return response()->json($data);
    }
}
