<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class VerticalHeadController extends Controller {
   public function basic_email() {
    
      $data = array('name'=>"Virat Gandhi");
      print_r($data);
   
    //   Mail::send(['text'=>'mail'], $data, function($message) {
    //      $message->to('sagarvagdoda1999@gmail.com', 'Tutorials Point')->subject
    //         ('Laravel Basic Testing Mail');
    //      $message->from('sagarvagdoda1999@gmail.com','Virat Gandhi');
    //   });
      echo "Basic Email Sent. Check your inbox.";
   }

}