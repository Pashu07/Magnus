<?php

namespace App\Http\Controllers;

use App\Models\JobDetails;
use Illuminate\Http\Request;

class JobDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobdetails = JobDetails::where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Dashboard"], ['name' => "Job Details List"]
        ];
        return view('/content/jobdetails-list', ['breadcrumbs' => $breadcrumbs, 'jobdetails' => $jobdetails]);
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
            $jobdetails = new JobDetails();
            $jobdetails->job_title = $request->job_title;
            $jobdetails->experience = $request->experience;
            $jobdetails->salery_up_to = $request->salery_up_to;
            $jobdetails->job_location = $request->job_location;
            $jobdetails->job_type = $request->job_type;
            $jobdetails->job_title = $request->job_title;
            $jobdetails->role_responsibilies = $request->role_responsibilies;
            $jobdetails->candidate_profile = $request->candidate_profile;
            $jobdetails->save();

            return redirect('add-job-details')->with('message', 'Job Details has been Added Successfully');

    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JobDetails  $jobDetails
     * @return \Illuminate\Http\Response
     */
    public function show(JobDetails $jobDetails)
    {
        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Dashboard"], ['name' => "Candidate List"]
        ];
        return view('content.add-jobdetails-list' , ['breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JobDetails  $jobDetails
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $id = $request->jobdetailsId;

        $jobdetails = JobDetails::where('id', $id)->first();
        $breadcrumbs = [
            ['link' => "add-job-details", 'name' => "Job Details List"], ['name' => "Update Job Details"]
        ];
        return view('/content/update-jobdetails-list', ['breadcrumbs' => $breadcrumbs, 'id' => $id])->with('jobdetails', $jobdetails);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobDetails  $jobDetails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $jobdetails = JobDetails::where('id' , $request->jobdetailsId)->first();
        $jobdetails->job_title = $request->job_title;
        $jobdetails->experience = $request->experience;
        $jobdetails->salery_up_to = $request->salery_up_to;
        $jobdetails->job_location = $request->job_location;
        $jobdetails->job_type = $request->job_type;
        $jobdetails->job_title = $request->job_title;
        $jobdetails->role_responsibilies = $request->role_responsibilies;
        $jobdetails->candidate_profile = $request->candidate_profile;
        $jobdetails->update();

        return redirect('add-job-details')->with('message', 'Job Details has been Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobDetails  $jobDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $jobdetails = JobDetails::where('id', $id)->first();

        $jobdetails->IsDeleted = 1;

        $jobdetails->update();
        // JobDetails::find($id)->delete();
        return back()->with('success', 'Job deleted successfully');
    }
}
