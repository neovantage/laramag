<?php

use Dingo\Api\Routing\ControllerTrait; 

class CommentController extends \BaseController
{
    use ControllerTrait;
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($id)
    {
        $comments = Comment::where('post_id', '=', $id)->orderBy ( 'post_id', 'desc' )->paginate ( 10 );
        return $this->response->array($comments);
    }
    
    /**
     * Display a listing of the comments of a post.
     *
     * @return Response
     */
    public function commentsByPost($id)
    {
        $comments = Comment::where('post_id', '=', $id)->orderBy ( 'post_id', 'desc' )->paginate ( 10 );
        return $this->response->array($comments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $comment = [
            'post_id' => Input::get('post_id'),
            'commenter' => Input::get('commenter'),
            'email' => Input::get('email'),
            'comment' => Input::get('comment'),
        ];
        $rules = [
            'post_id' => 'required',
            'commenter' => 'required',
            'email' => 'required | email',
            'comment' => 'required',
        ];
        $valid = Validator::make($comment, $rules);
        if($valid->passes())
        {
            $comment = new Comment( $comment );
            $comment->approved = 'no';
            $comment->save();
            
            return $this->response->array(['Success! Comment has been submitted and waiting for approval!']);
            // return Response::make(['Success! Comment has been submitted and waiting for approval!'], 200); // Response in JSON
        } else {
            return $this->response->errorBadRequest(); // Response in array
            // return Response::make([$valid->$error->all()]); // Response in JSON
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show( $id )
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit( $id )
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update( $id )
    {
        $data = [
            'commenter' => Input::get('commenter'),
            'email' => Input::get('email'),
            'comment' => Input::get('comment'),
        ];
        $rules = [
            'commenter' => 'required',
            'email' => 'required | email',
            'comment' => 'required',
        ];
        $valid = Validator::make($data, $rules);
        if($valid->passes())
        {
            $comment = Comment::find ( $id );
            $comment->fill($data)->save();
            
            return $this->response->array(['Success! Comment has been updated and waiting for approval!']);
            // return Response::make(['Success! Comment has been submitted and waiting for approval!'], 200); // Response in JSON
        } else {
            //return $this->response->errorBadRequest(); // Response in array
            return Response::make([$valid->errors()->all()]); // Response in JSON
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy( $id )
    {
        $comments = Comment::where ('id', '=', $id )->get();
        
        if ( ! $comments->isEmpty()) {
            $comment = $comments->first();
            
            $post = Post::find($comment->post_id);
            $status = $comment->approved;
            ('1' === $status) ? $post->decrement('comment_count') : '';
            $comment->delete();
            return $this->response->array(['success! Comment deleted!']);
        } else {
            return $this->response->array(['No record found!']); // Response in Array
            // return Response::make(['No record found!']); // Response in JSON
        }
    }

}
