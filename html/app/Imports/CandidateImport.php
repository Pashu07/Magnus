<?php

namespace App\Imports;

use Auth;
use App\Models\Candidate;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class CandidateImport implements ToCollection , WithHeadingRow , WithValidation
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            Candidate::updateOrCreate([
                'frontend'      =>   $row['frontend'],
                'name'          =>   $row['name'],
                'number'        =>   $row['number'],
                'email'         =>   $row['email'],
                'job'           =>   $row['job'],
                'location'      =>   $row['location'],
                'skill'         =>   $row['skill'],
                'education'     =>   $row['education'],
                'areaofintrest' =>   $row['areaofintrest'],
                'added_by'      =>   Auth::id(),
            ],'email');
        }
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:candidate',
             // Above is alias for as it always validates in batches
             '*.email' =>'required|email|unique:candidate',
        ];
    }
}
