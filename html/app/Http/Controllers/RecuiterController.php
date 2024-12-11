<?php

namespace App\Http\Controllers;

use App\Models\Recuiter;
use Illuminate\Http\Request;
use App\Models\RecuiterBranch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RecuiterController extends Controller
{

    public function index()
    {
        $recuiters = Recuiter::where('IsDeleted' , 0)->orderBy('id', 'DESC')->get();
        $breadcrumbs = [
            ['link' => "dashboard", 'name' => "Home"], ['name' => "Recuiter List"]
        ];
        return view('/content/recuiter-list', ['breadcrumbs' => $breadcrumbs, 'recuiters' => $recuiters]);
    }


    public function getBranch(Request $request)
    {
        // dd($request->recruiter_id);

        $getRecruiterBranch['recruiterBranch'] =  DB::table('recuiters')
                ->join('recuiter_branch' , 'recuiter_branch.recuiters_id' , '=' , 'recuiters.id')
                ->where('recuiter_branch.recuiters_id' , $request->recruiter_id)
                ->get();
        return response()->json($getRecruiterBranch);
    }



    public function store(Request $request)
    {
        $candidate = new Recuiter();
        $candidate->recuiter_name = $request->name;
        if ($request->hasFile('recruiter_logo')) {

            $file = $request->file('recruiter_logo');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('uploads/recuiter_logo/', $filename);
            $candidate->recuiter_logo = $filename;
        }

        $candidate->save();

        $last_id =   $candidate->id;

        $index = count($request->branch);

        $recuiter_branch[$index] = [
            $branch = $request->branch
        ];
                for ($i = 0; $i < $index; $i++) {
                    // dd($i);
                    RecuiterBranch::insert([
                        'branch_name' => $branch[$i],
                        'recuiters_id' => $last_id,
                    ]);
                }


        return redirect('recuiter')->with('message', 'Recuiter Added Successfully');
    }

    public function show(Recuiter $recuiter)
    {
        $vacancy = Recuiter::get();

        $breadcrumbs = [
            ['link' => "recuiter", 'name' => "Recuiter"], ['name' => "Recuiter List"]

        ];
        return view('content.add-recuiter-list' , ['breadcrumbs' => $breadcrumbs, 'vacancys' => $vacancy]);
    }


    public function edit(Request $request)
    {
        $recuiters = Recuiter::where('id', $request->id)->first();

        // dd ($recuiters);
        $breadcrumbs = [
            ['link' => "company", 'name' => "Company List"], ['name' => "Update recuiter"]
        ];
        return view('/content/update-recuiter-list', ['breadcrumbs' => $breadcrumbs, 'recuiters' => $recuiters]);
    }


    public function update(Request $request)
    {
        $recuiter = Recuiter::where('id', $request->recuiters_id)->first();

        $recuiter->recuiter_name = $request->name;

        if ($request->hasFile('recuiter_logo')) {
            $upload_path = 'storage/uploads/recuiter_logo/';
            $old_file = $upload_path . '/' . $request['recuiter_logo'];
            if (Storage::exists($old_file)) {
                //delete previous file
                unlink($old_file);
            }
            $file = $request->file('recruiter_logo');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('uploads/recuiter_logo/', $filename);
            $recuiter->recuiter_logo = $filename;
        }
        $recuiter->update();

        $last_id =   $recuiter->id;

        $deleteQualification = DB::table('recuiter_branch')->where('recuiters_id' , $last_id);
        // dd($delete);
        $deleteQualification->delete();


        $index = count($request->branch);

        $recuiter_branch[$index] = [
            $branch = $request->branch
        ];
                for ($i = 0; $i < $index; $i++) {
                    // dd($i);
                    RecuiterBranch::insert([
                        'branch_name' => $branch[$i],
                        'recuiters_id' => $last_id,
                    ]);
                }


        return redirect('recuiter')->with('message', 'recuiter Updated Successfully');
    }


    public function destroy(Recuiter $recuiter , $id)
    {
        $recuiter = Recuiter::where('id', $id)->first();

        $recuiter->IsDeleted = 1;



             DB::table('recuiter_branch')->where('recuiters_id' , $id)->delete();
        // dd($delete);


        $recuiter->update();

        return redirect('recuiter')->with('message', 'recuiter Deleted Successfully');
    }
}
