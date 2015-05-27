<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Comment extends Eloquent
{
    protected $fillable = ['post_id', 'commenter', 'email', 'comment'];
    
    public function post()
    {
        return $this->belongsTo ( 'Post' );
    }

}
