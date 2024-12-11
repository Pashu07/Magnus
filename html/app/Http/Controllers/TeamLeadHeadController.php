<?php

namespace App\Http\Controllers;
use Mail;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\TeamLeadHead;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use App\Models\User;
use App\Models\Recuiter;
use App\Models\Company;
use App\Models\Designation;
use App\Models\VerticalHead;

class TeamLeadHeadController extends Controller {
    public function index()
    {
        if (Auth::check()) {

            if (Session::get('user')['user_role'] == 'ADMIN' OR Session::get('user')['user_role'] == 'VERTICAL' OR Session::get('user')['user_role'] == 'BRANCH' ) {

                if (Session::get('user')['user_role'] == 'VERTICAL' ){
                    $findrow = User::where('user_role', '=', 'VERTICAL')->where('id', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get(); 

                    $teamleads = array();
                    foreach ($findrow as $data) {
                    $id = $data->id;      
                    // dd( $id);

                        $teamleads[] = DB::table('users')->where('IsDeleted' , 0)->where('added_by','=',$id)->get();           
                    }
                    $teamlead_find=array();
                    foreach ($teamleads as $data){
                       foreach ($data as $datas){
                           $teamlead_find[]=$datas;
                           
                       }
                   }
                   
                   $teamleads_GET = array();
                   foreach ($teamlead_find as $data) {
                   $id = $data->id;      
                   // dd( $id);

                       $teamleads_GET[] = DB::table('users')->where('IsDeleted' , 0)->where('added_by','=',$id)->get();           
                   }
                   $teamlead=array();
                   foreach ($teamleads_GET as $data){
                      foreach ($data as $datas){
                          $teamlead[]=$datas;
                          
                      }
                  }
                        // echo "<pre>";
                        // print_r($teamlead);
                        // echo "</pre>";
                        // exit();

                    // $teamlead = User::where('user_role', '=', 'TEAMHEAD')->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get(); 
                }
                elseif(Session::get('user')['user_role'] == 'ADMIN'){
                    $teamlead = User::where('user_role', '=', 'TEAMHEAD')->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();

                }  

                else{
                    if (Session::get('user')['user_role'] == 'VERTICAL' OR Session::get('user')['user_role'] == 'ADMIN' OR Session::get('user')['user_role'] == 'BRANCH'){

                    // $teamlead = User::where('user_role', '=', 'TEAMHEAD')->where('IsDeleted' , 0)->where('added_by', Auth::id())->orderBy('id', 'DESC')->get();
                        $findrow = User::where('user_role', '=', 'BRANCH')->where('IsDeleted' , 0)->where('added_by', Auth::id())->orderBy('id', 'DESC')->get();
                        // dd(Auth::id());
                        $teamleads = array();
                        foreach ($findrow as $data) {
                        $id = $data->id;      

                            $teamleads[] = DB::table('users')->where('added_by','=',$id)->get();           
                        }
                        //  echo "<pre>";
                        //  print_r($teamleads);
                        //  echo "</pre>";
                        $teamlead = array();
                        $teamlead = User::where('user_role', '=', 'TEAMHEAD')->where('IsDeleted' , 0)->where('added_by', Auth::id())->orderBy('id', 'DESC')->get();

                        foreach ($teamleads as $data){
                            foreach ($data as $datas){
                                $teamlead[]=$datas;
                            }
                        }
                    }
                }
                
            }
            
        }
        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Home"], ['name' => "Team Lead Details"]
        ];
        return view('/content/team-lead-head-list', ['breadcrumbs' => $breadcrumbs,'teamlead' => $teamlead]);
    }
 
    public function add_team_head()
    {
       //  $vacancy = Vartical::get();
       if(Session::get('user')['user_role'] == 'VERTICAL'){
       $branch = User::where('user_role', '=', 'BRANCH')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
       }
       elseif(Session::get('user')['user_role'] == 'ADMIN'){
        // dd(Auth::id());
        $branch = User::where('user_role', '=', 'VERTICAL')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
        // dd($branch);

        }
       else{
       $branch = User::where('user_role', '=', 'BRANCH')->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
       }
        $breadcrumbs = [
            ['link' => "Team Lead Head", 'name' => "Team Lead Head List"], ['name' => "Team Lead Head List"]
 
        ];
        return view('content.add-team-lead-head' , ['breadcrumbs' => $breadcrumbs,'branch' => $branch]);
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
            $TeamLeadHead = new User;
          //   $referral_id =  $this->random_strings(8);
          //   $branch->referral_id = $referral_id;
            $TeamLeadHead->name = $request->name;
            $TeamLeadHead->email = $request->email;
            $TeamLeadHead->password = bcrypt($request->password);
            $TeamLeadHead->contact = $request->contact;
            $TeamLeadHead->adhar_no = $request->adhar_no;
            $TeamLeadHead->pan_no = $request->pan_no;
            $TeamLeadHead->organisation = $request->organisation;
            $TeamLeadHead->doj = $request->doj;
            $TeamLeadHead->ctc = $request->ctc;
            $TeamLeadHead->user_role = 'TEAMHEAD';
            if(Session::get('user')['user_role'] == 'VERTICAL'){
                $TeamLeadHead->added_by = $request->branch_id;

            }elseif(Session::get('user')['user_role'] == 'ADMIN'){
                $TeamLeadHead->added_by = $request->branch_id;
            }
            else{
            $TeamLeadHead->added_by = Auth::id(); 
            }
            $TeamLeadHead->super_added = Auth::id();

            // dd($TeamLeadHead);
            $TeamLeadHead->save(); 
            return redirect('team')->with('message', 'Team has been Added Successfully');
        }
    }
 
    public function edit(Request $request)
    {
        $id = $request->teamId;
        $team = User::where('id', $id)->first();
       //  $designation = DB::table('emp_designation')->get();
       if(Session::get('user')['user_role'] == 'VERTICAL'){
        $branch = User::where('user_role', '=', 'BRANCH')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
        $added_by='';
        $get_vertical="";
        }
        elseif(Session::get('user')['user_role'] == 'ADMIN'){
         // dd(Auth::id());
         $branch = User::where('user_role', '=', 'VERTICAL')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
         $added_bys = User::where('id', $team->added_by)->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();

        foreach($added_bys as $add)
            {
                $added_by= $add;
            }   
        // dd($added_by);     
        $get_verticals = User::where('user_role', '=', 'VERTICAL')->where('id', $added_by->added_by)->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
        foreach($get_verticals as $add)
            {
                $get_vertical= $add;
            }   
        // dd($get_vertical);            
        
        }
        else{
        $branch = User::where('user_role', '=', 'BRANCH')->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
        $added_by='';
        $get_vertical="";
        }
       
        $breadcrumbs = [
            ['link' => "Team Lead Head", 'name' => "Team Lead Head List"], ['name' => "Update Team Lead Head Details"]
        ];
       //  dd($team);
        return view('/content/update-team-head-list', ['breadcrumbs' => $breadcrumbs, 'id' => $id,'team', $team,'branch'=>$branch,"added_by"=>$added_by,"get_vertical"=>$get_vertical])
        ->with('team', $team,'branch',$branch,'added_by',$added_by);
    }
 
    public function update_teams(Request $request, $id)
    {
        $team = User::where('id', $id)->first();
        $team->name = $request->name;
        $team->email = $request->email;
 
        if (!$request->password == '') {
            $team->password = bcrypt($request->password);
        }
 
        $team->contact = $request->contact;
        $team->adhar_no = $request->adhar_no;
        $team->pan_no = $request->pan_no;
        $team->organisation = $request->organisation;
        $team->doj = $request->doj;
        $team->ctc = $request->ctc;
        $team->user_role = 'TEAMHEAD';
        // $team->added_by = Auth::id();
        if(Session::get('user')['user_role'] == 'VERTICAL'){
            $team->added_by = $request->branch_id;

        }elseif(Session::get('user')['user_role'] == 'ADMIN'){
            $team->added_by = $request->branch_id;
        }
        else{
        $team->added_by = Auth::id(); 
        }
        // dd($team);
        $team->update();
        return redirect('team')->with('message', 'Team Lead Head has been Updated Successfully');
    }
 
    public function destroy($id)
    {
        $team = User::where('user_role', '=', 'TEAMHEAD')->get();
 
        $team = User::where('id', $id)->first();
        $team->IsDeleted = 1;
 
        $team->update();
 
        // User::find($id)->delete();
 
        return back()->with('message', 'Team Head has been deleted Successfully');;
    }
 }