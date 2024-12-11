<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Input\Input;
use Session;

class EmployeeController extends Controller
{

    public function getEmps(Request $request){

        print_r($request->all());
       //  exit();
       //  $verticalId = $request->verticalId;
       //  $branch_id = $request->branch_id;
       //  $teamhead_id = $request->teamhead_id;
       //  $empId = $request->empId;
       // $vertical = User::where('user_role', '=', 'VERTICAL')->where('added_by', $empId->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();

    }

    public function index()
    {
        if (Auth::check()) {
            if(Session::get('user')['user_role'] == 'TEAMHEAD' || Session::get('user')['user_role'] == 'ADMIN' || Session::get('user')['user_role'] == 'VERTICAL'|| Session::get('user')['user_role'] == 'BRANCH' ){
                // $employees = User::where('user_role', '=', 'EMPLOYEE')->where('IsDeleted' , 0)->where('added_by', Auth::id())->orderBy('id', 'DESC')->get();
                //////////////////////////////////////////////////////////////////////////////////////////////////////
                if(Session::get('user')['user_role'] == 'VERTICAL' ){
                    // dd(Auth::id());
                    $findrow = User::where('user_role', '=', 'BRANCH')->where('IsDeleted' , 0)->where('added_by', Auth::id())->orderBy('id', 'DESC')->get();
                    // dd($findrow->id);
                    $teamleads = array();
                    foreach ($findrow as $data) {
                    $id = $data->id;    
                        $teamleads[] = DB::table('users')->where('added_by','=',$id)->get();           
                    }
                    $teamlead = array();

                    foreach ($teamleads as $data){
                        foreach ($data as $datas){
                            $teamlead[]=$datas;
                        }
                    }
                    //  echo "<pre>";
                    //  print_r($teamlead);
                    //  echo "</pre>";
                    $employeess = array();

                    foreach ($teamlead as $employees) {
                        $id = $employees->id;         
                            $employeess[] = DB::table('users')->where('added_by','=',$id)->get(); 
                                    
                        }
                    $employeesss = array();
                    $employeesss = User::where('user_role', '=', 'EMPLOYEE')->where('IsDeleted' , 0)->where('added_by', Auth::id())->orderBy('id', 'DESC')->get();

                    foreach ($employeess as $data){
                        foreach ($data as $datas){
                            $employeesss[]=$datas;
                        }
                    }

                }
                elseif(Session::get('user')['user_role'] == 'BRANCH'){

                    $branch_find = User::where('user_role', '=', 'BRANCH')->where('IsDeleted' , 0)->where('id', Auth::id())->orderBy('id', 'DESC')->get();
                    
                     $branch_get = array();

                     foreach ($branch_find as $employees) {
                         $id = $employees->id;         
                             $branch_get[] = DB::table('users')->where('added_by','=',$id)->get(); 
                                     
                         }
                        $branch_gets=array();
                         foreach ($branch_get as $data){
                            foreach ($data as $datas){
                                $branch_gets[]=$datas;
                                
                            }
                        }
                        $branch_gets_id=array();
                        foreach ($branch_gets as $datas){
                            $branch_gets_id[]=$datas->id;

                        }
                        $findrows=array();

                        foreach ($branch_gets as $datas){
                            $id = $datas->id;
                            $findrows[] =DB::table('users')->where('user_role', '=', 'EMPLOYEE')->where('added_by','=',$id)->get();    

                        }
                         
                        $employeesss=array();
                         foreach ($findrows as $data){
                            foreach ($data as $datas){
                                $employeesss[]=$datas;
                                
                            }
                        }
                                // echo "<pre>";
                                // print_r($findrow);
                                // echo "</pre>";
                       
// exit();         
                    // $findrow = User::where('user_role', '=', 'BRANCH')->where('IsDeleted' , 0)->where('added_by','=', $branch_gets->id)->orderBy('id', 'DESC')->get();
                    // dd($employeesss);
                    // $teamleads = array();
                    // foreach ($findrow as $data) {
                    // $id = $data->id;    
                    //     $teamleads[] = DB::table('users')->where('added_by','=',$id)->get();           
                    // }
                    // $teamlead = array();

                    // foreach ($teamleads as $data){
                    //     foreach ($data as $datas){
                    //         $teamlead[]=$datas;
                    //     }
                    // }
                    // //  echo "<pre>";
                    // //  print_r($teamlead);
                    // //  echo "</pre>";
                    // $employeess = array();

                    // foreach ($teamlead as $employees) {
                    //     $id = $employees->id;         
                    //         $employeess[] = DB::table('users')->where('added_by','=',$id)->get(); 
                                    
                    //     }
                    // $employeesss = array();
                    // $employeesss = User::where('user_role', '=', 'EMPLOYEE')->where('IsDeleted' , 0)->where('added_by', Auth::id())->orderBy('id', 'DESC')->get();

                    // foreach ($employeess as $data){
                    //     foreach ($data as $datas){
                    //         $employeesss[]=$datas;
                    //     }
                    // }

                }  
                elseif(Session::get('user')['user_role'] == 'TEAMHEAD'){
                    $employeesss = User::where('user_role', '=', 'EMPLOYEE')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();

                }  
                elseif(Session::get('user')['user_role'] == 'ADMIN'){
                    $employeesss = User::where('user_role', '=', 'EMPLOYEE')->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();

                }   
                else{
                        $employeesss = User::where('user_role', '=', 'EMPLOYEE')->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
                }
                //////////////////////////////////////////////////////////////////////////////////////////////////////
                // dd($employees);
                $breadcrumbs = [
                    ['link' => "dashboard", 'name' => "Home"], ['name' => "Employee Details"]
                ];
                return view('/content/employees-list', ['breadcrumbs' => $breadcrumbs, 'employees' => $employeesss]);
            }else{
                echo "ypu dont have persmission";
            }
        }else{
            echo "Redirect to login";
        }


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
            $employees = new User;
            $referral_id =  $this->random_strings(8);
            $employees->referral_id = $referral_id;
            $employees->name = $request->name;
            $employees->email = $request->email;
            $employees->password = bcrypt($request->password);
            $employees->contact = $request->contact;
            $employees->adhar_no = $request->adhar_no;
            $employees->pan_no = $request->pan_no;
            $employees->organisation = $request->organisation;
            $employees->doj = $request->doj;
            $employees->ctc = $request->ctc;
            if(Session::get('user')['user_role'] == 'ADMIN'){
                $employees->added_by = $request->teamhead_id;
            }
            elseif(Session::get('user')['user_role'] == 'VERTICAL'){
                $employees->added_by = $request->branch_id;
            }
            elseif(Session::get('user')['user_role'] == 'BRANCH'){
                $employees->added_by = $request->VBTL_id;
            }
            else{
            $employees->added_by = Auth::id();
            }
            $employees->user_role = 'EMPLOYEE';
            // dd($employees);
            $employees->super_added = Auth::id();

            $employees->save();

            return redirect('employees')->with('message', 'Employees has been Added Successfully');
        }
    }


    public function edit(Request $request)
    {
        $id = $request->employeeId;
        $employees = User::where('id', $id)->first();
        $designation = DB::table('emp_designation')->get();

        if(Session::get('user')['user_role'] == 'ADMIN'){
            $vertical = User::where('user_role', '=', 'VERTICAL')->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
            $get_vertical="";
                $added_by=User::where('user_role', '=', 'BRANCH')->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
                $get_tls=User::where('user_role', '=', 'TEAMHEAD')->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
            }
            elseif(Session::get('user')['user_role'] == 'VERTICAL'){
                $vertical = User::where('user_role', '=', 'BRANCH')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
            // dd($employees->id);
            $added_bys = User::where('id', $employees->added_by)->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();

            foreach($added_bys as $add)
                {
                    $added_by= $add;
                }   
            // dd($added_bys);     
            $get_verticals = User::where('user_role', '=', 'BRANCH')->where('id', $added_by->added_by)->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
            // dd($get_verticals);
            foreach($get_verticals as $add)
                {
                    $get_vertical= $add;
                    
                }   
                // dd($get_vertical);
                $get_tls = User::where('user_role', '=', 'TEAMHEAD')->where('added_by', $get_vertical->id)->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
                // foreach($get_tl as $add)
                //     {
                //         $get_tls= $add;
                //     }   

            // dd($get_tls); 



            }
            elseif(Session::get('user')['user_role'] == 'BRANCH'){
                $vertical = User::where('user_role', '=', 'TEAMHEAD')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
            // dd($vertical);
            $get_vertical="";
                $added_by='';
                $get_tls='';
            }
            else{
                $vertical = User::where('user_role', '=', 'VERTICAL')->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
                $get_vertical="";
                $added_by='';
                $get_tls='';
            }


        $breadcrumbs = [
            ['link' => "employees", 'name' => "Employees List"], ['name' => "Update Employees Details"]
        ];
        return view('/content/update-employees-list', ['breadcrumbs' => $breadcrumbs,  'designation' => $designation , 'id' => $id, 'vertical' => $vertical, 'get_vertical' => $get_vertical, 'added_by' => $added_by,'get_tls'=>$get_tls])->with('employees', $employees);
    }


    public function update_employees(Request $request, $id)
    {
        $employees = User::where('id', $id)->first();
        $employees->name = $request->name;
        $employees->email = $request->email;

        if (!$request->password == '') {
            $employees->password = bcrypt($request->password);
        }

        $employees->contact = $request->contact;
        $employees->adhar_no = $request->adhar_no;
        $employees->pan_no = $request->pan_no;
        $employees->organisation = $request->organisation;
        $employees->doj = $request->doj;
        $employees->ctc = $request->ctc;
        $employees->user_role = 'EMPLOYEE';
        // $employees->added_by = Auth::id();
        if(Session::get('user')['user_role'] == 'ADMIN'){
            $employees->added_by = $request->teamhead_id;
        }
        elseif(Session::get('user')['user_role'] == 'VERTICAL'){
            $employees->added_by = $request->branch_id;
        }
        elseif(Session::get('user')['user_role'] == 'BRANCH'){
            $employees->added_by = $request->VBTL_id;
        }
        else{
        $employees->added_by = Auth::id();
        }
        // dd( $employees);
        $employees->update();

        return redirect('employees')->with('message', 'Employees has been Updated Successfully');
    }


    public function destroy($id)
    {
        $employees = User::where('user_role', '=', 'EMPLOYEE')->get();

        $employees = User::where('id', $id)->first();
        $employees->IsDeleted = 1;

        $employees->update();

        // User::find($id)->delete();

        return back()->with('message', 'Employees has been deleted Successfully');;
    }
    function random_strings($length_of_string)
    {

    // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    // Shuffle the $str_result and returns substring
    // of specified length
        return substr(str_shuffle($str_result),0, $length_of_string);
    }


    public function fetchVBTLApply(Request $request)
    {
        // dd($request->location_id);
        $findrow = User::where('user_role', '=', 'VERTICAL')->where('IsDeleted' , 0)->where('id', $request->location_id)->orderBy('id', 'DESC')->get();
        $teamleads = array();
        foreach ($findrow as $data) {
        $id = $data->id;      
            $teamleads[] = DB::table('users')->where('added_by','=',$id)->where('user_role','=',"BRANCH")->where('IsDeleted' , 0)->get();           
        }
        $teamlead = array();
        // $teamlead = User::where('user_role', '=', 'TEAMHEAD')->where('IsDeleted' , 0)->where('added_by', Auth::id())->orderBy('id', 'DESC')->get();
        foreach ($teamleads as $data){
            foreach ($data as $datas){
                $teamlead[]=$datas;                
            }
        }
        $teamleadss = array();
        foreach ($teamlead as $datas){
            // $teamleadss[]=$datas;
            $teamleadss[] = DB::table('users')->where('added_by','=',$datas->id)->where('user_role','=',"TEAMHEAD")->where('IsDeleted' , 0)->get();        
        }
        $teamlead_get = array();

        foreach ($teamleadss as $data){
            foreach ($data as $datas){
                $teamlead_get[]=$datas;                
            }
        }
        // echo "<pre>";
        //  print_r($teamlead_get);
        //  echo "</pre>";exit();
        // dd($teamlead->id);
        return response()->json($teamlead);
    }


    public function fetchVBTLISTApply(Request $request)
    {
        // dd($request->location_id);
        // $data['states'] = DB::table('users')->where("id", $request->location_id)->get();
        $findrow = User::where('user_role', '=', 'BRANCH')->where('IsDeleted' , 0)->where('id', $request->location_id)->orderBy('id', 'DESC')->get();
        $teamleads = array();
        foreach ($findrow as $data) {
        $id = $data->id;      
            $teamleads[] = DB::table('users')->where('added_by','=',$id)->where('user_role','=',"TEAMHEAD")->where('IsDeleted' , 0)->get();           
        }
        $teamlead = array();
        // $teamlead = User::where('user_role', '=', 'TEAMHEAD')->where('IsDeleted' , 0)->where('added_by', Auth::id())->orderBy('id', 'DESC')->get();

        foreach ($teamleads as $data){
            foreach ($data as $datas){
                $teamlead[]=$datas;                
            }
        }
        // echo "<pre>";
        // print_r($teamlead);
        // echo "</pre>"; 
        // dd($teamlead->id);
        return response()->json($teamlead);
    }
        public function fetchVBTLEMPApply(Request $request)
    {
        $findrow = User::where('user_role', '=', 'TEAMHEAD')->where('IsDeleted' , 0)->where('id', $request->location_id)->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
        $teamleads = array();
        foreach ($findrow as $data) {
        $id = $data->id;      
            $teamleads[] = DB::table('users')->where('added_by','=',$id)->where('user_role','=',"EMPLOYEE")->get();           
        }
        $teamlead = array();
        // $teamlead = User::where('user_role', '=', 'TEAMHEAD')->where('IsDeleted' , 0)->where('added_by', Auth::id())->orderBy('id', 'DESC')->get();

        foreach ($teamleads as $data){
            foreach ($data as $datas){
                $teamlead[]=$datas;                
            }
        }
        // echo "<pre>";
        // print_r($teamlead);
        // echo "</pre>"; 
        // dd($teamlead->id);
        return response()->json($teamlead);
    }
    
    public function fetchVBTLbranch_chg(Request $request)
    {
        // dd($request->all());

        $findrow = User::where('user_role', '=', 'BRANCH')->where('IsDeleted' , 0)->where('id', $request->location_id)->orderBy('id', 'DESC')->get();
        // dd($findrow);
        $ver=array();
        foreach($findrow as $value) {
        $ver[]=  $value->added_by;
        // dd($value->id);

        }
        // dd($ver);

        $findvertical=array();
        $findvertical = User::where('user_role', '=', 'VERTICAL')->where('IsDeleted' , 0)->where('id', $ver)->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
        // $findvertical = User::where('user_role', '=', 'VERTICAL')->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();

        // dd($findvertical);
        return response()->json($findvertical);

    }

    
    public function fetch_brh_change(Request $request)
    {
        // dd($request->all());

        $TEAMHEAD = User::where('user_role', '=', 'TEAMHEAD')->where('IsDeleted' , 0)->where('id', $request->location_id)->orderBy('id', 'DESC')->get();
        // dd($TEAMHEAD);
        // $branch=array();
        foreach($TEAMHEAD as $value) {
        // $branch[]=  $value->added_by;
        $value->added_by;

        }

        $branch_vertical=array();
        $branch_vertical = User::where('user_role', '=', 'BRANCH')->where('IsDeleted' , 0)->where('id', $value->added_by)->orderBy('id', 'DESC')->get();
        
        // dd($branch_vertical);

        // $branch_vertical = User::where('user_role', '=', 'VERTICAL')->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();

        // dd($branch_vertical);
        // $branch=array();
        // foreach($branch_vertical as $value) {
        // $branch[]=  $value->added_by;
        // // dd($value->id);

        // }
        // dd($branch_vertical);

        return response()->json($branch_vertical);

    }
}


