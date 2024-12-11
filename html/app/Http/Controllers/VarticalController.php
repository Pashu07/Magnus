<?php

namespace App\Http\Controllers;

use App\Models\Vartical;
use Illuminate\Http\Request;
use Auth;

class VarticalController extends Controller
{

    public function __construct()
    {
        // $this->middleware(function(){
        //     if (!Auth::check()) return 'NO';
        // });        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $varticals = Vartical::where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();

        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Home"], ['name' => "Vartical List"]
        ];
        return view('/content/vatical-list', ['breadcrumbs' => $breadcrumbs, 'varticals' => $varticals]);
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
        $vartical = new Vartical();

        $vartical->vartical_name = $request->vartical_name;

        $vartical->save();

        return redirect('vartical')->with('message', 'Vartical Added Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vartical  $vartical
     * @return \Illuminate\Http\Response
     */
    public function show(Vartical $vartical)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vartical  $vartical
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $varticals = Vartical::where('id', $id)->first();

        // dd($varticals);
        $breadcrumbs = [
            ['link' => "company", 'name' => "Company List"], ['name' => "Update Vartical"]
        ];
        return view('/content/update-vartical', ['breadcrumbs' => $breadcrumbs, 'id' => $id])->with('varticals', $varticals);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vartical  $vartical
     * @return \Illuminate\Http\Response
     */
    public function update_vartical(Request $request, $id)
    {
        $vartical = Vartical::where('id', $id)->first();

        $vartical->vartical_name = $request->vartical_name;

        $vartical->update();

        return redirect('vartical')->with('message', 'Vartical Updated Successfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vartical  $vartical
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vartical $vartical , $id)
    {
        $vartical = Vartical::where('id', $id)->first();

        $vartical->IsDeleted = 1;

        $vartical->update();

        return redirect('vartical')->with('message', 'Vartical Deleted Successfully');

    }
}
