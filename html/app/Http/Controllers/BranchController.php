<?php

namespace App\Http\Controllers;
use Mail;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use App\Models\User;


class BranchController extends Controller {
    public function index()
    {
        if (Auth::check()) {
            if( Session::get('user')['user_role'] == 'ADMIN' || Session::get('user')['user_role'] == 'BRANCH' ){
                $branch = User::where('user_role', '=', 'BRANCH')->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
            }else{
                 $branch = User::where('user_role', '=', 'BRANCH')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
            }
        }
        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Home"], ['name' => "branch Details"]
        ];
        return view('/content/branch-head-list', ['breadcrumbs' => $breadcrumbs,'branch' => $branch]);
    }
 
    public function add_branch_head()
    {
       //  $vacancy = Vartical::get();
    //    dd(Session::get('user')['user_role']);

       if(Session::get('user')['user_role'] == 'ADMIN'){
        $vertical = User::where('user_role', '=', 'VERTICAL')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
            // dd($vertical);
    } 
    $vertical = User::where('user_role', '=', 'VERTICAL')->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();

        $breadcrumbs = [
            ['link' => "vertical Head", 'name' => "Vertical Head List"], ['name' => "Vertical Head List"]
 
        ];
        return view('content.add-branch-head' , ['breadcrumbs' => $breadcrumbs, 'vertical'=>$vertical]);
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
            $branch = new User;
          //   $referral_id =  $this->random_strings(8);
          //   $branch->referral_id = $referral_id;
            $branch->name = $request->name;
            $branch->email = $request->email;
            $branch->password = bcrypt($request->password);
            $branch->contact = $request->contact;
            $branch->adhar_no = $request->adhar_no;
            $branch->pan_no = $request->pan_no;
            $branch->organisation = $request->organisation;
            $branch->doj = $request->doj;
            $branch->ctc = $request->ctc;
            $branch->user_role = 'BRANCH';
            if(Session::get('user')['user_role'] == 'ADMIN'){
                $branch->added_by = $request->vertical_id;
            }else{
                $branch->added_by = Auth::id();

            }
            $branch->super_added = Auth::id();

            // dd($branch);
            $branch->save();
 
            return redirect('branch')->with('message', 'Branch has been Added Successfully');
        }
    }
 
    public function edit(Request $request)
    {
        $id = $request->branchId;
        $branch = User::where('id', $id)->first();
       //  $designation = DB::table('emp_designation')->get();
       if(Session::get('user')['user_role'] == 'ADMIN'){
        $vertical = User::where('user_role', '=', 'VERTICAL')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
            // dd($vertical);
        } 
        $vertical = User::where('user_role', '=', 'VERTICAL')->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();


        $breadcrumbs = [
            ['link' => "branch Head", 'name' => "branch Head List"], ['name' => "Update branch Head Details"]
        ];
       //  dd($branch);
        return view('/content/update-branch-list', ['breadcrumbs' => $breadcrumbs, 'id' => $id,'branch',$branch,'vertical'=> $vertical])->with('branch', $branch,'vertical',$vertical);
    }
 
    public function update_branchs(Request $request, $id)
    {
        $branch = User::where('id', $id)->first();
        $branch->name = $request->name;
        $branch->email = $request->email;
 
        if (!$request->password == '') {
            $branch->password = bcrypt($request->password);
        }
 
        $branch->contact = $request->contact;
        $branch->adhar_no = $request->adhar_no;
        $branch->pan_no = $request->pan_no;
        $branch->organisation = $request->organisation;
        $branch->doj = $request->doj;
        $branch->ctc = $request->ctc;
        $branch->user_role = 'BRANCH';
        // $branch->added_by = Auth::id();
        if(Session::get('user')['user_role'] == 'ADMIN'){
            $branch->added_by = $request->vertical_id;
        }else{
            $branch->added_by = Auth::id();

        }
        // dd($branch);
        $branch->update();
 
        return redirect('branch')->with('message', 'branch has been Updated Successfully');
    }
 
    public function destroy($id)
    {
        $branch = User::where('user_role', '=', 'EMPLOYEE')->get();
 
        $branch = User::where('id', $id)->first();
        $branch->IsDeleted = 1;
 
        $branch->update();
 
        // User::find($id)->delete();
 
        return back()->with('message', 'Branch has been deleted Successfully');;
    }
 }