<?php

namespace App\Http\Controllers;
use Mail;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\VerticalHead;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Session;

class VerticalHeadController extends Controller {
   public function index()
   {
       $vertical = User::where('user_role', '=', 'VERTICAL')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
       // dd($vertical);
       $breadcrumbs = [
           ['link' => "dashboard", 'name' => "Home"], ['name' => "Vertical Details"]
       ];
       return view('/content/vertical-head-list', ['breadcrumbs' => $breadcrumbs,'vertical' => $vertical]);
   }

   public function add_vertical_head()
   {
      //  $vacancy = Vartical::get();

       $breadcrumbs = [
           ['link' => "vertical Head", 'name' => "Vertical Head List"], ['name' => "Vertical Head List"]

       ];
       return view('content.add-vertical-head' , ['breadcrumbs' => $breadcrumbs]);
   }

   
   public function store(Request $request)
   {
       if (User::where('email', $request->email)->exists()) {
           //email exists in user table
           return back()->with('error', 'Email alrady exist ');
       }
       if (User::where('contact', $request->contact)->exists()) {
           //contact exists in user table
           return back()->with('error', 'Contact alrady exist ');
       }
       if (User::where('pan_no', $request->pan_no)->exists()) {
           //pan_no exists in user table
           return back()->with('error', 'Pan NO Alredy Exist');
       }
       if (User::where('adhar_no', $request->adhar_no)->exists()) {
           //adhar_no exists in user table
           return back()->with('error', 'Adhar No alrady exist ');
       }
       else {
           $vertical = new User;
         //   $referral_id =  $this->random_strings(8);
         //   $vertical->referral_id = $referral_id;
           $vertical->name = $request->name;
           $vertical->email = $request->email;
           $vertical->password = bcrypt($request->password);
           $vertical->contact = $request->contact;
           $vertical->adhar_no = $request->adhar_no;
           $vertical->pan_no = $request->pan_no;
           $vertical->organisation = $request->organisation;
           $vertical->doj = $request->doj;
           $vertical->ctc = $request->ctc;
           $vertical->user_role = 'VERTICAL';
           $vertical->added_by = Auth::id();

           $vertical->save();

           return redirect('vertical')->with('message', 'Vertical has been Added Successfully');
       }
   }

   public function edit(Request $request)
   {
       $id = $request->verticalId;
       $vertical = User::where('id', $id)->first();
      //  $designation = DB::table('emp_designation')->get();
       $breadcrumbs = [
           ['link' => "vertical Head", 'name' => "vertical Head List"], ['name' => "Update vertical Head Details"]
       ];
    //    dd($vertical);
       return view('/content/update-vertical-list', ['breadcrumbs' => $breadcrumbs, 'id' => $id,'vertical', $vertical])->with('vertical', $vertical);
   }

   public function update_verticals(Request $request, $id)
   {

    // dd($request->all());
       $vertical = User::where('id', $id)->first();
       $vertical->name = $request->name;
       $vertical->email = $request->email;

       if (!$request->password == '') {
           $vertical->password = bcrypt($request->password);
       }

       $vertical->contact = $request->contact;
       $vertical->adhar_no = $request->adhar_no;
       $vertical->pan_no = $request->pan_no;
       $vertical->organisation = $request->organisation;
       $vertical->doj = $request->doj;
       $vertical->ctc = $request->ctc;
       $vertical->user_role = 'VERTICAL';
       $vertical->added_by = Auth::id();

       $vertical->update();

       return redirect('vertical')->with('message', 'Vertical has been Updated Successfully');
   }

   public function destroy($id)
   {
       $vertical = User::where('user_role', '=', 'EMPLOYEE')->get();

       $vertical = User::where('id', $id)->first();
       $vertical->IsDeleted = 1;

       $vertical->update();

       // User::find($id)->delete();

       return back()->with('message', 'Vertical has been deleted Successfully');;
   }

//    public function vertical_details(){
//     $vertical = User::where('user_role', '=', 'VERTICAL')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
//     // dd($vertical);

//     $breadcrumbs = [
//         ['link' => "dashboard", 'name' => "Home"], ['name' => "Vertical Details"]
//     ];
//     return view('/content/vertical-head-list-branch', ['breadcrumbs' => $breadcrumbs,'vertical' => $vertical]);

//    }

   
//    public function vertical_all_branch(Request $request)
//    {
       
//         $findvertical = User::where('user_role', '=', 'VERTICAL')->where('IsDeleted' , 0)->where('id', $request->location_id)->orderBy('id', 'DESC')->first();
//         $findbranch = User::where('user_role', '=', 'BRANCH')->where('IsDeleted' , 0)->where('added_by', $findvertical->id)->orderBy('id', 'DESC')->get();
//         $find_tl=array();
//         foreach($findbranch as $value) {
//                 $id=  $value->id;
//                 $find_tl[]  = DB::table('users')->where('added_by','=',$id)->where('user_role','=',"TEAMHEAD")->get();     
//         }

//         $findvertical_all = array();
//         $findvertical_all[] = User::where('user_role', '=', 'VERTICAL')->where('IsDeleted' , 0)->where('id', $request->location_id)->orderBy('id', 'DESC')->first();

//         $findvertical_all[] = User::where('user_role', '=', 'BRANCH')->where('IsDeleted' , 0)->where('added_by', $findvertical->id)->orderBy('id', 'DESC')->get();
//         $my_array2 =array();
//         foreach($findbranch as $value) {
//                 $id=  $value->id;
//                 $my_array2[]  = DB::table('users')->where('added_by','=',$id)->where('user_role','=',"TEAMHEAD")->get();     
//         }
//         $res = array_merge($findvertical_all, $my_array2);

//     echo "<pre>"; 
//     print_r($res);
//     // print_r($findbranch);
//     // print_r($find_tl);exit();
//     echo "</pre>"; 
//     exit();
//        return response()->json(array('vertical'=>$findvertical_all));

//    }

   public function tree()
   {
    // dd(Session::get('user')['user_role']);
        if(Session::get('user')['user_role'] == 'ADMIN' ){
            $parentCategories = DB::table('users')->where('added_by', '=', 1)->where('isDeleted', '=', 0)->get();
        }
        elseif(Session::get('user')['user_role'] == 'VERTICAL'){
            $parentCategories = DB::table('users')->where('id', '=', Auth::id())->where('isDeleted', '=', 0)->get();
        }
        elseif(Session::get('user')['user_role'] == 'BRANCH'){
            $parentCategories = DB::table('users')->where('id', '=', Auth::id())->where('isDeleted', '=', 0)->get();
        }
        elseif(Session::get('user')['user_role'] == 'TEAMHEAD'){
            $parentCategories = DB::table('users')->where('id', '=', Auth::id())->where('isDeleted', '=', 0)->get();
        }
        elseif(Session::get('user')['user_role'] == 'EMPLOYEE'){
            $parentCategories = DB::table('users')->where('id', '=', Auth::id())->where('isDeleted', '=', 0)->get();
        }
       return view('content/vertical-head-list-branch', compact('parentCategories'));
   }    
}