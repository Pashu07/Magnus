<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\NewVacancy;
use Illuminate\Http\Request;
use App\Models\PincodeLocation;
use App\Models\VacancyLocation;
use Illuminate\Support\Facades\DB;

class NewVacancyController extends Controller
{



    public function fetchLocationAdd(Request $request)
    {
        // dd($request);

        $address['address_get'] = DB::table('pincode_locations')
                    ->where('id' , $request->location_id)->get();
        return response()->json($address);

    }


    public function fetchLocation(Request $request)
    {
        // dd($request->location_id);
        $data['states'] = DB::table('vacancy')
            ->join('vacancy_location', 'vacancy.id', '=', 'vacancy_location.vacancy_id')
            ->where("comapny_id", $request->location_id)->get();

        // dd($data);
        return response()->json($data);
    }

    public function fetchLocationApply(Request $request)
    {
        // dd($request->location_id);
        $data['states'] = DB::table('vacancy')
                            ->join('vacancy_location', 'vacancy.id', '=', 'vacancy_location.vacancy_id')
                            ->join('vartical', 'vartical.id', '=', 'vacancy.vartical_id')
                            ->join('positions', 'positions.id', '=', 'vacancy.position_id')
                            ->join('designation', 'designation.id', '=', 'vacancy.designation_id')
                            ->where("comapny_id", $request->location_id)
                            ->select('vartical.vartical_name' , 'vartical.id as vertID' , 'positions.position_name' ,'positions.id as posID' , 'designation.designation_name' , 'designation.id as desID' ,  'vacancy_location.location_name' , 'vacancy_location.pincode_id' )
                            // ->distinct()
                            ->get();

        // dd($data);
        return response()->json($data);
    }

    public function fetchSubLocation(Request $request)
    {
        // dd($request->location_id);

        $sub_location['location'] = DB::table('pincode_locations')
            ->where("id", $request->location_id)->get();
        // dd($sub_location);
        return response()->json($sub_location);
    }

    public function fetchLocationVacancy(Request $request)
    {
        $sub_location['location'] = DB::table('vacancy_location')
            ->where("id", $request->sub_location_id)->get();
        // dd($sub_location);
        return response()->json($sub_location);
    }

    public function fetchCandidate(Request $request)
    {
        $data['candidate'] = DB::table('candidate')->where("id", $request->location_id)->get();
        return response()->json($data);
    }

    public function fetchVacancy(Request $request)
    {
        $data['vacancy'] = DB::table('companies')->where("sub_location_name", $request->vacancy_id)->get();
        return response()->json($data);
    }

    public function fetchCompanyLocation(Request $request)
    {
        // dd($request->company_id);
        $locationCompany['copm_location'] = DB::table('vacancy')
                                            ->join('vacancy_location', 'vacancy.id', '=', 'vacancy_location.vacancy_id')
                                            ->join('vartical', 'vartical.id', '=', 'vacancy.vartical_id')
                                            ->join('positions', 'positions.id', '=', 'vacancy.position_id')
                                            ->join('designation', 'designation.id', '=', 'vacancy.designation_id')
                                            ->where("comapny_id", $request->company_id)
                                            ->select('vartical.vartical_name' , 'vartical.id as vertID' , 'positions.position_name' ,'positions.id as posID' , 'designation.designation_name' , 'designation.id as desID' ,  'vacancy_location.location_name' , 'vacancy_location.pincode_id' )
                                            ->get();

        // DB::table('companies')
        //                                     ->leftjoin('vacancy_location' , 'vacancy_location.company_id' , 'companies.id')
        //                                     ->leftjoin('vartical' , 'vartical.id' , 'companies.vartical_id')
        //                                     ->leftjoin('designation' , 'designation.id' , 'companies.designation_id')
        //                                     ->where('company_id' , $request->company_id )
        //                                     ->select('vacancy_location.id as vacancyIds' , 'vacancy_location.location_name' , 'vartical.vartical_name' , 'designation.designation_name' , 'vacancy_location.vacancy_id')
        //                                     ->get( );
                                            // dd($locationCompany);
        return response()->json($locationCompany);

    }

    public function fetchCompanyVartical(Request $request)
    {
        // dd($request->company_id);
        $locationCompany['copm_vartical'] = DB::table('vacancy_location')->where('company_id' , $request->company_id )->get();
        return response()->json($locationCompany);

    }

    public function fetchCompanyDesignation(Request $request)
    {
        // dd($request->company_id);
        $locationCompany['copm_designation'] = DB::table('vacancy_location')->where('company_id' , $request->company_id )->get();
        return response()->json($locationCompany);

    }

    public function view_vacancy()
    {
        $vacancy = DB::table('vacancy')
            ->join('vacancy_location', 'vacancy.id', '=', 'vacancy_location.vacancy_id')
            ->join('companies', 'vacancy.comapny_id', '=', 'companies.id')
            ->join('vartical', 'vartical.id', '=', 'companies.vartical_id')
            ->select('companies.company_name', 'vartical.vartical_name', 'vacancy.id as ids', 'vacancy_location.location_name', 'vacancy_location.city_name', 'vacancy_location.state_name', 'vacancy_location.number_of_vacancy' , 'vacancy_location.id as vacIds')
            ->where('vacancy_location.IsDeleted' , 0)
            ->get();

        // dd($vacancy);
        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Dashboard"], ['name' => "Add New Vacancy"]
        ];
        return view('/content/vacancy-list', ['breadcrumbs' => $breadcrumbs, 'vacancys' => $vacancy]);
    }

    public function addVacancyCandidate(Request $request)
    {
        // dd($request->id);
        $locations = DB::table('pincode_locations')->get();
        $positions = DB::table('positions')->where('IsDeleted',0)->get();
        $varticals = DB::table('vartical')->where('IsDeleted',0)->get();
        $designation = DB::table('designation')->where('IsDeleted',0)->get();
        $vacancy = Company::find($request->id);
        $company = Company::find($request->id);

// dd($vacancy);
        $breadcrumbs = [
            ['link' => "company", 'name' => "Company List"], ['name' => "Add Vacancy"]
        ];
        return view('/content/add-vacancy-candidate-list', ['breadcrumbs' => $breadcrumbs, 'designations'=>$designation , 'vacancys' => $vacancy, 'company' => $company ,'positions' => $positions,'varticals' => $varticals, 'locations' => $locations]);
    }


    public function store(Request $request)
    {
        $vacancy = new NewVacancy;

        $vacancy->comapny_id = $request->company_name;
        $vacancy->vartical_id = $request->vartical_name;
        $vacancy->position_id = $request->position_name;
        $vacancy->designation_id = $request->designation_name;


        $vacancy->save();

        $last_id =   $vacancy->id;

        // dd($last_id);
        $index = count($request->location);

        $cart[$index] = [
            $location = $request->location,
            $location_name = $request->location_name,
            $district_name = $request->district_name,
            $state_name = $request->state_name,
            $post = $request->post,
        ];
        // dd($cart);


        for ($i = 0; $i < $index; $i++) {

            // dd($i);
            VacancyLocation::insert([
                'pincode_id' => $location[$i],
                'location_name' => $location_name[$i],
                'city_name' => $district_name[$i],
                'state_name' => $state_name[$i],
                'number_of_vacancy' => $post[$i],
                'vacancy_id' => $last_id,
                'company_id' => $request->company_name
            ]);
        }


        return redirect('new-vacancy')->with('message', 'Vacancy Add Successfully');
    }

    public function storeCandidate(Request $request)
    {
        $vacancy = new NewVacancy;

        $vacancy->comapny_id = $request->company_name;
        $vacancy->vartical_id = $request->vartical_name;
        $vacancy->position_id = $request->position_name;
        $vacancy->designation_id = $request->designation_name;

        $vacancy->save();

        $last_id =   $vacancy->id;

        // dd($last_id);
        $index = count($request->location);

        $cart[$index] = [
            $location = $request->location,
            $location_name = $request->location_name,
            $district_name = $request->district_name,
            $state_name = $request->state_name,
            $post = $request->post,
        ];
        // dd($cart);


        for ($i = 0; $i < $index; $i++) {

            // dd($i);
            VacancyLocation::insert([
                'pincode_id' => $location[$i],
                'location_name' => $location_name[$i],
                'city_name' => $district_name[$i],
                'state_name' => $state_name[$i],
                'number_of_vacancy' => $post[$i],
                'vacancy_id' => $last_id,
                'company_id' => $request->company_name
            ]);
        }


        return redirect('company')->with('message', 'Vacancy Add Successfully');
    }




    public function delete_new_vacancy($ids)
    {
        $vacancy = VacancyLocation::where('id', $ids)->first();

        $vacancy->IsDeleted = 1;

        $vacancy->update();

        // DB::table('vacancy_location')->where('id', $ids)->delete();

        return back()->with('message', 'Vacancy Data has been deleted Successfully');;
    }
}
