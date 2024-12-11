<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;

use App\Models\Company;
use App\Models\Recuiter;
use App\Models\TeamLead;
use App\Models\Candidate;
use App\Models\Designation;
use Illuminate\Http\Request;
use App\Models\Qualification;
use App\Models\WorkExperience;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Session;
class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $lead = Lead::all();

      if (Session::get('user')['user_role'] == 'ADMIN') {

        $lead = DB::table('leads')
        ->join('users' , 'leads.updated_by' , 'users.id')
        ->join('candidate' , 'leads.candidate_id' , 'candidate.id')
        ->join('multilead' , 'leads.id' , 'multilead.lead_id')
        ->select('candidate.*','leads.id as lead_id','multilead.schedule','multilead.id as multilead_id','users.name as empname' , 'users.email as empemail' , 'users.contact as usercontact', 'leads.id as ids','leads.status as canstatus','leads.candidate_id as cand_id','multilead.schedule as multischedule','multilead.is_joined')
        ->where('leads.IsDeleted' , 0)
        ->groupBy('leads.candidate_id')
        ->where('candidate.IsDeleted',0)
        ->orderBy('leads.id', 'DESC')
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
         $id[] = 1;
         $user = User::select("*")
         ->whereIn('added_by', $id)->where('user_role', '=', 'EMPLOYEE')
         ->get();
         // dd( $user);
         foreach ($user as $data) {
             $id[] = $data->id;   
         }
         $lead = DB::table('leads')
         ->join('users' , 'leads.updated_by' , 'users.id')
         ->join('candidate' , 'leads.candidate_id' , 'candidate.id')
         ->join('multilead' , 'leads.id' , 'multilead.lead_id')
         ->select('candidate.*','leads.id as lead_id','multilead.id as multilead_id','multilead.schedule','users.name as empname' , 'users.email as empemail' , 'users.contact as usercontact', 'leads.id as ids','leads.status as canstatus','leads.candidate_id as cand_id','multilead.schedule as multischedule','multilead.is_joined')
         ->where('leads.IsDeleted' , 0)
         ->whereIn('leads.updated_by',$id )
         ->groupBy('leads.candidate_id')
         ->where('candidate.IsDeleted',0)
         ->orderBy('leads.id', 'DESC')
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
            
            $lead = DB::table('leads')
         ->join('users' , 'leads.updated_by' , 'users.id')
         ->join('candidate' , 'leads.candidate_id' , 'candidate.id')
         ->leftJoin('multilead' , 'leads.id' , 'multilead.lead_id')
         ->select('candidate.*','leads.id as lead_id','multilead.id as multilead_id','multilead.schedule','users.name as empname' , 'users.email as empemail' , 'users.contact as usercontact', 'leads.id as ids','leads.status as canstatus','leads.candidate_id as cand_id','multilead.schedule as multischedule','multilead.is_joined')
         ->where('leads.IsDeleted' , 0)
         ->whereIn('leads.updated_by',$EMP_ID )
         ->groupBy('leads.candidate_id')
         ->where('candidate.IsDeleted',0)
         ->orderBy('leads.id', 'DESC')
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
                $lead = DB::table('leads')
                ->join('users' , 'leads.updated_by' , 'users.id')
                ->join('candidate' , 'leads.candidate_id' , 'candidate.id')
                ->leftJoin('multilead' , 'multilead.schedule','leads.id' , 'multilead.lead_id')
                ->select('candidate.*','leads.id as lead_id','multilead.id as multilead_id','users.name as empname' , 'users.email as empemail' , 'users.contact as usercontact', 'leads.id as ids','leads.status as canstatus','leads.candidate_id as cand_id','multilead.schedule as multischedule' ,'multilead.is_joined')
                ->where('leads.IsDeleted' , 0)
                ->whereIn('leads.updated_by',$team_id )
                ->groupBy('leads.candidate_id')
                ->where('candidate.IsDeleted',0)
                ->orderBy('leads.id', 'DESC')
                ->get();
    
            }
    else{
       $lead = DB::table('leads')
       ->join('users' , 'leads.updated_by' , 'users.id')
       ->join('candidate' , 'leads.candidate_id' , 'candidate.id')
       ->leftJoin('multilead' , 'leads.id' , 'multilead.lead_id')
       ->select('candidate.*','leads.id as lead_id','multilead.id as multilead_id','users.name as empname' , 'users.email as empemail' , 'users.contact as usercontact', 'leads.id as ids','leads.status as canstatus','leads.candidate_id as cand_id','multilead.schedule as multischedule','multilead.is_joined')
       ->where('leads.IsDeleted' , 0)
       ->groupBy('leads.candidate_id')
       ->where('candidate.IsDeleted',0)
       ->where('candidate.added_by',Auth::id())
       ->orderBy('leads.id', 'DESC')
       ->get();
   }

   $breadcrumbs = [
    ['link' => "dashboard", 'name' => "Dashboard"], ['name' => "Lead List"]
];
return view('/content/lead-list', ['breadcrumbs' => $breadcrumbs, 'lead' => $lead]);
}

public function lead_add(Request $request,$id = null)
{
    if ($id == '') {
     $id = $request->leadId;

 }
 $candidate = DB::table('leads')
 ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')
 ->where('candidate.id', $id)
 ->first(['*','candidate.id as cand_id']);

 if (empty($candidate)) {
    $candidate = Candidate::leftjoin('users', 'candidate.added_by', '=', 'users.id')
    ->where('candidate.IsDeleted' , 0)->orderBy('id', 'DESC')
    ->where('candidate.id', $id)
    ->first(['candidate.*', 'users.name as emp_name', 'users.id as user_id','candidate.id as cand_id']);
}

$company = Company::leftjoin('vacancy_location', 'vacancy_location.company_id', '=', 'companies.id')
->where('companies.IsDeleted' , 0)->orderBy('companies.id', 'DESC')->groupBy('vacancy_location.company_id')
->get(['companies.*','companies.id as comp_id']);

$recruiters = Recuiter::get();

$designations = Designation::get();

$leads = DB::table('leads')

->leftJoin('companies' , 'companies.id' , '=' , 'leads.company_id')
->leftJoin('recuiters' , 'recuiters.id' , '=' , 'leads.recruiter_name')
->leftJoin('designation' , 'designation.id' , '=' , 'leads.designation_name')
->leftJoin('vartical' , 'vartical.id' , '=' , 'leads.vartical_name')
                            // ->leftJoin('vacancy_location' , 'vacancy_location.pincode_id' , '=' , 'leads.location_id')
->where('leads.candidate_id', $candidate->id)->orderByDesc('leads.id')->get(['*','leads.id as ids','leads.created_at as con_time']);

                            // dd($leads);

$qualificationData = Qualification::where ('candidate_id' , $candidate->id)->get();
// dd($qualificationData);
$workExperience = WorkExperience::where ('candidate_id' , $candidate->id)->get();

$docs = DB::table('document')->where('candidate_id', $id)->orderByDesc('id')->get();

$recruitersOffice = DB::table('leads')
->join('recuiter_branch' , 'recuiter_branch.id' , 'leads.recruiter_branch')
->join('designation' , 'designation.id' , 'leads.recruiter_designation')
->join('recuiters' , 'recuiters.id' , 'leads.recruiter_name')
->get();
                            // dd($recruitersOffice);

$breadcrumbs = [
    ['link' => "lead", 'name' => "Lead List"], ['name' => "Candidate List"]
];
return view('/content/add-new-lead-list', ['breadcrumbs' => $breadcrumbs, 'id' => $id, 'leads' => $leads, 'docs' => $docs , 'candidate'=> $candidate , 'qualificationdatas'=> $qualificationData , 'workexperiences'=> $workExperience , 'companies' => $company , 'recruiters' => $recruiters , 'designations' => $designations , 'recruitersOffice' => $recruitersOffice ]);
}


public function store(Request $request)
{
        // dd($request->all());

    $team = new Lead();
        // $team->round = 1;
    $team->candidate_id = $request->candidate_id;

        // $team->organization  = $request->organisation;
    $team->status = $request->status;



    if($request->schedule == "company_office")
    {

        $team->company_id = $request->company_name;

        $team->vacancy_id = $request->vacancy_id;

        $team->vartical_name = $request->vartical_name;

        $team->designation_name = $request->designation_name;

        $team->location_id = $request->branch_location;

        $team->position_id  = $request->position_name;

        $team->ofc_visit_date = $request->company_visit_date;

        $team->status_remark = $request->company_remark;
            // dd($team->status_remark);
    }

    if($request->schedule == "recruiter_office")
    {
        $team->recruiter_branch = $request->recruiter_branch;

        $team->recruiter_visit_date = $request->recruiter_visit_date;

        $team->recruiter_name = $request->recruiter_name;

        $team->recruiter_designation = $request->recruiter_designation;

        $team->status_remark = $request->recruiter_remark;

            // $team->location_id = $request->online_location_name;
    }

    if($request->schedule == "Online")
    {
        $team->company_id = $request->int_com_name;

        $team->designation = $request->int_des_name;

        $team->ofc_visit_date = $request->int_des_date;

        $team->status_remark = $request->int_remark;

    }



    $team->updated_by = Auth::id();

    $team->status_remark = $request->status_remark;
    $team->not_contact = $request->not_contact;

    $team->not_answer = $request->not_answer;

    $team->status_remark = $request->not_answer_date;

    $team->status_remark = $request->call_back;

    $team->call_back_date = $request->call_back_date;

    $team->wrong_no = $request->wrong_no;
// santosh
    if($request->status == 'Screening' ){
        $team->remark_date = $request->company_visit_scr_date;
        $team->status_remark = $request->company_scr_remark;
    }
    if($request->status == 'Call_Back' ){
        $team->remark_date = $request->Call_Back_date;
        $team->status_remark = $request->status_remark;
    }
    

    if ($request->status == "Contact") {
    //  $team->status_remark = $request->status_remark;
    $team->status_remark = $request->company_remark;
 }


 if($request->status == 'Rejected' ){
    $team->not_contact = $request->not_intrest;
    $team->remark_date = $request->not_answer;
    $team->status_remark = $request->int_remark;

}

if($request->status == 'Not_Contact' && $request->company_ringing != '' ){
    $team->remark_date = $request->company_ringing_date;
    $team->status_remark = $request->company_ringing;

}

if($request->status == 'Not_Contact' && $request->not_contact =='Switch_Off' ){
    $team->remark_date = $request->company_switch_date;
    $team->status_remark = $request->company_switch;
}

if($request->status == 'Not_Contact' && $request->not_contact =='Not_Reachable' ){
    $team->remark_date = $request->company_reachable_date;
    $team->status_remark = $request->company_reachable;
}
// santosh
 $team->save();
 $last_id = $team->id;




 if($request->status == "Shortlisted"){
    $team->schedule = "multi";

    $interview_date = $request->interview_date;
    $schedule = $request->schedule;
    $company_name = $request->company_name;
    $company_remark = $request->company_remark;
    $vartical_name = $request->vartical_name;
    $designation_name = $request->designation_name;
    for ($i=0; $i < count($request->schedule) ; $i++) { 

      $values = array(
        'schedule' => $schedule[$i],
        'company_name' => $company_name[$i],
        'company_remark' => $company_remark[$i],
        'interview_date' => $interview_date[$i],
        'vartical_name' => $vartical_name[$i],
        'designation_name' => $designation_name[$i],
        'lead_id' => $last_id
    );
    // dd($values);
      DB::table('multilead')->insert($values);
  }


}
$values = array(
    'status'=> "1",
);


$result = DB::table('candidate')->where('id', $request->candidate_id)->update($values);

return redirect('getleadlist/all')->with('message', 'Lead List Data has been Added Successfully');
}


public function interviewStore(Request $request)

{
        // dd($request);

    $team = new Lead();
        // $team->round = 1;
    $team->candidate_id = $request->candidate_id;

    $team->name = $request->name;

    $team->email = $request->email;

    $team->number = $request->number;

    $team->status = $request->status;

    $team->schedule = $request->schedule;

    if($request->schedule == "company_office")
    {

        $team->company_id = $request->company_name;

        $team->vacancy_id = $request->vacancy_id;

        $team->vartical_name = $request->vartical_name;

        $team->designation_name = $request->designation_name;

        $team->location_id = $request->branch_location;

        $team->position_id  = $request->position_name;

        $team->ofc_visit_date = $request->company_visit_date;

        $team->status_remark = $request->company_remark;
            // dd($team->status_remark);
    }

    if($request->schedule == "recruiter_office")
    {
        $team->recruiter_branch = $request->recruiter_branch;

        $team->recruiter_visit_date = $request->recruiter_visit_date;

        $team->recruiter_name = $request->recruiter_name;

        $team->recruiter_designation = $request->recruiter_designation;

        $team->status_remark = $request->recruiter_remark;

            // $team->location_id = $request->online_location_name;
    }

    if($request->schedule == "Online")
    {
        $team->company_id = $request->int_com_name;

        $team->designation = $request->int_des_name;

        $team->ofc_visit_date = $request->int_des_date;

        $team->status_remark = $request->int_remark;

    }

    $team->updated_by = Auth::id();

    $team->status_remark = $request->company_remark;

    $team->status_remark = $request->recruiter_remark;

    $team->status_remark = $request->int_remark;

    $team->not_contact = $request->not_contact;

    $team->not_answer = $request->not_answer;

    $team->status_remark = $request->not_answer_date;

        // dd($request->call_back);

    $team->status_remark = $request->call_back;

    $team->call_back_date = $request->call_back_date;

    $team->wrong_no = $request->wrong_no;

    $team->save();

        // return redirect('lead')->with('message', 'Lead List Data has been Added Successfully');
}

public function update(Request $request, $id)
{
    $lead = Lead::where('id', $id)->first();

    $lead->cname = $request->cname;
    $lead->numberofvacancy = $request->numberofvacancy;
    $lead->status = $request->status;

    $lead->update();

    return redirect('lead')->with('message', 'Lead List Data has been Updated Successfully');
}

public function update_work_status(Request $request)
{

    $matchTheseFields = array('id'=>$request->candidate_id);
    $values= array(
        "working_status" =>$request->not_intrest
    );

    $team = new Lead();
    $team->candidate_id = $request->candidate_id;
    $team->status = "Not Working";
    $team->schedule = "Not Working";
    $team->updated_by = Auth::id();
    $team->save();
    $values = array(
        "status"=>0
    );
    DB::table('candidate')->updateOrInsert($matchTheseFields,$values);
    return true;

}

public function update_working_status(Request $request)
{

    // dd($request->all());
    $matchTheseFields = array('lead_id'=>$request->lead);
    $values = array(
        "updated_at"=> date('Y-m-d G:i:s'),
        "last_update"=>date('Y-m-d G:i:s')
    );
    DB::table('multilead')->updateOrInsert($matchTheseFields,$values);
    return true;

}
public function update_join_status(Request $request)
{

    // dd($request->all());
    $matchTheseFields = array('lead_id'=>$request->lead);
    $values = array(
        "updated_at"=> date('Y-m-d G:i:s'),
        "is_joined"=> 1,
        "last_update"=>date('Y-m-d G:i:s')
    );
    DB::table('multilead')->updateOrInsert($matchTheseFields,$values);
    return true;

}





public function destroy($id)
{
        // dd($id);
    $lead = Lead::where('id', $id)->first();
    $lead->IsDeleted = 1;
    $lead->update();
        // $get =  Lead::find($id)->delete();

        // dd($get);


    return back()->with('message', 'Lead Data has been deleted Successfully');
}
}
