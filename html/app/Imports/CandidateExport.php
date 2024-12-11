<?php 
namespace App\Imports;
use App\Models\Candidate;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Session;
use Auth;
class CandidateExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */ 
    public function headings():array{
        return[
            'id',
            'name',
            'number',
            'email',
            'location',
            'job' ,
            'skill' ,
            'areaofintrest' ,
            'education' ,
            'dateOfBirth' ,
            'qualification' ,
            'workExperience' ,
            'panNumber' ,
            'adharNumber' ,
            'created_at' ,
        ];
    } 
    public function collection()
    {

  if (Session::get('user')['user_role'] == 'ADMIN') {

         $type = DB::table('candidate')->select( 'id',
            'name',
            'number',
            'email',
            'location',
            'job' ,
            'skill' ,
            'areaofintrest' ,
            'education' ,
            'dateOfBirth' ,
            'qualification' ,
            'workExperience' ,
            'panNumber' ,
            'adharNumber' ,
            'created_at')
         ->where('IsDeleted',0)
         ->get();
     }else{
         $type = DB::table('candidate')->select( 'id',
            'name',
            'number',
            'email',
            'location',
            'job' ,
            'skill' ,
            'areaofintrest' ,
            'education' ,
            'dateOfBirth' ,
            'qualification' ,
            'workExperience' ,
            'panNumber' ,
            'adharNumber' ,
            'created_at')
         ->where('IsDeleted',0)
         ->where('added_by',Auth::id())
         ->get();
     }
        return $type ;
    }
}