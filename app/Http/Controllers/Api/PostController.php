<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'posts'   => $posts,
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate request
        $validator = Validator::make($request->all(), [
            'user_id'  => 'required',
            'title'    => 'required',
            'content'  => 'required'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        Post::create([
            'user_id'  => $request->user_id,
            'title'    => $request->title,
            'content'  => $request->content
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post added successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with('user')->find($id);

        //response if there is no post
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post is not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'post'    => $post,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //find post by ID
        $post = Post::find($id);

        //response if there is no post
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post is not found.'
            ], 404);
        }

        //update post
        $post->update([
            'user_id'  => $request->user_id,
            'title'    => $request->title,
            'content'  => $request->content,
        ]);

        //return response
        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //find post by ID
        $post = Post::find($id);

        //response if there is no post 
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post is not found.'
            ], 404);
        }

        //delete post
        $post->delete();

        //return response
        return response()->json([
            'success' => true,
            'message' => 'Post has been deleted successfully'
        ], 202);
    }
}
