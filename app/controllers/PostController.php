<?php

use Dingo\Api\Routing\ControllerTrait; 

class PostController extends \BaseController
{
    use ControllerTrait;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
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
        $post = [
            'title' => Input::get ( 'title' ),
            'content' => Input::get ( 'content' ),
        ];
        $rules = [
            'title' => 'required',
            'content' => 'required',
        ];
        $valid = Validator::make ( $post, $rules );
        if ( $valid->passes () ) {
            $post = new Post ( $post );
            $post->comment_count = 0;
            $post->read_more = (strlen ( $post->content ) > 120) ? substr ( $post->content, 0, 120 ) : $post->content;
            $post->save ();
            
            return $this->response->array($post);
            //return $post; //JSON response
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
        $post = Post::with('comments')->where('id', '=', $id)->get();
        return $this->response->array($post);
        //return $post; //JSON response
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
     * @param int $id
     * @return Response
     */
    public function update( $id )
    {
        $data = [
            'title' => Input::get ( 'title' ),
            'content' => Input::get ( 'content' ),
        ];
        $rules = [
            'title' => 'required',
            'content' => 'required',
        ];
        $valid = Validator::make ( $data, $rules );
        if ( $valid->passes () ) {
            $post = Post::find ( $id );
            $post->title = $data['title'];
            $post->content = $data['content'];
            $post->read_more = (strlen ( $post->content ) > 120) ? substr ( $post->content, 0, 120 ) : $post->content;
            if ( count ( $post->getDirty () ) > 0 ) /* avoiding resubmission of same content */ {
                $post->save ();
                return $this->response->array(['success! Post is updated!']);
                // return Response::make(['success! Post is updated!'], 200); // Response in JSON
            } else {
                return $this->response->array(['success Nothing to update!']);
                // return Response::make(['success! Nothing to update!'], 200); // Response in JSON
            }
        } else {
            return $this->response->error($valid->errors ()->first(), 401); // Response in Array
            // return Response::make([$valid->$error->all()]); // Response in JSON
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
        $post = Post::where ('id', '=', $id )->get();
        if ( ! $post->isEmpty()) {
            $post->delete ();
            return $this->response->array(['success! Post is deleted!']);
            // return Response::make(['success! Post is deleted!'], 200); // Response in JSON
        } else {
            return $this->response->array(['No record found!']); // Response in Array
            // return Response::make(['No record found!']); // Response in JSON
        }
    }

}
