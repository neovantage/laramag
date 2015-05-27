<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Post extends Eloquent {
    
    protected $fillable = ['title', 'content'];


    public function comments()
    {
        return $this->hasMany('Comment')->where('approved', '=', 1);
    }
 
}