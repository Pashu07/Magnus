<?php

namespace App\Imports;

use Auth;
use App\Models\Candidate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExcelImport implements ToModel , WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        // print_r($row);die();
        $user = Candidate::where('email', '=', $row['email'])->first();
        if ($user === null) {
         return Candidate::updateOrCreate([
    //Add unique field combo to match here
    //For example, perhaps you only want one entry per user:
            'email'   => $row['email'],
            'number'   => $row['number'],
        ],[
            "name"                =>   $row['name'],
            'number'              =>   $row['number'],
            'email'               =>   $row['email'],
            'location'            =>   $row['location'],
            'job'                 =>   $row['job'],
            'skill'               =>   $row['skill'],
            'areaofintrest'       =>   $row['areaofintrest'],
            'education'           =>   $row['education'],
            'dateOfBirth'         =>   $row['dateofbirth'],
            'qualification'       =>   $row['qualification'],
            'workExperience'      =>   $row['workexperience'],
            'panNumber'           =>   $row['pannumber'],
            'adharNumber'         =>   $row['adharnumber'],
            'added_by'            =>   Auth::id(),

        ]);
     }


 }
 public function rules(): array
 {
    return [
        'email' => 'required|email|unique:candidate',
             // Above is alias for as it always validates in batches
        '*.email' =>'required|email|unique:candidate',
        'number'              =>   'required|distinct|number|max:10|min:10|digits_between:9,11|unique:candidate',
        "name"                =>   'distinct',
        'location'            =>   'distinct',
        'job'                 =>   'distinct',
        'skill'               =>   'distinct',
        'areaofintrest'       =>   'distinct',
        'education'           =>   'distinct',
        'dateOfBirth'         =>   'distinct',
        'qualification'       =>   'distinct',
        'workExperience'      =>   'distinct',
        'panNumber'           =>   'distinct',
        'adharNumber'         =>   'distinct',
    ];
}
}
