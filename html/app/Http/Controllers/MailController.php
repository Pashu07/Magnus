<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller {
   public function basic_email( $name, $email) {
      // print_r($name, $email);
      $data = array('name'=>$name,'email'=>$email);
         // print_r($data);

      Mail::send('content.mail', $data, function($message)  use($data) {
         $message->to($data['email'])->subject
            ('Your Registration Is Successfull In ShriHR');
         // $message->from('support@secretdeveloper.in','Virat Gandhi');
      });



      
      echo "Basic Email Sent. Check your inbox.";
      return redirect('getleadlist/all')->with('message', 'Candidate has been Added Successfully');

   }
   public function html_email() {
      $data = array('name'=>"Virat Gandhi");
      Mail::send('mail', $data, function($message) {
         $message->to('sagar98pl@gmail.com', 'Shrihr')->subject
            ('Laravel HTML Testing Mail');
         $message->from('support@secretdeveloper.in','Virat Gandhi');
      });
      echo "HTML Email Sent. Check your inbox.";
   }
   public function attachment_email() {
      $data = array('name'=>"Virat Gandhi");
      Mail::send('mail', $data, function($message) {
         $message->to('sagar98pl@gmail.com', 'Shrihr')->subject
            ('Laravel Testing Mail with Attachment');
         // $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
         // $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
         $message->from('support@secretdeveloper.in','Virat Gandhi');
      });
      echo "Email Sent with attachment. Check your inbox.";
   }
}