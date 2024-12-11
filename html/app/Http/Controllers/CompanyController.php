<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\NewVacancy;
use Illuminate\Http\Request;
use App\Models\PincodeLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Redirect;
class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     * ,0
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $companys = Company::all();

        $companys = DB::table('companies')
                    // ->join('pincode_locations', 'pincode_locations.id', '=', 'companies.pincode')
        ->leftjoin('designation', 'designation.id', '=', 'companies.designation_id')
        ->leftjoin('vartical', 'vartical.id', '=', 'companies.vartical_id')
        ->leftjoin('positions', 'positions.id', '=', 'companies.position_id')
        ->where('companies.IsDeleted' ,0)
        ->orderBy('companies.id', 'DESC')
        ->get(['companies.company_logo', 'companies.organisation','companies.company_name','positions.position_name' , 'companies.location_name','companies.post','companies.id as ids' , 'designation.designation_name' , 'vartical.vartical_name' ]);
            // dd($getCompanyVerticalData);


        // dd($companys);
        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Dashboard"], ['name' => "Company List"]
        ];
        return view('/content/company-list', ['breadcrumbs' => $breadcrumbs, 'companys' => $companys]);
    }

    public function fetchCompany(Request $request)
    {
        $getCompany['getcompany'] = Company::where('id', '=', $request->companyID)->get();

        // dd($getCompany);
        return response()->json($getCompany);
    }

    public function addNewVacancy($id)
    {
        $companys = Company::where('id', $id)->first();

        $breadcrumbs = [
            ['link' => "company-list", 'name' => "Company List"], ['name' => "Update company"]
        ];
        return view('/content/add-vacancy-list', ['breadcrumbs' => $breadcrumbs,  'companys'=> $companys]);

    }

    public function viewCompanyVerticals($id)
    {
        $candidate = DB::table('candidate')->where('IsDeleted',0)->get();

        $company = DB::table('companies')->where('id' , '=' , $id)->first();
// dd($company);
        $getCompanyVerticalData = DB::table('vacancy')
        ->join('vacancy_location', 'vacancy_location.vacancy_id', '=', 'vacancy.id')
        ->join('companies', 'companies.id', '=',  'vacancy.comapny_id')
        ->join('designation', 'designation.id', '=', 'vacancy.designation_id')
        ->join('vartical', 'vartical.id', '=', 'vacancy.vartical_id')
        ->join('positions', 'positions.id', '=', 'vacancy.position_id')
        ->where('vacancy.comapny_id' , '=' , $id)
        ->orderBy('vacancy.id', 'DESC')
        ->get(['companies.company_logo','companies.company_name','positions.position_name' , 'vacancy_location.location_name','vacancy_location.city_name','companies.id as ids' , 'designation.designation_name' , 'vartical.vartical_name','vacancy_location.number_of_vacancy' , 'vacancy_location.id as vacancyid' ]);

        $breadcrumbs = [
            ['link' => "company", 'name' => "Company"], ['name' => "Company List"]
        ];
        return view('/content/view-company-list',['breadcrumbs' => $breadcrumbs, 'candidates' =>$candidate , 'companies' =>$company , 'getcompanys' => $getCompanyVerticalData]);
    }


    public function store(Request $request)
    {
        $data = $request->all();
        if (isset($request->company_id))
            $companys = Company::find($request->company_id);
        else
            $companys = new Company();
        $companys->company_name = strtoupper($request->company_name);
        if ($request->hasFile('company_logo')) {
            $upload_path = 'storage/uploads/company_logo/';
            $old_file = $upload_path . '/' . $request['company_logo'];
            if (Storage::exists($old_file)) {
                //delete previous file
                unlink($old_file);
            }
            $file = $request->file('company_logo');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('uploads/company_logo/', $filename);
            $companys->company_logo = $filename;
        }

        $companys->organisation = $request->organisation;
        $company_response =  $companys->save();

        if ($company_response && isset($request->company_id)) {
            return redirect()->to('company')->with('message', 'Company Details updated successfully.');
        } else if ($company_response)
        return redirect()->to('company')->with('message', 'Company Details Added successfully.');

        return back()->with('message', 'Company Details is not added');
    }

    public function updateCompany(Request $request)
    {


        // dd($request->pincode);
        $id = $request->company_id;
        $vacancy_location = PincodeLocation::where('vacancy_id', $id)->first();
        // dd( $request->status);
        // echo($vacancy_location);
        $vacancy_location->pincode_id = $request->pincode;
        $vacancy_location->location_name = $request->location_name;
        $vacancy_location->city_name = $request->city_name;
        $vacancy_location->state_name = $request->state_name;
        $vacancy_location->number_of_vacancy = $request->post;
        $vacancy_location->IsActive = $request->status;

        $vacancy_location->update();

        $vacancy = NewVacancy::where('id', $id)->first();
        // dd($vacancy);
        $vacancy->comapny_id = $request->company_name;
        $vacancy->vartical_id = $request->vartical_name;
        $vacancy->designation_id = $request->designation_name;
        $vacancy->position_id = $request->position_name;
        // print_r($vacancy);
        $vacancy->update();
        return \Redirect::route('company-view', [$request->company_name])->with('message', 'Company Position updated successfully');



    }


    public function show(Request $request)
    {
        $locations = DB::table('pincode_locations')->get();
        $designation = DB::table('designation')->get();
        $vartical = DB::table('vartical')->get();


        $vacancy = Company::find($request->id);

        // dd($request->id);

        $breadcrumbs = [
            ['link' => "company", 'name' => "Company"], ['name' => "Company List"]

        ];
        return view('content.update-companees-list', ['breadcrumbs' => $breadcrumbs, 'vacancys' => $vacancy, 'locations' => $locations, 'designation' => $designation, 'vartical' => $vartical ]);
    }

    public function viewShow($id)
    {
        // dd($request);

        $vacancys = DB::table('companies')->get();
        $locations = DB::table('pincode_locations')->get();
        $designation = DB::table('designation')->get();
        $vertical = DB::table('vartical')->get();
        $position = DB::table('positions')->get();
        $vacancy_location = DB::table('vacancy_location')
        ->join('vacancy' , 'vacancy.id' , '=' , 'vacancy_location.vacancy_id')
        ->where('vacancy_location.id' , '=' , $id)
        ->first();
        $companees = Company::find($id);



        // dd($vacancy_location);
        $breadcrumbs = [
            ['link' => "company" , 'name' => "Company"],['name' => "Company List"]
        ];

        return view('content.update-companees-view-list' , ['breadcrumbs' => $breadcrumbs, 'positions' =>$position , 'vacancys' =>$vacancys ,'designations' => $designation, 'varticals' => $vertical , 'locations' => $locations, 'companees' => $companees , 'vacancy_location' => $vacancy_location]);
    }



    public function edit($id)
    {

        $companys = Company::where('id', $id)->first();
        $breadcrumbs = [
            ['link' => "company-list", 'name' => "Company List"], ['name' => "Update company"]
        ];
        return view('/content/update-companees-list', ['breadcrumbs' => $breadcrumbs, 'id' => $id])->with('companys', $companys);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $companys = Company::where('id', $id)->first();

        $companys->company_name = $request->company_name;
        $companys->post = $request->post;



        if ($request->hasFile('company_logo')) {
            $upload_path = 'storage/uploads/company_logo/';
            $old_file = $upload_path . '/' . $request['company_logo'];
            if (Storage::exists($old_file)) {
                //delete previous file
                unlink($old_file);
            }

            $file = $request->file('company_logo');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('uploads/company_logo/', $filename);
            $companys->company_logo = $filename;
        }

        $companys->update();
        return redirect('company')->with('message', 'Company Added Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $companys = Company::where('id', $id)->first();
        $companys->IsDeleted = 1;

        $companys->update();
        // Company::find($id)->delete();
        return back()->with('success', 'Company deleted successfully');
    }

    public function viewCompanyVerticalsDelete($id)
    {
        DB::table('vacancy_location')
        ->where('id','=' , $id)
        ->delete();
        return \Redirect::route('company-view', [$id])->with('message', 'Company Position deleted successfully');


    }
}
