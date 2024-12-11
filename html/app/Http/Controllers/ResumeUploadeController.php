<?php

namespace App\Http\Controllers;

use App\Models\ResumeUploade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Candidate;
use Auth;
use Session;


class ResumeUploadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Dashboard"], ['name' => "Gallery Image"]
        ];
        return view('/content/add-resume-list', ['breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $resume = new ResumeUploade;
        $file = $request->file('resume');
        $extention = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extention;
        $path = $file->move('documents/resume/', $filename);
        $loc =  base_path('public/'.$path);
        $resume->resume = $filename;
        $resume->save();
        if($resume->save()){
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.shrihr.info/uploadfile/',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array('file'=> new \CURLFILE($loc)),
              CURLOPT_HTTPHEADER => array(
                'Content-Type: multipart/form-data',
                'Accept: application/json'
            ),
          ));

            $response = curl_exec($curl);

$arr = json_decode($response, TRUE);
$url = url('documents/resume/').'/'.$filename;
$arr[] = ['url' => $url];
$json = json_encode($arr);
            

            $data = json_decode($response, true);
            if(!empty($data) && $data['name'] != '' ){
               if (Candidate::where('email', $data['email'])->exists()) {
            //email exists in user table
                return back()->with('error', 'Email alrady exist ');
            }
            if (Candidate::where('number', $data['mobile_number'])->exists()) {
            //contact exists in user table
                return back()->with('error', 'Please Use Other Number ');
            }
                if (isset($data['skills'])) {$skill = implode(',', $data['skills']);}else{ $skill = '';}

            
                if (isset($data['company_names'])) {$company_names = implode(',', $data['company_names']);}else{ $company_names = '';}

            
                if (isset($data['degree'])) {$degree = implode(',', $data['degree']);}else{ $degree = '';}

            
                if (isset($data['designation'])) { $designation = implode(',', $data['designation']);}else{ $designation = '';}

           
            
                $candidate = new Candidate;
                $candidate->frontend = "back_end_data";
                $candidate->name = $data['name'];
                $candidate->number = $data['mobile_number'];
                $candidate->email = $data['email'];
                $candidate->college_name = $data['college_name'];
                $candidate->company_names = $company_names;
                $candidate->designation = $designation;
                $candidate->skill = $skill;
                $candidate->degree = $degree;
                $candidate->url = url('documents/resume/').'/'.$filename;
                if (isset($data['degree'])) {
                    $candidate->education =  $data['degree']['0'];
                }else{
                 $candidate->education = '';
             }
            
            return response()->json($candidate);

            // return $response;
            exit;

            curl_close($curl);
            $data = json_decode($response, true);




            if(!empty($data) && $data['name'] != '' ){
               if (Candidate::where('email', $data['email'])->exists()) {
            //email exists in user table
                return back()->with('error', 'Email alrady exist ');
            }
            if (Candidate::where('number', $data['mobile_number'])->exists()) {
            //contact exists in user table
                return back()->with('error', 'Please Use Other Number ');
            } else {
                $candidate = new Candidate;
                $candidate->frontend = "back_end_data";
                $candidate->name = $data['name'];
                $candidate->number = $data['mobile_number'];
                $candidate->email = $data['email'];
                $candidate->job ='';
                $candidate->skill = $skills;
                if (isset($data['degree'])) {
                    $candidate->education =  $data['degree']['0'];
                }else{
                 $candidate->education = '';
             }
             $candidate->areaofintrest = '';
             $candidate->added_by = Auth::id();

             $candidate->resume = $filename;

             $res = $candidate->save();

             return redirect('candidate')->with('message', 'Candidate has been Added Successfully');
         }
     }else{
         return back()->with('message', 'Please attached proper resume');

     }
 }
 return back()->with('message', 'Candidate has been added Successfully');

}
}
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ResumeUploade  $resumeUploade
     * @return \Illuminate\Http\Response
     */
    public function show(ResumeUploade $resumeUploade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ResumeUploade  $resumeUploade
     * @return \Illuminate\Http\Response
     */
    public function edit(ResumeUploade $resumeUploade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ResumeUploade  $resumeUploade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResumeUploade $resumeUploade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ResumeUploade  $resumeUploade
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResumeUploade $resumeUploade)
    {
        //
    }
}
