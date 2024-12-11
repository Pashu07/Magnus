<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $positions = Position::where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Home"], ['name' => "Position List"]
        ];
        return view('/content/position-list', ['breadcrumbs' => $breadcrumbs, 'positions' => $positions]);
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
        $position = new Position();

        $position->position_name = $request->position_name;

        $position->save();

        return redirect('position')->with('message', 'Position Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function show(Position $position)
    {
        $vacancy = Position::get();

        $breadcrumbs = [
            ['link' => "position", 'name' => "Position"], ['name' => "Position List"]

        ];
        return view('content.add-position' , ['breadcrumbs' => $breadcrumbs, 'vacancys' => $vacancy]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function edit(Position $position , $id)
    {
        $positions = Position::where('id', $id)->first();
        $breadcrumbs = [
            ['link' => "company", 'name' => "Company List"], ['name' => "Update position"]
        ];
        return view('/content/update-position', ['breadcrumbs' => $breadcrumbs, 'id' => $id])->with('positions', $positions);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Position $position , $id)
    {
        $position = Position::where('id', $id)->first();

        $position->position_name = $request->position_name;

        $position->update();

        return redirect('position')->with('message', 'position Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy(Position $position , $id)
    {
        $position = Position::where('id', $id)->first();

        $position->IsDeleted = 1;

        $position->update();

        // $position->delete();

        return redirect('position')->with('message', 'position Deleted Successfully');
    }
}
