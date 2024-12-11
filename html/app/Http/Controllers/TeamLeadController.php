<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\TeamLead;
use Illuminate\Http\Request;

class TeamLeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teamleads = User::where('user_role', '=', 'EMPLOYEE')->get();
        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Dashboard"], ['name' => "TL / Branch Manager Details"]
        ];
        return view('/content/teamlead-list', ['breadcrumbs' => $breadcrumbs, 'teamleads' => $teamleads]);
    }

    public function view_vacancy()
    {
        // $teamleads = User::where('user_role', '=', 'EMPLOYEE')->get();

        $vacancy = Company::get();
        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Dashboard"], ['name' => "Add New Vacancy"]
        ];
        return view('/content/vacancy-list', ['breadcrumbs' => $breadcrumbs, 'vacancys' => $vacancy]);
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
        $team = new TeamLead();
        $team->name = $request->name;
        $team->email = $request->email;
        $team->status = $request->status;
        $team->schedule = $request->schedule;
        $team->branch = $request->branch;
        $team->ofc_visit_date = $request->ofc_visit_date;
        $team->com_name = $request->com_name;
        $team->designation = $request->designation;
        $team->status_remark = $request->status_remark;
        $team->int_com_name = $request->int_com_name;
        $team->int_des_name = $request->int_des_name;
        $team->int_des_date = $request->int_des_date;
        $team->int_remark = $request->int_remark;
        $team->not_intrest = $request->not_intrest;
        $team->not_answer = $request->not_answer;
        $team->call_back = $request->call_back;
        $team->wrong_no = $request->wrong_no;
        $team->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TeamLead  $teamLead
     * @return \Illuminate\Http\Response
     */
    public function show(TeamLead $teamLead)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TeamLead  $teamLead
     * @return \Illuminate\Http\Response
     */
    public function edit(TeamLead $teamLead)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TeamLead  $teamLead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TeamLead $teamLead)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TeamLead  $teamLead
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeamLead $id)
    {
        $team = TeamLead::where('id' , $id)->first();

        $team->delete();
        // TeamLead::find($id)->delete();

        return back()->with('message', 'Lead Data has been deleted Successfully');
    }
}
