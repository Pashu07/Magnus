<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //designation

            $designations = Designation::where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Home"], ['name' => "Designation List"]
        ];
        return view('/content/designation-list', ['breadcrumbs' => $breadcrumbs, 'designations' => $designations]);
    }


    public function store(Request $request)
    {
        $designation = new Designation();

        $designation->designation_name = $request->designation_name;

        $designation->minctc = $request->minctc;

        $designation->maxctc = $request->maxctc;

        $designation->minage = $request->minage;

        $designation->maxage = $request->maxage;

        $designation->save();

        return redirect('designation')->with('message', 'Designation Added Successfully');
    }


    public function edit(Designation $designation , $id)
    {
        $designation = Designation::where('id', $id)->first();
        $breadcrumbs = [
            ['link' => "designation", 'name' => "Designation List"], ['name' => "Update Designation"]
        ];
        return view('/content/update-designation', ['breadcrumbs' => $breadcrumbs, 'id' => $id])->with('designation', $designation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Designation $designation , $id)
    {

        $designation = Designation::where('id', $id)->first();

        $designation->designation_name = $request->designation_name;

        $designation->minctc = $request->minctc;

        $designation->maxctc = $request->maxctc;

        $designation->minage = $request->minage;

        $designation->maxage = $request->maxage;


        $designation->save();

        return redirect('designation')->with('message', 'Designation Added Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        $designation = Designation::where('id', $id)->first();

        $designation->IsDeleted = 1;

        $designation->update();

        // $designation->delete();

        return redirect('designation')->with('message', 'Designation Deleted Successfully');

    }
}
