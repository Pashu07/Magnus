<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Link;
use File;
use DB;
use Carbon\Carbon; 


class ReportController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getReport(Request $request, $type = null)
    {
        $is_draft=$request->is_draft ?? 0;


        if (!empty($request->refund_file) && !is_null($request->refund_file)) {
            $refund_file = $request->name.time().'.'.$request->refund_file->getClientOriginalExtension();  
            $path = public_path().'/refunds/'.$request->client_id.'/';
            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
            $request->refund_file->move($path, $refund_file);
            $final_refund_file='/refunds/'.$request->client_id.'/'.$refund_file;
        }else{
            $final_refund_file=null;
        }

        DB::table('vat_refunds')->insert([
            'account_id' => 'FAME',
            'client_id' => $request->client_id,
            'refund_from_date' => $request->from_date,
            'refund_to_date' => $request->to_date,
            'requested_amount' => $request->requested_amount,
            'approved_amount' => $request->approved_amount,
            'submission_date' => $request->submission_date,
            'approved_date' => $request->approved_date,
            'refund_file' => $final_refund_file,
            'created_by' => $request->created_by,                    
            "is_draft" => $is_draft,
            'created_at' => Carbon::now()
        ]);

    return redirect()->route('refunds-list')->with("message", "Client's has been Added Successfully");

   }



    public function update(Request $request)
    {
        $is_draft=$request->is_draft ?? 0;
        if (!empty($request->refund_file) && !is_null($request->refund_file)) {
            $refund_file = $request->name.time().'.'.$request->refund_file->getClientOriginalExtension();  
            $path = public_path().'/refund_file/'.$request->client_id.'/';
            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
            $request->refund_file->move($path, $refund_file);
            $final_refund_file='/refunds/'.$request->client_id.'/'.$refund_file;
            $result=DB::table('vat_refunds')->where([
                'id' => $request->id,
                'client_id' => $request->client_id,
            ])->limit(1)->update(['refund_file' => '/refunds/'.$request->client_id.'/'.$refund_file]);
        }
        $client=DB::table('vat_refunds')->where(['id'=> $request->id])->limit(1)->update([
                'refund_from_date' => $request->from_date,
                'refund_to_date' => $request->to_date,
                'requested_amount' => $request->requested_amount,
                'approved_amount' => $request->approved_amount,
                'submission_date' => $request->submission_date,
                'approved_date' => $request->approved_date,
                'modified_by' => $request->modified_by,
                "is_draft" => $is_draft,
                'modified_at' => Carbon::now()
        ]);

    return redirect()->route('refunds-list')->with('message', 'Vat Refund has been Updated Successfully');

    }

    public function delete(Request $request)
    {

        DB::table('vat_refunds')->where([
            'id' => $request->id
        ])->limit(1)->delete();      
    return redirect()->route('refunds-list')->with('message', 'Vat refund of Client has been Deleted Successfully');

    }
}
