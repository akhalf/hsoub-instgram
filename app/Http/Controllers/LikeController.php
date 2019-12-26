<?php

namespace App\Http\Controllers;

use App\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $is_like = Like::where(['user_id'=>auth()->id(), 'post_id' => $request->get('post_id')]);
        if (!$is_like->count()){
            $like = new Like();
            $like->post_id = $request->get('post_id');
            $like->user_id = auth()->id();
            $like->save();
        }
        //return back();
        $conut=Like::where('post_id', $request->get('post_id'))->count();
        return response()->json(['count'=> $conut, 'id'=> $like->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($post_id)
    {
        //
        $like = Like::where(['user_id' => auth()->id(), 'post_id' => $post_id]);
        if ($like->count())
            $like->delete();
        //return back();
        $conut=Like::where('post_id', $post_id)->count();
        return response()->json(['count' => $conut]);


    }
}
