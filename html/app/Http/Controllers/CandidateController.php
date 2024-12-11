<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use Auth;
use Session;
use App\Models\User;
use App\Models\Company;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Candidate;
use App\Imports\ExcelImport;
use App\Imports\CandidateExport;
use App\Models\Lead;
use Redirect;
use App\Models\Designation;
use Illuminate\Http\Request;
use App\Models\Qualification;
use App\Models\WorkExperience;
use App\Models\PincodeLocation;
use App\Models\Recuiter;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\MailController;
use Mail;
use Carbon\Carbon;


class CandidateController extends Controller
{
    public function index()
    {
        if (Session::get('user')['user_role'] == 'ADMIN') {
            $company = Company::get();
            $recruiters = Recuiter::get();
            $designations = Designation::get();
            $candidate = Candidate::leftjoin('users', 'candidate.added_by', '=', 'users.id')
            ->where('candidate.IsDeleted' , 0)->orderBy('id', 'DESC')
            ->get(['candidate.*', 'users.name as emp_name', 'users.id as user_id']);
        } else {

            $candidate = Candidate::select("*")
            ->where("added_by", "=", Auth::id())
            ->where("IsDeleted", "=", 0)
            ->orderBy('id', 'DESC')
            ->groupBy('added_by')
            ->get();
        }

        $users = User::where('user_role', "=", "EMPLOYEE")->where("IsDeleted", "=", 0)->orderBy('id', 'DESC')->get();

        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Dashboard"], ['name' => "Candidate List"]
        ];
        return view('/content/candidate-list', ['breadcrumbs' => $breadcrumbs , 'companies' => $company , 'recruiters' => $recruiters , 'designations' => $designations , 'candidate' => $candidate, 'users' => $users]);
    }
    public function getleadlist( $type,$sort=null)
    {
        // dd($sort);
        $company = Company::leftjoin('vacancy_location', 'vacancy_location.company_id', '=', 'companies.id')
        ->where('companies.IsDeleted' , 0)->orderBy('companies.id', 'DESC')->groupBy('vacancy_location.company_id')
        ->get(['companies.*','companies.id as comp_id']);
        $recruiters = Recuiter::get();
        $designations = Designation::get();

        if (Session::get('user')['user_role'] == 'ADMIN') {
            // $candidate = Candidate::leftjoin('users', 'candidate.added_by', '=', 'users.id')
            // ->where('candidate.IsDeleted' , 0)->orderBy('id', 'DESC')
            // ->get(['candidate.*', 'users.name as emp_name', 'users.id as user_id','candidate.id as cand_id']);                    $candidate->where('candidate.IsDeleted' , 0);


        }
        ////////////////////////////////////////////////////////
        elseif (Session::get('user')['user_role'] == 'TEAMHEAD') {
            // dd(Auth::id());
            $tl_add_candidate = User::where('user_role', '=', 'EMPLOYEE')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
            // dd($tl_add_candidate);

            $teamleads_GET = array();
            $team_id = array();
            $team_id[] =  Auth::id(); 
            foreach ($tl_add_candidate as $data) {
            $team_id[] = $data->id;      
            // dd( $id);
                // $teamleads_GET[] = DB::table('candidate')->where('IsDeleted' , 0)->where('added_by','=',$id)->get(['candidate.*', 'users.name as emp_name', 'users.id as user_id','candidate.id as cand_id']);           
                // $teamleads_GET =  Candidate::leftjoin('users', 'candidate.added_by', '=', 'users.id')
                // ->where('candidate.IsDeleted' , 0)->where('candidate.added_by' , $id)->orderBy('id', 'DESC')
                // ->get(['candidate.*', 'users.name as emp_name', 'users.id as user_id','candidate.id as cand_id']);
                       
            }
            // dd( $id);
            // $candidate = Candidate::select("*")
            // ->whereIn('added_by', $id)
            // ->get(['candidate.*', 'users.name as emp_name', 'users.id as user_id','candidate.id as cand_id']);

                //  echo "<pre>";
                //  print_r($candidate);
                //  echo "</pre>";
                //  exit();

        }
        elseif (Session::get('user')['user_role'] == 'BRANCH') {
            // dd(Auth::id());
            $tl_add_candidate = User::where('user_role', '=', 'TEAMHEAD')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
            // dd($tl_add_candidate);

            $teamleads_GET = array();
            $id = array();
            $id[] =  Auth::id(); 
            foreach ($tl_add_candidate as $data) {
            $id[] = $data->id;      
            // dd( $id);
                // $teamleads_GET[] = DB::table('candidate')->where('IsDeleted' , 0)->where('added_by','=',$id)->get(['candidate.*', 'users.name as emp_name', 'users.id as user_id','candidate.id as cand_id']);           
                // $teamleads_GET =  Candidate::leftjoin('users', 'candidate.added_by', '=', 'users.id')
                // ->where('candidate.IsDeleted' , 0)->where('candidate.added_by' , $id)->orderBy('id', 'DESC')
                // ->get(['candidate.*', 'users.name as emp_name', 'users.id as user_id','candidate.id as cand_id']);
                       
            }
            // dd( $id);
            //  echo "<pre>";
            //      print_r($id);
            //      echo "</pre>";
               
            $user = User::select("*")
            ->whereIn('added_by', $id)->where('user_role', '=', 'EMPLOYEE')
            ->get();
            // dd( $user);
            $EMP_ID =array();
            // $EMP_ID[] =  Auth::id(); 
            

            foreach ($id as $data) {
                $EMP_ID[] = $data; 
            }

            foreach ($user as $data) {
                $EMP_ID[] = $data->id;   
            }
            // dd( $EMP_ID);

            // $candidate = Candidate::select("*")
            // ->whereIn('added_by', $EMP_ID)
            // ->get(['candidate.*', 'users.name as emp_name', 'users.id as user_id','candidate.id as cand_id']);

            //   echo "<pre>";
            //      print_r($candidate);
            //      echo "</pre>";
            //      exit();

        }

        //
        elseif (Session::get('user')['user_role'] == 'VERTICAL') {
            // dd(Auth::id());
            $tl_add_candidate = User::where('user_role', '=', 'BRANCH')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
            // dd($tl_add_candidate);

            $teamleads_GET = array();
            $id = array();
            $id[] =  Auth::id(); 
            foreach ($tl_add_candidate as $data) {
            $id[] = $data->id;      
            // dd( $id);
                // $teamleads_GET[] = DB::table('candidate')->where('IsDeleted' , 0)->where('added_by','=',$id)->get(['candidate.*', 'users.name as emp_name', 'users.id as user_id','candidate.id as cand_id']);           
                // $teamleads_GET =  Candidate::leftjoin('users', 'candidate.added_by', '=', 'users.id')
                // ->where('candidate.IsDeleted' , 0)->where('candidate.added_by' , $id)->orderBy('id', 'DESC')
                // ->get(['candidate.*', 'users.name as emp_name', 'users.id as user_id','candidate.id as cand_id']);
                       
            }
            // dd( $id);
            //  echo "<pre>";
            //      print_r($id);
            //      echo "</pre>";
               
            $user = User::select("*")
            ->whereIn('added_by', $id)->where('user_role', '=', 'TEAMHEAD')
            ->get();
            // dd( $user);
             // dd( $id);
            //  echo "<pre>";
            //      print_r($user);
            //      echo "</pre>";
            //      exit();
            // $emp_id = array();
            
            foreach ($user as $data) {
                $id[] = $data->id;   
            }
            // dd( $id);

            $user = User::select("*")
            ->whereIn('added_by', $id)->where('user_role', '=', 'EMPLOYEE')
            ->get();
            // dd( $user);
            foreach ($user as $data) {
                $id[] = $data->id;   
            }
            // dd( $id);


            // $EMP_ID_S =array();
            // $EMP_ID_S[] =  Auth::id(); 
            

            // foreach ($id as $data) {
            //     $EMP_ID_S[] = $data; 
            // }

            // foreach ($user as $data) {
            //     $id[] = $data->id;   
            // }
            // dd( $EMP_ID_S);

            // $candidate = Candidate::select("*")
            // ->whereIn('added_by', $id)
            // ->get(['candidate.*', 'users.name as emp_name', 'users.id as user_id','candidate.id as cand_id']);

            //   echo "<pre>";
            //      print_r($candidate);
            //      echo "</pre>";
            //      exit();

        }

        // //////////////////////////////////////////////////////
        else {

            $candidate = Candidate::select("*","candidate.id as cand_id")
            ->where("added_by", "=", Auth::id())
            ->join('leads' , 'leads.candidate_id' , 'candidate.id')
            ->where("candidate.IsDeleted", "=", 0)
            ->orderBy('candidate.id', 'DESC')
            ->groupBy('candidate.added_by')
            ->get();
        }
      // print_r($leads);
        // if($type == "all"){

        //     $candidate = Candidate::select("*","candidate.id as cand_id");
         
        //     if (Session::get('user')['user_role'] != 'ADMIN') {
        //         // $candidate->where("added_by", "=", Auth::id());
        //         $candidate->whereIn('added_by', $id);
        //     }
        //     $candidate->where("candidate.IsDeleted", "=", 0)
        //     ->orderBy('candidate.id', 'DESC')
        //     ->groupBy('candidate.id');
        //     $candidate_List = $candidate->get();

        // }
        // if($type == "attendent"){

            $candidate = Candidate::select("*","candidate.id as cand_id");
            $candidate->where('candidate.IsDeleted' , 0);

            if (Session::get('user')['user_role'] != 'ADMIN') {
                if (Session::get('user')['user_role'] ==  'VERTICAL') {
                    $candidate->whereIn('added_by', $id);
                    $candidate->where('candidate.IsDeleted' , 0);

                }
                elseif (Session::get('user')['user_role'] ==  'BRANCH') {
                    $candidate->whereIn('added_by', $EMP_ID);
                    $candidate->where('candidate.IsDeleted' , 0);

                }
                elseif (Session::get('user')['user_role'] ==  'TEAMHEAD') {
                    $candidate->whereIn('added_by', $team_id);
                    $candidate->where('candidate.IsDeleted' , 0);

                }
                else{
                $candidate->where("added_by", "=", Auth::id());
                $candidate->where('candidate.IsDeleted' , 0);
                }
            }
            // $candidate->join('leads' , 'leads.candidate_id' , 'candidate.id')
            // ->where("candidate.IsDeleted", "=", 0);

      
            if($type == "attendent"){
                $candidate->where("candidate.status", "=", 1);}
            if($type == "unattendent"){
                $candidate->where("candidate.status", "=", 0);
            }

            if($sort == "ASC"){
                $candidate->orderBy('candidate.id', 'ASC');}
            if($sort == "DESC"){
                $candidate->orderBy('candidate.id', 'DESC');
            }

            // $candidate->orderBy('leads.id', 'DESC')
            // ->groupBy('leads.candidate_id');
            $candidate_List = $candidate->get();
            // echo "<pre>";
            //      print_r($candidate_List);
            //      echo "</pre>";
            //      exit();
        // }
    //     if($type == "unattendent"){
    //      $candidate = Candidate::select("*","candidate.id as cand_id");
    //        if (Session::get('user')['user_role'] != 'ADMIN') {
    //             $candidate->where("added_by", "=", Auth::id());
    //         }
    //      $candidate->where("candidate.IsDeleted", "=", 0)
    //      ->where("candidate.status", "=", 0)
    //      ->orderBy('candidate.id', 'DESC')
    //      ->groupBy('candidate.id');
    //         $candidate_List = $candidate->get();
    //  }

    
     
    //  if($type == "ASC"){
    //     if (Session::get('user')['user_role'] != 'ADMIN') {
    //         $candidate->where("added_by", "=", Auth::id());
    //     }
    //     $candidate = DB::table('candidate')
    //     ->select('*','candidate.id AS cand_id')
    //     ->where("candidate.IsDeleted", "=", 0)
    //     ->where("candidate.status", "=", 0)
    //     ->orderBy('candidate.name', 'ASC')
    //     ->groupBy('candidate.id');

    //     $candidate_List = $candidate->get();

    //     }
    
    //     if($type == "DESC"){
    //         if (Session::get('user')['user_role'] != 'ADMIN') {
    //             $candidate->where("added_by", "=", Auth::id());
    //         }
    //         $candidate = DB::table('candidate')
    //         ->select('*','candidate.id AS cand_id')
    //         ->where("candidate.IsDeleted", "=", 0)
    //         ->where("candidate.status", "=", 0)
    //         ->orderBy('candidate.name', 'DESC')
    //         ->groupBy('candidate.id');
    
    //         $candidate_List = $candidate->get();
    
    //         }

     $users = User::where('user_role', "=", "EMPLOYEE")->where("IsDeleted", "=", 0)->orderBy('id', 'DESC')->get();

     $breadcrumbs = [
        ['link' => "dashboard", 'name' => "Dashboard"], ['name' => "Candidate List"]
    ];
    // dd($candidate_List); 

    return view('/content/candidate-list', ['breadcrumbs' => $breadcrumbs , 'companies' => $company , 'recruiters' => $recruiters , 'designations' => $designations , 'candidate' => $candidate_List, 'users' => $users]);


}
public function selected()
{
    $company = Company::get();
    $employees = Company::get();
    $recruiters = Recuiter::get();
    $designations = Designation::get();

    if (Session::get('user')['user_role'] == 'ADMIN') {

      $candidate = DB::table('leads')
      ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')
      ->join('multilead' , 'multilead.lead_id' , '=' , 'leads.id')
      ->join('companies' , 'multilead.company_name' , '=' , 'companies.id')
      ->select('candidate.*','companies.organisation','companies.company_name as comp_name','multilead.*','candidate.id as cand_id','multilead.id as multilead_id')
      ->where('leads.status', 'Shortlisted')
      ->where('multilead.schedule', 4)
      ->where('candidate.status', 1)
      ->where('candidate.IsDeleted' , 0)
      ->orderBy('leads.interview_date','DESC')
      ->get(['*','multilead.updated_at as interview_last_upadte']);
  }else{
   $candidate = DB::table('leads')
   ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')
   ->join('multilead' , 'multilead.lead_id' , '=' , 'leads.id')
   ->join('companies' , 'multilead.company_name' , '=' , 'companies.id')
   ->select('candidate.*','companies.organisation','companies.company_name as comp_name','multilead.*','candidate.id as cand_id','multilead.id as multilead_id')
   ->where('leads.status', 'Shortlisted')
   ->where('multilead.schedule', 4)
   ->where('candidate.status', 1)
   ->where('candidate.added_by', Auth::id())
   ->where('candidate.IsDeleted' , 0)
   ->orderBy('leads.interview_date','DESC')
   ->get(['*','multilead.updated_at as interview_last_upadte']);


}

$users = User::where('user_role', "=", "EMPLOYEE")->where("IsDeleted", "=", 0)->orderBy('id', 'DESC')->get();

$breadcrumbs = [
    ['link' => "dashboard", 'name' => "Dashboard"], ['name' => "Candidate List"]
];

return view('/content/selected-candidate', ['breadcrumbs' => $breadcrumbs , 'companies' => $company ,'employees' => $employees,  'recruiters' => $recruiters , 'designations' => $designations , 'candidate' => $candidate, 'users' => $users]);

}

public function followup_list()
{
    // dd($type);
  $company = Company::get();
  $employees = Company::get();
  $recruiters = Recuiter::get();
  $designations = Designation::get();
  if (Session::get('user')['user_role'] == 'ADMIN') {
    $candidate = DB::table('leads')
    ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')
    ->join('multilead' , 'multilead.lead_id' , '=' , 'leads.id')
    ->join('companies' , 'multilead.company_name' , '=' , 'companies.id')
    ->select('candidate.*','companies.organisation','companies.company_name as comp_name','multilead.*','candidate.id as cand_id')
    ->where('leads.status', 'Shortlisted')
    ->where('candidate.IsDeleted' , 0)
    ->orderBy('multilead.interview_date','DESC')
    ->whereDay('multilead.interview_date',now()->day)
    ->get();


    $type = ['Screening','Shortlisted','Not_Contact','Contact','Rejected','Call_Back'];

    $candidate_followUp = DB::table('leads')
    ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')  
    ->select('candidate.*','leads.*','candidate.id as cand_id')
    ->whereIn('leads.status', $type)
    ->where('candidate.IsDeleted' , 0)
    ->whereDay('leads.remark_date',now()->day)
    ->get();

    // dd($candidate_followUp);

}

elseif (Session::get('user')['user_role'] == 'VERTICAL') {
    // dd(Auth::id());
    $tl_add_candidate = User::where('user_role', '=', 'BRANCH')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
    $teamleads_GET = array();
    $id = array();
    $id[] =  Auth::id(); 
    foreach ($tl_add_candidate as $data) {
    $id[] = $data->id;        
    }
    // dd($id);

    $user = User::select("*")
    ->whereIn('added_by', $id)->where('user_role', '=', 'TEAMHEAD')
    ->get();
  
    foreach ($user as $data) {
        $id[] = $data->id;   
    }
    // dd( $id);
    $user = User::select("*")
    ->whereIn('added_by', $id)->where('user_role', '=', 'EMPLOYEE')
    ->get();
    // dd( $user);
    foreach ($user as $data) {
        $id[] = $data->id;   
    }

   $candidate = DB::table('leads')
   ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')
   ->join('multilead' , 'multilead.lead_id' , '=' , 'leads.id')
   ->join('companies' , 'multilead.company_name' , '=' , 'companies.id')
   ->select('candidate.*','companies.organisation','companies.company_name as comp_name','multilead.*','candidate.id as cand_id')
   ->where('leads.status', 'Shortlisted')
//    ->where('leads.updated_by',Auth::id() )
   ->whereIn('leads.updated_by',$id )
   ->where('candidate.IsDeleted' , 0)
   ->orderBy('multilead.interview_date','DESC')
   ->whereDay('multilead.interview_date',now()->day)
   ->get();


    $candidate_followUp = '';

    $type = ['Screening','Shortlisted','Not_Contact','Contact','Rejected','Call_Back'];
    $candidate_followUp = DB::table('leads')
    ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')  
    ->select('candidate.*','leads.*','candidate.id as cand_id')
    ->whereIn('leads.status', $type)
    ->whereIn('leads.updated_by',$id )
    ->where('candidate.IsDeleted' , 0)
    ->whereDay('leads.remark_date',now()->day)
    ->get();

    // dd($candidate_followUp);

   }
   elseif (Session::get('user')['user_role'] == 'BRANCH') {
       $tl_add_candidate = User::where('user_role', '=', 'TEAMHEAD')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
       $teamleads_GET = array();
       $id = array();
       $id[] =  Auth::id(); 
       foreach ($tl_add_candidate as $data) {
       $id[] = $data->id;       
       }
                 
       $user = User::select("*")
       ->whereIn('added_by', $id)->where('user_role', '=', 'EMPLOYEE')
       ->get();
       $EMP_ID =array();
       // $EMP_ID[] =  Auth::id(); 
       foreach ($id as $data) {
           $EMP_ID[] = $data; 
       }

       foreach ($user as $data) {
           $EMP_ID[] = $data->id;   
       }
       // dd( $EMP_ID);
       $candidate = DB::table('leads')
       ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')
       ->join('multilead' , 'multilead.lead_id' , '=' , 'leads.id')
       ->join('companies' , 'multilead.company_name' , '=' , 'companies.id')
       ->select('candidate.*','companies.organisation','companies.company_name as comp_name','multilead.*','candidate.id as cand_id')
       ->where('leads.status', 'Shortlisted')
    //    ->where('leads.updated_by',Auth::id() )
       ->whereIn('leads.updated_by', $EMP_ID)
       ->where('candidate.IsDeleted' , 0)
       ->orderBy('multilead.interview_date','DESC')
       ->whereDay('multilead.interview_date',now()->day)
       ->get();

       $candidate_followUp = '';

    $type = ['Screening','Shortlisted','Not_Contact','Contact','Rejected','Call_Back'];
    $candidate_followUp = DB::table('leads')
    ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')  
    ->select('candidate.*','leads.*','candidate.id as cand_id')
    ->whereIn('leads.status', $type)
    ->whereIn('leads.updated_by',$EMP_ID )
    ->where('candidate.IsDeleted' , 0)
    ->whereDay('leads.remark_date',now()->day)
    ->get();
   
       }
       elseif (Session::get('user')['user_role'] == 'TEAMHEAD') {
           // dd(Auth::id());
           $tl_add_candidate = User::where('user_role', '=', 'EMPLOYEE')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
           $teamleads_GET = array();
           $team_id = array();
           $team_id[] =  Auth::id(); 
           foreach ($tl_add_candidate as $data) {
           $team_id[] = $data->id;               
           }
            //   dd($team_id);
               $candidate = DB::table('leads')
               ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')
               ->join('multilead' , 'multilead.lead_id' , '=' , 'leads.id')
               ->join('companies' , 'multilead.company_name' , '=' , 'companies.id')
               ->select('candidate.*','companies.organisation','companies.company_name as comp_name','multilead.*','candidate.id as cand_id')
               ->where('leads.status', 'Shortlisted')
            //    ->where('leads.updated_by',Auth::id() )
               ->whereIn('leads.updated_by', $team_id)
               ->where('candidate.IsDeleted' , 0)
               ->orderBy('multilead.interview_date','DESC')
               ->whereDay('multilead.interview_date',now()->day)
               ->get();

               $candidate_followUp = '';

                $type = ['Screening','Shortlisted','Not_Contact','Contact','Rejected','Call_Back'];
                $candidate_followUp = DB::table('leads')
                ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')  
                ->select('candidate.*','leads.*','candidate.id as cand_id')
                ->whereIn('leads.status', $type)
                ->whereIn('leads.updated_by',$team_id )
                ->where('candidate.IsDeleted' , 0)
                ->whereDay('leads.remark_date',now()->day)
                ->get();

       }
    else{
    $candidate = DB::table('leads')
    ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')
    ->join('multilead' , 'multilead.lead_id' , '=' , 'leads.id')
    ->join('companies' , 'multilead.company_name' , '=' , 'companies.id')
    ->select('candidate.*','companies.organisation','companies.company_name as comp_name','multilead.*','candidate.id as cand_id')
    ->where('leads.status', 'Shortlisted')
    ->where('candidate.IsDeleted' , 0)
    ->where('candidate.added_by' , Auth::id())
    ->orderBy('multilead.interview_date','DESC')
    ->whereDay('multilead.interview_date',now()->day)
    ->get();
}
$users = User::where('user_role', "=", "EMPLOYEE")->where("IsDeleted", "=", 0)->orderBy('id', 'DESC')->get();

$breadcrumbs = [
    ['link' => "dashboard", 'name' => "Dashboard"], ['name' => "Candidate List"]
];
$date_current = '';


if (request()->start_date || request()->end_date) {
    $start_date = Carbon::parse(request()->start_date)->toDateTimeString();
    $end_date = Carbon::parse(request()->end_date)->toDateTimeString();
    $date_current = Candidate::whereBetween('created_at',[$start_date,$end_date])->get();
} else {
    $date_current = $candidate;
}

return view('/content/followup_list', ['date_current'=> $date_current,'breadcrumbs' => $breadcrumbs , 'companies' => $company ,'employees' => $employees,  'recruiters' => $recruiters , 'designations' => $designations , 'candidate' => $candidate, 'users' => $users,'candidate_followUp'=>$candidate_followUp]);

}


public function getCandidateData(Request $request)
{
    $id = $request->location_id;
        // dd($id);
    $candidate = Candidate::where('id', $id)->first();

    return response()->json([
        'data' => $candidate
    ]);
}

public function fetchCandidateDetails(Request $request)
{
    $id =$request->candidate_id;
        // dd($id);
    $candidate['candidate'] = Candidate::where('id', $id)->first();

    return response()->json([
        'candidate' => $candidate
    ]);

}

public function getCityStatePincode()
{
    $pincode = $_GET['pincode'];

        // dd($pincode);
    $data =  PincodeLocation::where('pincode_locations', $pincode)->get()->toArray();

        // dd($data);

    if (!empty($data)) {
        $arr['city'] = $data[0]['district'];
        $arr['state'] = $data[0]['state'];
        $arr['country'] = $data[0]['country'];
        foreach ($data as $value) {
            $arr['location'][] = $value['location'];
        }
        echo json_encode($arr);
    } else {
        echo 'no';
    }
}

public function thankyou()
{
    return view('frontend/thankyou');
}

public function excel_uploade()
{
        // $breadcrumbs = [
        //     ['link' => "dashboard", 'name' => "Dashboard"], ['name' => "Dashboard"]
        // ];
        // return view('content.add-excel-list',['breadcrumbs' => $breadcrumbs] );
    return view('content.add-excel-list');
}

public function assign_emp(Request $request)
{
        // dd($request->all());

    $adduser  = $request->candidate;

    $name = $request->name;


    $ids = explode(',', $adduser);


        // print_r($ids);

    foreach ($ids as $id) {

        $emp_assign =  DB::update('update candidate set added_by = ? where id = ?', [$name, $id]);
    }

    // return response()->json();
    return true;
}

public function candidateUpdate($id)
{

    $candidate = Candidate::where('id', $id)->first();
    $breadcrumbs = [
        ['link' => "candidate", 'name' => "Candidate List"], ['name' => "Update Candidate Details"]
    ];
    // print_r($candidate);
    return view('/content/update-candidate-list', ['breadcrumbs' => $breadcrumbs , 'candidate' => $candidate]);
}

public function store(Request $request)
{
    if (Candidate::where('email', $request->email)->exists()) {
        return back()->with('error', 'Email alrady exist ');
    }
    if (Candidate::where('number', $request->number)->exists()) {
        return back()->with('error', 'Please Use Other Number ');
    } else {

        $candidate = new Candidate();
        $candidate->name = $request->name;
        $candidate->number = $request->number;
        $candidate->email = $request->email;
        $candidate->job = $request->job;
        $candidate->skill = $request->skill;
        $candidate->education = $request->education;
        $candidate->areaofintrest = $request->areaofintrest;
        $candidate->dob = $request->dob;
        $candidate->gender = $request->gender;
        // $candidate->organisation = $request->organisation;
        $candidate->dob_number = $request->dob_number;

        $candidate->added_by = Auth::id();

        if ($request->file()) {
            $file = $request->file('resume');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('documents/resume/', $filename);
        }else{
            $filename = '';
        }

        $candidate->resume = $filename;
        $candidate->save();
        $last_id =   $candidate->id;
        $index = count($request->board);
        $index1 = count($request->company_name);

        $qualification[$index] = [
            $board = $request->board,
            $year = $request->year,
            $institute = $request->institute,
        ];

        $workExperience[$index1] = [
            $company_name = $request->company_name,
            $designation = $request->designation,
            $location = $request->location,
            $ctc = $request->ctc,
            $department = $request->department,
            $sDate = $request->start_date,
            $eDate = $request->end_date,
        ];
        for ($i = 0; $i < $index; $i++) {
                    // dd($i);
            Qualification::insert([
                'board_name' => $board[$i],
                'passing_year' => $year[$i],
                'institute' => $institute[$i],
                'candidate_id' => $last_id,
            ]);
        }

        for ($i = 0; $i < $index1; $i++) {

                    // dd($i);
            WorkExperience::insert([
                'company_name' => $company_name[$i],
                'designation' => $designation[$i],
                'location' => $location[$i],
                'ctc' => $ctc[$i],
                'department' => $department[$i],
                'start_date' => $sDate[$i],
                'end_date' => $eDate[$i],
                'candidate_id' => $last_id,
            ]);
        }
        $name=$request->name;
        $email=$request->email;
        return redirect("sendbasicemail/$name/$email/")->with('message', 'State saved correctly!');

        // return redirect('getleadlist/all')->with('message', 'Candidate has been Added Successfully');
    }
}

public function update_candidate(Request $request)
{
    $candidate = Candidate::where('id', $request->candidate_id)->first();

    $candidate->name = $request->name;
    $candidate->number = $request->number;
    $candidate->email = $request->email;
    $candidate->job = $request->job;
    $candidate->skill = $request->skill;
    $candidate->education = $request->education;
    $candidate->areaofintrest = $request->areaofintrest;
    $candidate->organisation = $request->organisation;
    $candidate->dob = $request->dob;
    $candidate->dob_number = $request->dob_number;



    if (!$request->resume == '') {
        $file = $request->file('resume');
        $extention = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extention;
        $file->move('documents/resume/', $filename);
        $candidate->resume = $filename;
    }

    $candidate->update();

    $last_id =   $candidate->id;

    $deleteQualification = DB::table('qualification')->where('candidate_id' , $last_id);
// dd($delete);
    $deleteQualification->delete();

    $deleteWorkExperience = DB::table('work_experience')->where('candidate_id' , $last_id);
        // dd($delete);
    $deleteWorkExperience->delete();

    if(!empty($request->board))
    {
        $index = count($request->board);
        $qualification[$index] = [
            $board = $request->board,
            $year = $request->year,
            $institute = $request->institute,
        ];
        for ($i = 0; $i < $index; $i++) {
                // dd($i);
            Qualification::insert([
                'board_name' => $board[$i],
                'passing_year' => $year[$i],
                'institute' => $institute[$i],
                'candidate_id' => $last_id,
            ]);
        }
    }


    if(!empty($request->company_name))
    {
        $index1 = count($request->company_name);
        $workExperience[$index1] = [
            $company_name = $request->company_name,
            $designation = $request->designation,
            $location = $request->location,
            $ctc = $request->ctc,
            $department = $request->department,
            $sDate = $request->start_date,
            $eDate = $request->end_date,
        ];
        for ($i = 0; $i < $index1; $i++) {

                // dd($i);
            WorkExperience::insert([
                'company_name' => $company_name[$i],
                'designation' => $designation[$i],
                'location' => $location[$i],
                'ctc' => $ctc[$i],
                'department' => $department[$i],
                'start_date' => $sDate[$i],
                'end_date' => $eDate[$i],
                'candidate_id' => $last_id,
            ]);
        }
    }

    // Redirect::to($request->request->get('http_referrer'));
    // return back()->with('message', 'Candidate has been Updated Successfully');
    return redirect('getleadlist/all')->with('message', 'Candidate has been Updated Successfully');

}


public function lead_Candidate_add(Request $request)
{
    $id = $request->candidateId;

        // dd($id);
    $candidate = Candidate::where('id', $id)->first();
        // $candidate = DB::table('leads')
        //                 ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')

        //                 ->first();
                        // dd($candidate);
    $company = Company::get();

    $recruiters = Recuiter::get();

    $designations = Designation::get();

        // dd($recruiter);

    $leads = DB::table('leads')
    ->leftjoin('companies' , 'companies.id' , '=' , 'leads.company_id')
    ->leftjoin('recuiters' , 'recuiters.id' , '=' , 'leads.recruiter_name')
    ->leftjoin('designation' , 'designation.id' , '=' , 'leads.designation_name')
    ->leftjoin('vartical' , 'vartical.id' , '=' , 'leads.vartical_name')
    ->leftjoin('vacancy_location' , 'vacancy_location.pincode_id' , '=' , 'leads.location_id')
    ->where('candidate_id', $candidate->id)->orderByDesc('leads.id')->get();
                            // dd($leads);

        // $leads = DB::table('leads')->where('candidate_id', $id)->orderByDesc('id')->get();
    $qualificationData = Qualification::where ('candidate_id' , $candidate->id)->get();
    $workExperience = WorkExperience::where ('candidate_id' , $candidate->id)->get();

                            // dd($workExperience);

    $docs = DB::table('document')->where('candidate_id', $id)->orderByDesc('id')->first();
    $breadcrumbs = [
        ['link' => "candidate", 'name' => "Candidate List"], ['name' => "Candidate List"]
    ];
    return view('/content/add-new-lead-list', ['breadcrumbs' => $breadcrumbs, 'id' => $id, 'leads' => $leads, 'docs' => $docs , 'candidate'=> $candidate , 'qualificationdatas'=> $qualificationData , 'workexperiences'=> $workExperience , 'companies' => $company , 'recruiters' => $recruiters , 'designations' => $designations]);
}

public function lead_update(Request $request, $id)
{
        // $candidate = new Candidate;
    $candidate = Candidate::where('id', $id)->first();

    $candidate->status = $request->value;
    $candidate->candidate_id = $request->candidate_id;

    $candidate->update();

    // header('Location: ' . $_SERVER['HTTP_REFERER']);
    return redirect('candidate')->with('message', 'Candidate has been Added Successfully');
}

public function resume_uploade(Request $request, $id)
{

    $team = new Document();

    $team->candidate_id = $request->candidate_id;

    $team->ofc_joining_date = $request->ofc_joining_date;

    if (!empty($file = $request->file('ssc'))) {
        $file = $request->file('ssc');
        $extention = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extention;
        $file->move('documents/selected/ssc/', $filename);
        $team->ssc = $filename;
    }

    if (!empty($file = $request->file('hsc'))) {
        $file = $request->file('hsc');
        $extention = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extention;
        $file->move('documents/selected/hsc/', $filename);
        $team->hsc = $filename;
    }

    if (!empty($file = $request->file('degree'))) {
        $file = $request->file('degree');
        $extention = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extention;
        $file->move('documents/selected/degree/', $filename);
        $team->degree = $filename;
    }

    if (!empty($file = $request->file('offer_latter'))) {
        $file = $request->file('offer_latter');
        $extention = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extention;
        $file->move('documents/selected/offer_latter/', $filename);
        $team->offer_letter = $filename;
    }

    if (!empty($file = $request->file('sallery_slip'))) {
        $file = $request->file('sallery_slip');
        $extention = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extention;
        $file->move('documents/selected/sallery_slip/', $filename);
        $team->sallery_slip = $filename;
    }

    if (!empty($file = $request->file('reg_later'))) {
        $file = $request->file('reg_later');
        $extention = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extention;
        $file->move('documents/selected/reg_later/', $filename);
        $team->reg_letter = $filename;
    }
    if (!empty($file = $request->file('pan_card'))) {
        $file = $request->file('pan_card');
        $extention = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extention;
        $file->move('documents/selected/pancard/', $filename);
        $team->pan_card = $filename;
    }
    if (!empty($file = $request->file('adhar_card'))) {
        $file = $request->file('adhar_card');
        $extention = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extention;
        $file->move('documents/selected/adharcard/', $filename);
        $team->aadhar_card = $filename;
    }

    $team->save();
    // return back()->with('message', 'Document Updation has been Added Successfully');
    return redirect('lead-add/'.$request->candidate_id)->with('message', 'Document Updation has been Added Successfully');

}

public function frontendadd(Request $request)
{
    if ($request->referral_id != '') {

        $added_by = Employee::where('referral_id', $request->referral_id)->first();
        if (!empty($added_by)) {
            $added_by = ($added_by->id);
        } else {
            $added_by = 1;
        }
    } else {
        $added_by = 1;
    }

    $data['email'] = $request->email;
    $data['number'] = $request->number;
    $rules_email = array('email' => 'unique:candidate,email');
    $rules_number = array('number' => 'unique:candidate,number');
    $validato_email = Validator::make($data, $rules_email);
    $validator_number = Validator::make($data, $rules_number);

    if ($validato_email->fails()) {
        return back()->with('message', 'Email address is already added');
    }
    if ($validator_number->fails()) {
        return back()->with('message', 'Phone number is alrady added ');

    }
    $candidate = new Candidate;
    $candidate->frontend = "front_end_data";
    $candidate->name = $request->name;
    $candidate->number = $request->number;
    $candidate->email = $request->email;
    $candidate->job = $request->job;
    $candidate->skill = $request->skill;
    $candidate->education = $request->education;
    $candidate->areaofintrest = $request->areaofintrest;
    $candidate->added_by = $added_by;

    if (!empty($file = $request->file('resume'))) {
        $file = $request->file('resume');
        $extention = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extention;
        $file->move('documents/frontend/resume/', $filename);
        $candidate->resume = $filename;
    }
    if (!empty($vedio = $request->file('vedio'))) {
        $vedio = $request->file('vedio');
        $Vextention = $vedio->getClientOriginalExtension();
        $vedioname = time() . '.' . $Vextention;
        $vedio->move('documents/frontend/vedio/', $vedioname);
        $candidate->vedio = $vedioname;
    }
    // dd($candidate);
    $candidate->save();
    return redirect('thankyou')->with('message', 'Candidate has been Added Successfully');
}


public function importExcel(Request $request)
{
    $request->validate([
        'excel_file' => 'required',
            // 'email' => 'required|email|unique:candidate',
    ]);
    try {
            // dd($request->file('excel_file'));

            // Excel::import(new CandidateImport,  $request->excel_file);
        Excel::import(new ExcelImport, $request->excel_file);
        return redirect('admin/candidate/show')->with('message', 'Excel Imported Successfully');
    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        $failures = $e->failures();
            // dd($failures);
        return redirect('admin/candidate/show')->with('import_error', $failures);
    }
}


public function exportExcel(Request $request)
{

    try {
            // dd($request->file('excel_file'));
        return Excel::download(new CandidateExport, 'candidate.csv');

        exit;

            // Excel::import(new CandidateImport,  $request->excel_file);
        Excel::import(new CandidateExport, $request->excel_file);
        return redirect('candidate')->with('message', 'Excel Importeds Successfully');
    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        $failures = $e->failures();
            // dd($failures);
        return redirect('candidate')->with('import_error', $failures);
    }
}

public function show(Candidate $candidate)
{
    return view('content.resume-formate');
}




public function destroy($id)
{
        // Candidate::find($id)->delete();
    $candidate = Candidate::where('id', $id)->first();
        // dd($candidate);
        // $getQ = DB::table('qualification')->limit(1)->get();

        // if ($getQ->candidate_id == $candidate->id) {
    DB::table('qualification')->where('candidate_id' , '=' , $id)->delete();
    DB::table('work_experience')->where('candidate_id' , '=' , $id)->delete();
        // }
        // $candidate->delete();
    $candidate->IsDeleted = 1;

    $candidate->update();




    return back()->with('message', 'Candidate has been deleted Successfully');;
}
public function deleteQualification(Request $request)
{

        // dd($request);
    DB::table('qualification')->where([
        'id' => $request->id
    ])->limit(1)->delete();
    return true;
}
public function deleteWorkexperience(Request $request)
{
    DB::table('work_experience')->where([
        'id' => $request->id
    ])->limit(1)->delete();
    return true;
}


public function getfollowupcardview(Request $request)
{
    $company = Company::get();
  $employees = Company::get();
  $recruiters = Recuiter::get();
  $designations = Designation::get();
  if (Session::get('user')['user_role'] == 'ADMIN') {
    $candidate = DB::table('leads')
    ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')
    ->join('multilead' , 'multilead.lead_id' , '=' , 'leads.id')
    ->join('companies' , 'multilead.company_name' , '=' , 'companies.id')
    ->select('candidate.*','companies.organisation','companies.company_name as comp_name','multilead.*','candidate.id as cand_id')
    ->where('leads.status', 'Shortlisted')
    ->where('candidate.IsDeleted' , 0)
    ->orderBy('multilead.interview_date','DESC')
    ->whereDay('multilead.interview_date',now()->day)
    ->get();

    }elseif (Session::get('user')['user_role'] == 'VERTICAL') {
         // dd(Auth::id());
         $tl_add_candidate = User::where('user_role', '=', 'BRANCH')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
         // dd($tl_add_candidate);

         $teamleads_GET = array();
         $id = array();
         $id[] =  Auth::id(); 
         foreach ($tl_add_candidate as $data) {
         $id[] = $data->id;        
         }

         $user = User::select("*")
         ->whereIn('added_by', $id)->where('user_role', '=', 'TEAMHEAD')
         ->get();
       
         foreach ($user as $data) {
             $id[] = $data->id;   
         }
         $user = User::select("*")
         ->whereIn('added_by', $id)->where('user_role', '=', 'EMPLOYEE')
         ->get();
         // dd( $user);
         foreach ($user as $data) {
             $id[] = $data->id;   
         }
        $candidate = DB::table('leads')
        ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')
        ->join('multilead' , 'multilead.lead_id' , '=' , 'leads.id')
        ->join('companies' , 'multilead.company_name' , '=' , 'companies.id')
        ->select('candidate.*','companies.organisation','companies.company_name as comp_name','multilead.*','candidate.id as cand_id')
        ->where('leads.status', 'Shortlisted')
        // ->where('leads.updated_by',Auth::id() )
        ->whereIn('leads.updated_by',$id )
        ->where('candidate.IsDeleted' , 0)
        ->orderBy('multilead.interview_date','DESC')
        ->whereDay('multilead.interview_date',now()->day)
        ->get();
    
        }
        elseif (Session::get('user')['user_role'] == 'BRANCH') {
            $tl_add_candidate = User::where('user_role', '=', 'TEAMHEAD')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
            // dd(Auth::id()); 

            $teamleads_GET = array();
            $id = array();
            $id[] =  Auth::id(); 
            foreach ($tl_add_candidate as $data) {
            $id[] = $data->id;       
            }
                      
            $user = User::select("*")
            ->whereIn('added_by', $id)->where('user_role', '=', 'EMPLOYEE')
            ->get();
            // dd( $user);
            $EMP_ID =array();
            // $EMP_ID[] =  Auth::id(); 
            foreach ($id as $data) {
                $EMP_ID[] = $data; 
            }

            foreach ($user as $data) {
                $EMP_ID[] = $data->id;   
            }
            
            $candidate = DB::table('leads')
            ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')
            ->join('multilead' , 'multilead.lead_id' , '=' , 'leads.id')
            ->join('companies' , 'multilead.company_name' , '=' , 'companies.id')
            ->select('candidate.*','companies.organisation','companies.company_name as comp_name','multilead.*','candidate.id as cand_id')
            ->where('leads.status', 'Shortlisted')
            // ->where('leads.updated_by',Auth::id() )
            ->whereIn('leads.updated_by', $EMP_ID)
            ->where('candidate.IsDeleted' , 0)
            ->orderBy('multilead.interview_date','DESC')
            ->whereDay('multilead.interview_date',now()->day)
            ->get();
        
            }
            elseif (Session::get('user')['user_role'] == 'TEAMHEAD') {
                // dd(Auth::id());
                $tl_add_candidate = User::where('user_role', '=', 'EMPLOYEE')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
                // dd($tl_add_candidate);
    
                $teamleads_GET = array();
                $team_id = array();
                $team_id[] =  Auth::id(); 
                foreach ($tl_add_candidate as $data) {
                $team_id[] = $data->id;      
                }
                    $candidate = DB::table('leads')
                    ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')
                    ->join('multilead' , 'multilead.lead_id' , '=' , 'leads.id')
                    ->join('companies' , 'multilead.company_name' , '=' , 'companies.id')
                    ->select('candidate.*','companies.organisation','companies.company_name as comp_name','multilead.*','candidate.id as cand_id')
                    ->where('leads.status', 'Shortlisted')
                    // ->where('leads.updated_by',Auth::id() )
                    ->whereIn('leads.updated_by', $team_id)
                    ->where('candidate.IsDeleted' , 0)
                    ->orderBy('multilead.interview_date','DESC')
                    ->whereDay('multilead.interview_date',now()->day)
                    ->get();
    
            }
    else{
        $candidate = DB::table('leads')
        ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')
        ->join('multilead' , 'multilead.lead_id' , '=' , 'leads.id')
        ->join('companies' , 'multilead.company_name' , '=' , 'companies.id')
        ->select('candidate.*','companies.organisation','companies.company_name as comp_name','multilead.*','candidate.id as cand_id')
        ->where('leads.status', 'Shortlisted')
        ->where('candidate.IsDeleted' , 0)
        ->where('candidate.added_by' , Auth::id())
        ->orderBy('multilead.interview_date','DESC')
        ->whereDay('multilead.interview_date',now()->day)
        ->get();
    }
    $users = User::where('user_role', "=", "EMPLOYEE")->where("IsDeleted", "=", 0)->orderBy('id', 'DESC')->get();
    $breadcrumbs = [
        ['link' => "dashboard", 'name' => "Dashboard"], ['name' => "Candidate List"]
    ];
    // dd($candidate); 
    return view('/content/followup_cardview', ['breadcrumbs' => $breadcrumbs, 'companies' => $company ,'employees' => $employees,  'recruiters' => $recruiters , 'designations' => $designations , 'candidate' => $candidate, 'users' => $users]);

    }

}
