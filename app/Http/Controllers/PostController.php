<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Like;
use App\Post;
use App\User;
use App\Follower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $posts = Post::where('user_id','=', auth()->id())->paginate(3);

        $active_profile = 'primary';
        return view('post.index', compact('posts', 'active_profile'));
    }

    public function allPosts()
    {
        //
        $posts=Post::withCount('likes')
            ->whereIn('user_id', auth()->user()->following()->where('accepted', '=', 1)
                ->pluck('to_user_id')
            )->paginate(3);
        $active_home = 'primary';
        return view('home', compact('posts', 'active_home'));
    }

    public function userFriendPosts($id)
    {
        //
        //$is_follower=Follower::where(['from_user_id'=> auth()->id(), 'to_user_id'=> $id, 'accepted'=> 1])->get();

        if (policy(Post::class)->userFriendPosts(auth()->user(), $id)){
            $posts = Post::withCount('likes')->where(['user_id'=>$id])->paginate(3);
            $friend_name=null;
            if (count($posts))
                $friend_name = $posts[0]->user->first_name .' '. $posts[0]->user->last_name;
            return view('home', compact('posts','friend_name'));
        }

        return redirect('home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // save the data
        $img_name = '';
        if ($request->hasFile('filename')){
            $file = $request->file('filename');
            $img_name = Auth::id().'-'.time().'-'.$file->getClientOriginalName();
            $file->move(public_path().'/images/', $img_name);
        }
        $post = new Post();
        $post->body = $request->get('body');
        $post->user_id= Auth::id();
        $post->image_path=$img_name;
        $post->save();
        return redirect(route('post.index'));
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
        $post=Post::with('user')->find($id);
        $user = auth()->user();
        if ($user->can('show', $post)){
            $likes = Like::where('post_id', $id)->count();
            $user_like = Like::where(['user_id' => auth()->id(), 'post_id' => $id])->get();
            $post_comments = Comment::where('post_id', $id)->get();
            return view('post.show', compact('post', 'likes', 'user_like', 'post_comments'));
        }
        else
            return redirect('not_found');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $post=Post::find($id);
        if (auth()->user()->can('edit', $post))
            return view('post.edit', compact('post'));
        else
            return redirect('not_found');

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
        //
        $post=Post::find($id);
        if (auth()->user()->can('update', $post)){
            $post->body=$request->get('body');
            $post->save();
            return $this->show($id);
        }
        else
            return redirect('not_found');
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
        $post=Post::find($id);
        if (auth()->user()->can('delete', $post)){
            $post->delete();
            return redirect(route('post.index'));
        }
        else
            return redirect('not_found');
    }
}
