<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Recuiter;
use App\Models\Vartical;
use App\Models\Employee;
use App\Models\Candidate;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Auth;
class StaterkitController extends Controller
{
    // home
    public function home()
    {
        $candidate = DB::table('candidate')->select(DB::raw('*'))
        ->whereRaw('Date(created_at) = CURDATE()')->count();
        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Home"], ['name' => "Index"]
        ];
        return view('/content/home', ['breadcrumbs' => $breadcrumbs, 'candidate'=>$candidate]);
    }

    public function users_list()
    {
        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Home"],['name' => "Users List"]
        ];
        return view('/content/users-list', ['breadcrumbs' => $breadcrumbs]);
    }

    public function product_list()
    {
        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Dashboard"],['name' => "Booking List"]
        ];
        return view('/content/product-list', ['breadcrumbs' => $breadcrumbs]);
    }

    public function add_company()
    {
        // $locations = DB::table('location')->get();
        $locations = DB::table('pincode_locations')->get();
        $designation = DB::table('designation')->get();
        $vartical = DB::table('vartical')->get();
        $positions = DB::table('positions')->get();
        // {{ dd($positions); }}
        $vacancy = Company::get();

        $breadcrumbs = [
            ['link' => "company", 'name' => "Company"], ['name' => "Company List"]

        ];
        return view('content.add-company-list' , ['breadcrumbs' => $breadcrumbs, 'positions' => $positions , 'vacancys' => $vacancy, 'locations' => $locations , 'designation' =>$designation , 'vartical' =>$vartical]);
    }

    public function add_candidate(Request $request)
    {
        // $locations = DB::table('location')->get();
        $candidate = DB::table('candidate')->where('IsDeleted',0)->get();

        $company = DB::table('companies')->where('IsDeleted',0)->get();
        $designation = DB::table('designation')->where('IsDeleted',0)->get();
        $recuiters = DB::table('recuiters')->where('IsDeleted',0)->get();
        $vartical = DB::table('vartical')->where('IsDeleted',0)->get();
        $positions = DB::table('positions')->where('IsDeleted',0)->get();
        $vacancy_location = DB::table('vacancy_location')->where('id' , $request->id)->get();


        $locations = DB::table('vacancy')
        ->join('vacancy_location', 'vacancy.id', '=', 'vacancy_location.vacancy_id')
        ->join('vartical', 'vartical.id', '=', 'vacancy.vartical_id')
        ->join('positions', 'positions.id', '=', 'vacancy.position_id')
        ->join('designation', 'designation.id', '=', 'vacancy.designation_id')
        ->where("comapny_id", $vacancy_location['0']->company_id)
        ->select( 'vacancy_location.location_name' , 'vacancy_location.pincode_id' )
        ->distinct()
        ->get();

        $vacancy_data = DB::table('vacancy')->where('id' , $vacancy_location['0']->vacancy_id)->get();
        $vacancy = Company::get();

        $breadcrumbs = [
            ['link' => "company", 'name' => "Company"], ['name' => "Company List"]

        ];
        return view('content.apply-company-list' , ['breadcrumbs' => $breadcrumbs,'vacancy_location' =>$vacancy_location, 'candidate'=>$candidate, 'companies' =>$company , 'recruiters' => $recuiters , 'positions' => $positions , 'vacancys' => $vacancy, 'locations' => $locations , 'designations' =>$designation , 'varticals' =>$vartical,'vacancy_data'=>$vacancy_data]);
    }

    public function view_candidate(Request $request)
    {
        // dd($request->id);
        // $locations = DB::table('location')->get();
        $candidate = DB::table('candidate')->where('id' , $request->id)->first();

        $company = DB::table('companies')->get();
        $locations = DB::table('pincode_locations')->get();
        $designation = DB::table('designation')->get();
        $recuiters = DB::table('recuiters')->get();
        $vartical = DB::table('vartical')->get();
        $positions = DB::table('positions')->get();

        $vacancy_location = DB::table('vacancy_location')->where('id' , $request->id)->get();
        // {{ dd($vacancy_location); }}
        $vacancy = Company::get();

        $breadcrumbs = [
            ['link' => "/admin/candidate/show", 'name' => "Candidate"], ['name' => "Candidate Details"]

        ];
        return view('content.view-candidate-profile' , ['breadcrumbs' => $breadcrumbs,'vacancy_location' =>$vacancy_location, 'candidate'=>$candidate, 'companies' =>$company , 'recruiters' => $recuiters , 'positions' => $positions , 'vacancys' => $vacancy, 'locations' => $locations , 'designations' =>$designation , 'vartical' =>$vartical]);
    }

    public function add_vartical()
    {
        $vacancy = Vartical::get();

        $breadcrumbs = [
            ['link' => "vartical", 'name' => "Vartical List"], ['name' => "Vartical List"]

        ];
        return view('content.add-vartical' , ['breadcrumbs' => $breadcrumbs, 'vacancys' => $vacancy]);
    }

    public function add_designation()
    {
        $designations = Designation::get();

        $breadcrumbs = [
            ['link' => "designation", 'name' => "Designation"], ['name' => "Designation Form"]

        ];
        return view('content.add-designation' , ['breadcrumbs' => $breadcrumbs, 'designations' => $designations]);
    }

    public function add_jobdetails()
    {
        $breadcrumbs = [
            ['link' => "add-job-details", 'name' => "Job Details"], ['name' => "Job Details List"]
        ];
        return view('content.add-jobdetails-list' , ['breadcrumbs' => $breadcrumbs]);

    }
    public function add_teamlead()
    {
        $breadcrumbs = [
            ['link' => "teamlead", 'name' => "TL/Branch Manager List"], ['name' => "Add Employees"]
        ];
        return view('/content/add-teamlead-list', ['breadcrumbs' => $breadcrumbs]);
    }

    public function add_new_vacancy(Request $request)
    {
        // dd($request);
        $locations = DB::table('pincode_locations')->get();
        $positions = DB::table('positions')->get();
        $varticals = DB::table('vartical')->get();
        $designation = DB::table('designation')->get();

        $vacancy = Company::get();

        $company = Company::find($request->id);

// dd($vacancy);
        $breadcrumbs = [
            ['link' => "new-vacancy", 'name' => "Vacancy List"], ['name' => "Add Vacancy"]
        ];
        return view('/content/add-vacancy-list', ['breadcrumbs' => $breadcrumbs, 'designations' => $designation , 'vacancys' => $vacancy, 'company' => $company ,'positions' => $positions,'varticals' => $varticals, 'locations' => $locations]);
    }

    public function vacancy_show()
    {
        // dd($request);

        $candidate = Candidate::all();
        $company = Company::get();



        $vacancy = DB::table('vacancy')
        ->leftjoin('vacancy_location', 'vacancy.id', '=', 'vacancy_location.vacancy_id')
        ->leftjoin('companies', 'vacancy.comapny_id', '=', 'companies.id')
        ->leftjoin('vartical', 'vartical.id', '=', 'vacancy.vartical_id')
        ->leftjoin('designation', 'designation.id', '=', 'vacancy.designation_id')
        ->where('vacancy_location.IsActive',0)
        ->where('companies.IsDeleted',0)
        ->orderByDesc('vacancy.id')
        ->select('companies.company_name','companies.company_logo', 'vartical.vartical_name' ,  'designation.designation_name', 'vacancy_location.location_name', 'vacancy_location.city_name', 'vacancy_location.state_name','vacancy_location.number_of_vacancy' , 'vacancy_location.id as ids')
        // ->get();
        ->get();
        // dd($vacancy);

        $breadcrumbs = [
            ['link' => "company", 'name' => "Company List"], ['name' => "Vacancy Details"]
        ];
        return view('/content/show-vacancy-list', ['breadcrumbs' => $breadcrumbs, 'companies' => $company , 'vacancys' => $vacancy , 'candidates' => $candidate]);
    }


    public function add_employee()
    {
        $designation = DB::table('emp_designation')->get('emp_designation.*');
        // dd(Auth::id());
        if(Session::get('user')['user_role'] == 'ADMIN'){
        $vertical = User::where('user_role', '=', 'VERTICAL')->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
        }
        elseif(Session::get('user')['user_role'] == 'VERTICAL'){
            $vertical = User::where('user_role', '=', 'BRANCH')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
        // dd($vertical);
        }
        elseif(Session::get('user')['user_role'] == 'BRANCH'){
            $vertical = User::where('user_role', '=', 'TEAMHEAD')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
        // dd($vertical);
        }
        else{
            $vertical = User::where('user_role', '=', 'VERTICAL')->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
        }
        // dd($vertical);
        $breadcrumbs = [
            ['link' => "employees", 'name' => "Employee List"], ['name' => "Add Employee"]
        ];
        return view('/content/add-employees-list', ['breadcrumbs' => $breadcrumbs , 'designations' => $designation, 'vertical'=>$vertical]);
    }

    public function candidate_list()
    {
        $breadcrumbs = [
            ['link' => "candidate", 'name' => "Candidate List"], ['name' => "Add New Candidate"]
        ];
        return view('/content/add-candidate-list', ['breadcrumbs' => $breadcrumbs]);
    }

    public function candidate_show()
    {
        // $candidate = Candidate::all();

      if (Session::get('user')['user_role'] == 'ADMIN') {


        $candidate = Candidate::leftjoin('users', 'candidate.added_by', '=', 'users.id' )
        ->where('candidate.IsDeleted',0)
        ->get(['candidate.*', 'users.name as emp_name', 'users.id as user_id']);

    }
    elseif (Session::get('user')['user_role'] == 'VERTICAL') {
        //  dd(Auth::id());
         $tl_add_candidate = User::where('user_role', '=', 'BRANCH')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
         // dd($tl_add_candidate);

         $teamleads_GET = array();
         $id = array();
         $id[] =  Auth::id(); 
         foreach ($tl_add_candidate as $data) {
         $id[] = $data->id;                         
         }
        //  dd( $id);
        //   echo "<pre>";
        //       print_r($id);
        //       echo "</pre>";
            
         $user = User::select("*")
         ->whereIn('added_by', $id)->where('user_role', '=', 'TEAMHEAD')
         ->get();
         
         
         foreach ($user as $data) {
             $id[] = $data->id;   
         }
        //  dd( $id);

         $user = User::select("*")
         ->whereIn('added_by', $id)->where('user_role', '=', 'EMPLOYEE')
         ->get();
        //  dd( $user);
         foreach ($user as $data) {
             $id[] = $data->id;   
         }
        // dd( $id);
         $candidate = Candidate::select("*")
            ->whereIn('added_by', $id)
            ->get(['candidate.*', 'users.name as emp_name', 'users.id as user_id','candidate.id as cand_id']);

         
    }
    elseif (Session::get('user')['user_role'] == 'BRANCH') {
        //  dd(Auth::id());
         $tl_add_candidate = User::where('user_role', '=', 'TEAMHEAD')->where('added_by', Auth::id())->where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
         // dd($tl_add_candidate);

         $teamleads_GET = array();
         $id = array();
         $id[] =  Auth::id(); 
         foreach ($tl_add_candidate as $data) {
         $id[] = $data->id;      
                 
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
         $candidate = Candidate::select("*")
         ->whereIn('added_by', $EMP_ID)
         ->get(['candidate.*', 'users.name as emp_name', 'users.id as user_id','candidate.id as cand_id']);
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
        $candidate = Candidate::select("*")
        ->whereIn('added_by', $team_id)
        ->get(['candidate.*', 'users.name as emp_name', 'users.id as user_id','candidate.id as cand_id']);

    }

    else{
         $candidate = Candidate::leftjoin('users', 'candidate.added_by', '=', 'users.id' )
        ->where('candidate.IsDeleted',0)
        ->where('candidate.added_by',Auth::id())
        ->get(['candidate.*', 'users.name as emp_name', 'users.id as user_id']);
    }

        // dd($candidate);

    $company = Company::get();
    $company_online = Company::get();
    $users = Employee::get();
    $recruiters = Recuiter::get();

    $designations = Designation::get();
    $breadcrumbs = [
        ['link' => "candidate", 'name' => "Candidate List"], ['name' => "Add New Candidate"]
    ];
    return view('/content/all-candidate', ['breadcrumbs' => $breadcrumbs , 'designations' =>$designations , 'recruiters' => $recruiters , 'candidate' => $candidate , 'companies' =>$company , 'company_online' =>$company_online,'users'=>$users]);
}
public function lead()
{
    $leads = DB::table('candidate')->select('name' , 'number' , 'email')->get();
        // return view('/content/add-lead-list')
    $breadcrumbs = [
        ['link' => "lead", 'name' => "Lead List"], ['name' => "Add New Lead"]
    ];
    return view('/content/add-lead-list', ['breadcrumbs' => $breadcrumbs])->with('leads', $leads);
}





    // Layout collapsed menu

public function frontend()
{
    return view('/frontend/index');
}


public function about()
{
    return view('/frontend/about');
}


public function contact()
{
    return view('/frontend/contact');
}

public function apply_job($referral_id = NULL)
{

    return view('/frontend/apply', ['referral_id' => $referral_id]);
}

public function job_details()
{

    return view('/frontend/job-details');
}
public function staffing_solution()
{
    return view('/frontend/staffing_solution');
}

public function enquiry_fom()
{
    return view('/frontend/enquiry_fom');
}


public function hr_solutions()
{

    return view('/frontend/hr_solutions');
}

}
