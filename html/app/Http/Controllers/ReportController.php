<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Link;
use File;
use DB;
use Carbon\Carbon; 
use App\Models\Reports;
use App\Models\User;
use Session;


class ReportController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getReport(Request $request, $type = null,$filter = null)
    {


        $type = $request->type;
        $filter = $request->filter;

        $candidate = Reports::select("*");
        $startDate = Carbon::createFromFormat('Y-m-d', '2023-03-10');
        $endDate = Carbon::createFromFormat('Y-m-d', '2023-03-13');
        $candidate->where('candidate.IsDeleted' , 0);
        $candidate->join('candidate' , 'candidate.id' , 'leads.candidate_id');
        $candidate->join('users as emp' , 'candidate.added_by' , 'emp.id');
        if (isset($request->verticalName)) {
           $tl_add_candidate = User::where('user_role', '=', 'BRANCH')->where('added_by', $request->verticalName)->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();

         // dd($tl_add_candidate);

           $teamleads_GET = array();
           $id = array();
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

       }elseif (isset($request->branchName)) {
        $tl_add_candidate = User::where('user_role', '=', 'TEAMHEAD')->where('added_by', $request->branchName)->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
            // dd(Auth::id()); 

        $teamleads_GET = array();
        $idss = array();
        foreach ($tl_add_candidate as $data) {
            $idss[] = $data->id;       
        }

        $user = User::select("*")
        ->whereIn('added_by', $idss)->where('user_role', '=', 'EMPLOYEE')
        ->get();
            // dd( $user);
        $id =array();
            // $EMP_ID[] =  Auth::id(); 
        foreach ($id as $data) {
            $id[] = $data; 
        }

        foreach ($user as $data) {
            $id[] = $data->id;   
        }
    }
    elseif (isset($request->teamheadName)) {
                // dd(Auth::id());
        $tl_add_candidate = User::where('user_role', '=', 'EMPLOYEE')->where('added_by', $request->teamheadName)->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
                // dd($tl_add_candidate);

        $teamleads_GET = array();
        $team_id = array();
        $team_id[] =  Auth::id(); 
        foreach ($tl_add_candidate as $data) {
            $team_id[] = $data->id;      
        }
    }
    else{
        $id =array();

    }






    if ($type == "new-cv") {
        $candidate->groupBy('leads.candidate_id');
            // $candidate->whereIn('added_by', $id);
        $base_table = "candidate";
        $candidate->select('leads.*','candidate.*','candidate.id as cd_id','leads.status AS lead_status','emp.name as added_name');
        $candidate->whereIn(''.$base_table.'.added_by',$id );


    }else{
        $base_table = "leads";
        $candidate->join('multilead' , 'multilead.lead_id' , 'leads.id');
        $candidate->join('companies' , 'companies.id' , 'multilead.company_name');
        $candidate->join('vartical' , 'vartical.id' , 'multilead.vartical_name');
        $candidate->join('designation' , 'designation.id' , 'multilead.designation_name');
        $candidate->select('leads.*','candidate.*','multilead.*','candidate.id as cd_id','leads.status AS lead_status','emp.name as added_name');
        $candidate->whereIn(''.$base_table.'.updated_by',$id );

    }


    if ($type == "office-interview") {
        $candidate->where('leads.status',"Screening");
    }
    if ($type == "shortlisted") {
        $candidate->where('leads.status',"Shortlisted");
    }
    if ($type == "selected") {
        $candidate->where('leads.status',"Shortlisted");
        $candidate->where('multilead.schedule',4);
        $candidate->where('multilead.is_joined',0);
    }
    if ($type == "joined") {
        $candidate->where('leads.status',"Shortlisted");
        $candidate->where('multilead.schedule',4);
        $candidate->where('multilead.is_joined',1);
    }


    if ($filter  == "weekly") {
     $previous_week = strtotime("-1 week +1 day");
     $start_week = strtotime("last sunday midnight",$previous_week);
     $end_week = strtotime("next saturday",$start_week);
     $startDate = date("Y-m-d",$start_week);
     $endDate = date("Y-m-d",$end_week);
 }
 if ($filter != '') {
     if ($filter == "7-days") {
         $candidate->where(''.$base_table.'.created_at','>=',Carbon::now()->subdays());
     }
     else if($filter == "15-days"){
         $candidate->where(''.$base_table.'.created_at','>=',Carbon::now()->subdays(10));
     }
     else if($filter == "30-days"){
         $candidate->where(''.$base_table.'.created_at','>=',Carbon::now()->subdays(30));
     }
     else if($filter == "quarterly"){
         $candidate->where(''.$base_table.'.created_at','>=',Carbon::now()->subdays(90));
     }
     else if($filter == "six-months"){
         $candidate->where(''.$base_table.'.created_at','>=',Carbon::now()->subdays(180));
     }
     else if($filter == "last-year"){
        $candidate->whereYear(''.$base_table.'.created_at', date('Y', strtotime('-1 year')));
    }
    else if($filter == "last-month"){
        $candidate->whereMonth(''.$base_table.'.created_at', '=', Carbon::now()->subMonth()->month);
    }
    else{
       $candidate->whereDate(''.$base_table.'.created_at', '>=', $startDate)
       ->whereDate(''.$base_table.'.created_at', '<=', $endDate);
   }


}

$reports = $candidate->get();


   // echo "<pre>";
   // print_r($reports);
   // exit;
$candidate = DB::table('candidate')->select(DB::raw('*'))
->whereRaw('Date(created_at) = CURDATE()')->count();

$users = DB::table('users')
->where('IsDeleted','0')
->where('user_role','VERTICAL')
->get();

    $vertical = User::where('user_role', '=', 'VERTICAL')->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();



$breadcrumbs = [
    ['link' => "dashboard", 'name' => "Dashboard"], ['name' => "Lead List"]
];

// if ($type  != '' && $filter != '' ) {
    // code...
    return view('/content/reports', ['breadcrumbs' => $breadcrumbs, 'reports' => $reports,'candidate'=>$candidate,'users'=>$users,'vertical'=>$vertical,'id'=> $id]);

// }else{
//     echo json_encode($reports);


// }

}



public function sendWhatsapp(){

    $curl = curl_init();

    $array = array(
        "9177189207734",
        "918652585232",
        "919702323024",
        "919768183383",
        "918780456429"
    );

    foreach($array as $arr){

        $name = "Kishan";
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://dash.wasniper.com/api/send.php?number='.$arr.'&type=json&instance_id=641F3384CBC5B&access_token=aaa8c4ed756a8ffdc9efd1d160b31a71',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "text": "Hi this is a template message '.$name.'",
            "footer": "follow us https://google.com",
            "templateButtons": [ 
            {
                "index": 1, 
                "urlButton": { 
                    "displayText": "⭐ Star Baileys on GitHub!", 
                    "url": "https://github.com/adiwajshing/Baileys" 
                }
                },
                {
                    "index": 2, 
                    "callButton": {
                        "displayText": "Call me!",
                        "phoneNumber": "+521238XXXXXXX"
                    }
                    },
                    {
                        "index": 3,
                        "quickReplyButton": {
                            "displayText": "This is a reply, just like normal buttons!",
                            "id": "next"
                        }
                    }
                    ]
                }',
            ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

}


public function update(Request $request)
{
    $is_draft=$request->is_draft ?? 0;
    if (!empty($request->refund_file) && !is_null($request->refund_file)) {
        $refund_file = $request->name.time().'.'.$request->refund_file->getClientOriginalExtension();  
        $path = public_path().'/refund_file/'.$request->client_id.'/';
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
        $request->refund_file->move($path, $refund_file);
        $final_refund_file='/refunds/'.$request->client_id.'/'.$refund_file;
        $result=DB::table('vat_refunds')->where([
            'id' => $request->id,
            'client_id' => $request->client_id,
        ])->limit(1)->update(['refund_file' => '/refunds/'.$request->client_id.'/'.$refund_file]);
    }
    $client=DB::table('vat_refunds')->where(['id'=> $request->id])->limit(1)->update([
        'refund_from_date' => $request->from_date,
        'refund_to_date' => $request->to_date,
        'requested_amount' => $request->requested_amount,
        'approved_amount' => $request->approved_amount,
        'submission_date' => $request->submission_date,
        'approved_date' => $request->approved_date,
        'modified_by' => $request->modified_by,
        "is_draft" => $is_draft,
        'modified_at' => Carbon::now()
    ]);

    return redirect()->route('refunds-list')->with('message', 'Vat Refund has been Updated Successfully');

}

public function delete(Request $request)
{

    DB::table('vat_refunds')->where([
        'id' => $request->id
    ])->limit(1)->delete();      
    return redirect()->route('refunds-list')->with('message', 'Vat refund of Client has been Deleted Successfully');

}
}
