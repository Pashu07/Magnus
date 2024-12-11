@include('layouts/crm-layout/header')
@include('layouts/crm-layout/footer')
@yield('header')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css" integrity="sha512-MQXduO8IQnJVq1qmySpN87QQkiR1bZHtorbJBD0tzy7/0U9+YIC93QWHeGTEoojMVHWWNkoCp8V6OzVSYrX0oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    .upload{
        margin-top:20px;
    }
    .download{
        margin-top:20px;

    }
    .tabular{
        margin-top:20px;
    }
    .add{
        margin-top:2%;
        width:11%;
    }
    .choose{
        width: 32%;
        margin-left:2%;
    }
    .lead-top .nav-link{
        font-size: 36px;margin-top: 8px;color: #5156be;
    }
@media only screen and (max-width: 414px){
    .choose{
      width: 76%;
        margin-left:11%;  
    }
    .download{
        margin-top:-59px;
        margin-left: 137px;
        width: 50px;
    }
    .tabular{
        margin-top:-58px;
        margin-left: 260px;
        width: 50px;
    }

        
</style>
<main class="page-content" style="margin-top: 35px">
    <section >
   <div class="page-breadcrumb d-none d-sm-flex align-items-center ">
      <div class="breadcrumb-title pe-3">Lead List</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="/getleadlist/all"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{Request::segment(2)}} List</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col d-flex" style="margin-left: 660px">
        <div class="dropdown">
            ASC: <input type="radio" value='ASC' <?php if(Request::segment(3)== "ASC") {echo "checked";} ?> onclick="window.location='https://shrihr.info/getleadlist/{{Request::segment(2)}}/ASC';" />
       DESC: <input type="radio" value='DESC' <?php if(Request::segment(3)== "DESC") {echo "checked";} ?>  onclick="window.location='https://shrihr.info/getleadlist/{{Request::segment(2)}}/DESC';" />
           <!-- /* <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Short BY   
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="https://shrihr.info/getleadlist/ASC">Ascending</a>
                <a class="dropdown-item" href="https://shrihr.info/getleadlist/DESC">Descending</a>
            </div> */-->
        </div>
      
       <input id="myInput" class="form-control"  type="text" placeholder="Search Candidate ">

        </div>
    </div>
</div>
    @if (Session::has('message'))
        <div class="toast-container  position-fixed top-0 end-0 p-2" style="z-index: 15">
            <div class="toast toast-autohide-success show" role="alert" aria-live="assertive" aria-atomic="true"
                data-bs-autohide="false">
                <div class="toast-header bg-success text-white">
                    <strong class="me-auto">Success</strong>
                    <button type="button" class="ms-1 btn-close text-white" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
                <div class="toast-body bg-success text-white">
                    {{ Session::get('message') }}

                </div>
            </div>

        </div>
    @endif

<div class="row lead-top" style="margin-top:20px" >
    <div class=" col-lg-3 ">
     <a href="{{ route('add-candidate') }}" class="btn btn-primary"> Add New</a>
 </div>

<div class="col-md-4" >
    <form class="form" action="{{ route('add-uploadexcelfile') }}" enctype="multipart/form-data"
    id="image-upload" method="POST">
    @csrf
    <input type="file" name="excel_file" id="inputImage"class="form-control"  >
    @error('excel_file')
    <span class="text-danger">{{ $message }}</span>

    @enderror

</div>
<div class="col-md-2 ">
    <button type="submit" class="btn btn-primary">Upload File</button><br>
    <small><a href="{{url('samplecandidate.csv')}}" download class="text-muted">Download sample file</a></small>
</form>
</div>
<div class="col-md-2 ">
    <a type="button"   href="{{ route('export-candidate-data') }}" value="Download" class="btn btn-primary">Download Excel</a>
</div>

<div class="col-md-1 ">
    <a class="nav-link active"  href="{{ route('All-Candidate') }}" data-bs-toggle="tooltip" data-bs-placement="center" title="" data-bs-original-title="List" aria-label="List"><i class="bx bx-list-ul"></i></a>
</div>
</div>

<ul class="nav form-stepper nav-tabs nav-justified">
  <div class="slider"></div>
  <li class="nav-item form-stepper-active text-center form-stepper-list" step="1" >
      <!-- <a  class="nav-link btn-navigate-form-step" href="#" step_number="2"><i class="fa fa-cogs"></i> General</a> -->
      <a class="nav-link btn-navigate-form-step" type="button" href="{{ route('leadlist','all') }}"step_number="1" style="border: 1px solid"> <i class="f"></i> All</a>
  </li>
  <li class="nav-item form-stepper-unfinished text-center form-stepper-list" step="2">
   <a class="nav-link btn-navigate-form-step"  type="button" href="{{ route('leadlist','attendent') }}"  step_number="2" style="border: 1px solid"><i class=""></i>Attendent Lead</a>

</li>
<li class="nav-item form-stepper-unfinished text-center form-stepper-list" step="2">
   <a class="nav-link btn-navigate-form-step"  type="button" href="{{ route('leadlist','unattendent') }}"  step_number="2" style="border: 1px solid"><i class=""></i>  Unattendent Lead</a>

</li>
<li class="nav-item form-stepper-unfinished text-center form-stepper-list" step="2">
   <a class="nav-link btn-navigate-form-step"  type="button" href="{{ route('followup-list') }}"  step_number="2" style="border: 1px solid"><i class=""></i>Today Followup</a>

</li>
<li class="nav-item form-stepper-unfinished text-center form-stepper-list" step="2">
   <a class="nav-link btn-navigate-form-step"  type="button" href="{{ route('selected-Candidate') }}"  step_number="2" style="border: 1px solid"><i class=""></i>Selected Candidate</a>

</li>

</ul>
                <script>
                  var input = document.getElementById("myInput");
                  input.addEventListener("input", myFunction);

                  function myFunction(e) {
                      var filter = e.target.value.toUpperCase();
                      var list = document.getElementById("subproduct");
                      var divs = list.getElementsByClassName("col");
                      for (var i = 0; i < divs.length; i++) {
                        var a = divs[i].getElementsByTagName("h5")[0];
                        if (a) {
                          if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                            divs[i].style.display = "block";
                        } else {
                            divs[i].style.display = "none";
                        }
                    }
                }
            }
            var input = document.getElementById("myInput");
                  input.addEventListener("input", myFunction);

                  function myFunction(e) {
                      var filter = e.target.value.toUpperCase();
                      var list = document.getElementById("subproduct");
                      var divs = list.getElementsByClassName("col");
                      for (var i = 0; i < divs.length; i++) {
                        var a = divs[i].getElementsByTagName("ul")[0];
                        if (a) {
                          if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                            divs[i].style.display = "block";
                        } else {
                            divs[i].style.display = "none";
                        }
                    }
                }
            }

        </script>

</section>
<?php 
     use App\Models\Candidate;
     use App\Models\User;

?>
<div class="card bg-transparent shadow-none" style=" margin-top: 20px;">
  <div class="card-body">

    <div class="my-3 border-top"></div>
    <div class="row row-cols-1 row-cols-lg-3 justify-content-center g-lg-5" id="subproduct">
        @foreach($candidate as $data)
        
        <div class="col" >
            <div class="card" data-city="barca" style="">
              <div class="card-body border-bottom">
                <a  href="{{ route('lead-add',$data->cand_id) }}" class=""><u><b><h5>{{ $data->name }}</h5></b></u></a>
             </div>
             <ul class="list-group list-group-flush">
                <span style="display: none;">{{ $data->name }}</span>
                <li class="list-group-item"><b>EMAIL :</b>{{ $data->email }}</li>
                <li class="list-group-item"><b>MOBILE :</b>{{ $data->number }}</li>
                <li class="list-group-item"><b>Gender :</b>{{ $data->gender }}</li>
                <li class="list-group-item"><b>JOB :</b>{{ $data->job }}</li>
                <li class="list-group-item"><b>INTREST :</b>{{ $data->areaofintrest }}</li>
                @php 
                   $added = $data->added_by ;
                   $emp_name = Candidate::join('users', 'users.id', '=', 'candidate.added_by') ->where('users.id', $data->added_by)->get(); 
/* dd($emp_name['0']['name']); */
                 @endphp
                 @if(isset($data->temp_added) )
                 <li class="list-group-item"><b>FRONT ADDED BY :</b>{{ $data->temp_added }}</li>

                @else
                    <li class="list-group-item"><b>ADDED BY :</b>{{ $emp_name['0']['name']}}</li>

                @endif

                
                <li class="list-group-item"><b>Date Of Birth :</b>{{ $data->dob }} / (<b>AGE:</b> {{ $data->dob_number }})</li>
                @php
                if($data->status == 1){
                    $leadss = DB::table('leads')
                    ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')
                    ->join('multilead' , 'multilead.lead_id' , '=' , 'leads.id')
                    ->join('companies' , 'multilead.company_name' , '=' , 'companies.id')
                    ->select('candidate.*','companies.organisation','companies.company_name as comp_name','multilead.*','candidate.id as cand_id')
                    ->where('leads.candidate_id', $data->id )
                    ->orderBy('leads.id','DESC')
                    ->limit(3)
                    ->get();
                }
                @endphp
                @if($data->status == 1)

                @if($leadss->isNotEmpty())
                <h6 class="text-center">Last Interview Status</h6>
                <table class="table align-middle mb-0" id="example">
                  <thead>
                    <th>Round</th>
                    <th>Company</th>
                    <th>Time</th>
                    <th>Remark</th>
                </thead>
                <tbody>
                    <?php foreach ($leadss as $leads): ?>


                     <tr>
                        <td> {{ $leads->schedule}}</td>
                        <td> {{ $leads->comp_name}}</td>
                        <td>{{$leads->interview_date}}</td>
                        <td>{{$leads->company_remark}}</td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        @endif
        @endif
        @if(Request::segment(2) == "attendent")
        <li class="list-group-item"><b>Interview Status :</b>{{ $data->status}}</li>


        @else
        <li class="list-group-item" ><b>STATUS :</b> <?php if ($data->status == 1): ?>
            @php 
                  $status = DB::table('leads')
                    ->join('candidate' , 'candidate.id' , '=' , 'leads.candidate_id')
                    ->where('leads.candidate_id', $data->id )
                    ->orderBy('leads.id','DESC')
                    ->select('*','leads.status as lead_status')
                    ->limit(1)
                    ->get();
                    if($status->isEmpty()){
                        echo "In Process";
                    }else{
                        echo $status['0']->lead_status ;
                    }
            @endphp
        <?php else: ?>
            No Followup
        <?php endif ?></li>

        @endif
        <li class="list-group-item"><b>GET RESUME :</b>

        @if ($data->resume != '')
              @if ($data->frontend == 'front_end_data')
              <a download  style="margin-left: 35px";
              href="{{ asset('documents/frontend/resume/' . $data->resume) }}">Get
            Resume</a>
            @else
            <a download  style="margin-left: 35px"; href="{{ asset('documents/resume/' . $data->resume) }}">Get
            Resume</a>
            @endif
            @else
            <span style="margin-left: 35px";>-</span>
            @endif


        </li>
        <li class="list-group-item"><b>GET Video :</b>

        @if ($data->vedio != '')
            @if ($data->frontend == 'front_end_data')
            <a download  style="margin-left: 35px";
            href="{{ asset('documents/frontend/vedio/' . $data->vedio) }}">Get
            Video</a>
            @else
            <a download  style="margin-left: 35px"; href="{{ asset('documents/vedio/' . $data->vedio) }}">Get
                Video</a>
            @endif
            @else
            <span style="margin-left: 35px";>-</span>
            @endif


        </li>
        <li class="list-group-item"><b>Created At:</b> {{$data->created_at}}

    </ul>
    <div class="card-body border-top">
       <a class="btn btn-outline-success btn-sm" href="https://api.whatsapp.com/send?phone=+91<?php echo $data->number ?>&text=Hello" target="_blank">
        <i class="fa fa-whatsapp" class="float" target="_blank"></i>
    </a>
    <a class="btn btn-outline-secondary btn-sm" href="mailto:{{ $data->email }}"> <i class='fa fa-envelope'></i></a>

<a class="btn btn-outline-success btn-sm" href="sms:+91<?php echo $data->number ?>" target="_blank">
        <i class="fa fa-comment" class="float" target="_blank"></i>
    </a>


<a class="btn btn-outline-success btn-sm" href="tel:{{ $data->number }}"><i class='fa fa-phone'></i>
    <!-- <h4><img src="assets/images/gallery/icons8-calling-16.png" style="width: 30px;">  <a href="" style="color:rgb(192, 118, 49)"></a></h4> -->
</a>
<div class="actions d-flex align-items-center justify-content-center gap-2 mt-3" >

    <a href="{{route('update-candidate',$data->id)}}" class="btn btn-sm glyphicon glyphicon-pencil" style="color: blue;">Edit</a>
    <a href="{{ route('delete-candidate',$data->id)}}"
        class="btn btn-sm glyphicon glyphicon-trash" onclick="return myFunction();" style="color: red;" >Delete</a>
<!--  for not contact -->
            
        @if(Request::segment(2) == "attendent" || $data->status == 1  )
        
        <button onclick="openInterviewModel('{{ $data->cand_id}}')" type="button" class="btn btn-primary float-end" style="margin-left: 10px; " data-bs-toggle="modal"
            data-bs-target="#addInterview">
            Add Interview Round
        </button>
        @else
        <button onclick="openModel('{{ $data->cand_id}}')" type="button" class="btn btn-primary float-end" style="margin-left: 10px; " data-bs-toggle="modal"
            data-bs-target="#exampleModal">
            Add Follow Up
        </button>
        @endif
    </div>   
</div>

</div>
</div>
@endforeach


<script type="text/javascript">
   function openModel(id){
    $("#cand_id").val(id);
    $("#exampleModal").modal("show");
    $("#exampleModal").find('form').trigger('reset');

}

function openInterviewModel(id){
    $("#interview_cand_id").val(id);
    $("#addInterview").modal("show");
    $("#addInterview").find('form').trigger('reset');

}
</script>


</div><!--end row-->
</div>
</div>
</main>


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Follow Up <strong></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                @method('post')
                {{-- <input type="hidden" name="created_by" value="{{Session::get('user')['email']}}"> --}}
                <form class="form" action="{{ route('add-lead-form') }}" enctype="multipart/form-data" method="POST">
                    @csrf

                    <input type="hidden" name="candidate_id" id="cand_id" value=>
                    <!--     <input type="hidden" name="name" value=>
                        <input type="hidden" name="email" value=>
                        <input type="hidden" name="number" value=>
                    -->
                    <div class="row">
                        <!-- <div class="col-md-6 col-12 mb-1">
                            <label class="form-check-label">
                                Organization :
                            </label>
                            <select class="form-select  select2" aria-label="Default select example" name="organisation" required>
                                <option value=" ">Select organization</option>
                                <option value="ShriHR">ShriHR</option>
                                <option value="FireFox Solutions">FireFox Solutions</option>
                            </select>
                        </div> -->
                        <div class="col-md-6 col-12 mb-1">
                            <label class="form-check-label">
                                Status :
                            </label>
                            <select class="form-select status select2" aria-label="Default select example" name="status" required>
                                <option value=" ">Select Status</option>
                                <option value="Contact">Contact</option>
                                <option value="Not_Contact">Not Contact</option>
                                {{-- <option value="Not_Answerd">Not Answerd</option> --}}
                                <option value="Call_Back">Call Back</option>
                            </select>
                        </div>

                        {{-- start approve div  for interview --}}
                        <div class="Contact box">
                            <div class="row">
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-6 col-12 mb-1">
                                            <label class="form-label" for="country-floating">Remark </label>
                                            <input type="text" class="form-control"
                                            name="status_remark"/>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- end approved div --}}

                        {{-- start not approved div --}}

                        <div class="Not_Contact box">
                            <div class="row">
                                <div class="col-md-6 col-12 mb-1">
                                    <label class="form-check-label">
                                        Response :
                                    </label>
                                    <select class="form-select select2" aria-label="Default select example" name="not_contact">
                                        <option Value=" "> Select </option>
                                        <option value="Ringing">Ringing</option>
                                        <option value="Switch Off">Switch Off</option>
                                        <option value="Not Reachable">Not Reachable</option>
                                        <option value="Wrong Number">Wrong Number</option>
                                    </select>
                                </div>
                                {{-- <div class="col-md-6 col-12 mb-1">
                                    <label class="form-label" for="country-floating">Not Contact Remark</label>
                                    <input type="text" id="country-floating" class="form-control" name="not_intrest" placeholder="insert resone" />
                                </div> --}}
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mb-1 Not_Answerd box">
                            <label class="form-label" for="country-floating">Not Answer </label>
                            <input type="text" id="country-floating" class="form-control" name="not_answer"
                            />
                        </div>
                        <div class="col-md-6 col-12 mb-1 Not_Answerd box">
                            <label class="form-label" for="country-floating">Not Answer
                            Date</label>
                            <input type="datetime-local" id="country-floating" class="form-control"
                            name="not_answer_date" />
                        </div>
                        <div class="col-md-6 col-12 mb-1 Call_Back box">
                            <label class="form-label" for="country-floating">Call Back Remark</label>
                            <input type="text" id="country-floating" class="form-control" name="call_back"
                            />
                        </div>
                        <div class="col-md-6 col-12 mb-1 Call_Back box">
                            <label class="form-label" for="country-floating">Call Back
                            Date</label>
                            <input type="text"  id="basicDate3" class="form-control"
                            name="call_back_date" />
                        </div>
                        <div class="col-md-6 col-12 mb-1 Wrong_Number box">
                            <label class="form-label" for="country-floating">Wrong Number </label>
                            <input type="text" id="country-floating" class="form-control" name="wrong_no"
                            />
                        </div>

                        {{-- end not approved div --}}

                        <div class=" text-center mt-2  pt-10 col-12">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </form>

                <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

             </div>
         </div>
     </div>
 </div>


</div>
<div class="modal " id="addInterview" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="interviewLabel" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="interviewLabel">Add Interview  <strong></strong></h5>
            {{-- <p></p> --}}

            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <form class="form" action="{{ route('add-lead-form') }}" enctype="multipart/form-data"
            method="POST">
            @method('post')
            @csrf
            {{-- <input type="hidden" name="created_by" value="{{Session::get('user')['email']}}"> --}}

            <input type="hidden" name="candidate_id" id="interview_cand_id" >
            <input type="hidden" name="name" >
            <input type="hidden" name="email" >
            <input type="hidden" name="number" >

            <div class="row">
                <div class="col-md-6 col-12 mb-1">
                    <label class="form-check-label">
                        Status :
                    </label>
                    <select class="form-select status select2" aria-label="Default select example" name="status" required>
                        <option value=" ">Select Status</option>
                        <option value="Screening">Short List For Screening</option>
                        <option value="Shortlisted">Shortlisted</option>
                        <option value="Rejected">Rejected</option>

                    </select>
                </div>

                {{-- start approve div  for interview --}}
                <div class=" ">
                    <div class="row">


                        {{-- round_1 div --}}
                        <div class=" Shortlisted-list Shortlisted box  " >
                            <div class="element  add_control" id='div_1'>
                                <hr><br>
                                <div class="row">
                                    <div class="col-md-6 col-12 mb-1">
                                        <label class="form-check-label">
                                            Schedule Interview:
                                        </label>
                                        <select class="form-select schudle select2 mb-1" aria-label="Default select example"
                                        name="schedule[]">
                                        <option>Select Mode</option>
                                        <option value="1">Round 1</option>
                                        <option value="2">Round 2</option>
                                        <option value="3">Round 3</option>
                                        <option value="4">Round 4 (Selected)</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-12 mb-1">
                                    <label class="form-label" for="country-floating">Date and Time</label>
                                    <input type="date"  class="showdate form-control"
                                    name="interview_date[]" />
                                </div>


                                <div class="col-md-6 col-12 mb-1">
                                    <label class="form-label" for="country-floating">Remark </label>
                                    <input type="text" class="form-control"
                                    name="company_remark[]"/>
                                </div>

                                <div class=' ' >
                                    <div class="row col-md-12 col-sm-12 col-lg-12 mt-2 ">

                                     <div class="col-md-4  col-12 mb-1">
                                        <label class="form-check-label">
                                            Company Name :
                                        </label>
                                        <select class="form-select select2 companyName" aria-label="Default select example" 
                                        name="company_name[]">
                                        <option value=" ">Company Names </option>
                                        @foreach ($companies as $company )
                                        <option value="{{ $company->comp_id }}">{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" id="vacancy_id_" class="form-control"
                                name="vacancy_id" placeholder="Vacancy Id" readonly/>

                                <div class="col-md-4  col-12 mb-1">
                                    <label class="form-check-label">
                                        Select Vertical
                                    </label>
                                    <select id="" name="vartical_name[]"
                                    class="form-control select2 vartical_name_">
                                    <option value=" ">Select Vertical</option>
                                </select>
                            </div>

                            <div class="col-md-3 col-12 mb-1">
                                <label class="form-check-label">
                                    Select Designation
                                </label>
                                <select id="" name="designation_name[]"
                                class="form-control select2 designation_name_">
                                <option value=" ">Select Designation</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-12">
            <div class="mt-1">
                <button type="button" name="add" class="add-section btn btn-success">+ Add Another Round</button>
            </div>
        </div>
    </div>

</div>
</div>


</div>

{{-- end approved div --}}

{{-- start not approved div --}}

<div class="Rejected box">
    <div class="row">
        
        <div class="col-md-6 col-12 mb-1">
            <label class="form-label" for="country-floating">Rejected Remark</label>
            <input type="text" id="country-floating" class="form-control" name="status_remark" placeholder="insert response" />
        </div> 

    <div class="col-md-6 col-12 mb-1 ">
        <label class="form-label" for="country-floating">Reschedule </label>
        <input type="text" id="country-floating" class="form-control datepick" name="not_answer"
        />
    </div>
    <div class="col-md-6 col-12 mb-1 Not_Answerd box">
        <label class="form-label" for="country-floating">Reschedule
        Date</label>
        <input type="datetime-local" id="country-floating" class="form-control"
        name="Reschedule_date" />
    </div>
    </div>



</div>
<div class=" text-center mt-2  pt-10 col-12">
    <button class="btn btn-primary" type="submit">Submit</button>
    <br><br>
</div>
</form>

<div class="modal-footer">
 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

<div class="modal" id="addInterviewSuccess" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="interviewLabel" aria-hidden="true">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Follow UP Data For Organisation <strong></strong></h5>

            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <form class="form" action="{{ route('add-lead-form') }}" enctype="multipart/form-data"
            method="POST">
            @method('post')
            @csrf
            <input type="hidden" name="candidate_id" value="">
            <input type="hidden" name="name" value=>
            <input type="hidden" name="email" >
            <input type="hidden" name="number">

            <div class="row">
                <div class="col-md-6 col-12 mb-1">
                    <label class="form-check-label">
                        Offer :
                    </label>
                    <select class="form-select status select2" aria-label="Default select example" name="status" required>
                        <option value=" ">Select Status</option>
                        <option value="Accepted">Accepted</option>
                        <option value="Not_Accepted">Not Accepted</option>

                    </select>
                </div>

                {{-- start Accepted div After interview --}}
                <div class="Accepted box">
                    <div class="row">

                        <div class="col-md-6 col-12 mb-1">
                            <label class="form-label" for="country-floating">Company Name </label>
                            <input type="text" class="form-control"
                            name="company_remark"/>
                        </div>

                        <div class="col-md-6 col-12 mb-1">
                            <label class="form-label" for="country-floating">
                            Date Of Joining</label>
                            <input type="text" id="basicDate1" class="form-control"
                            name="recruiter_visit_date" />
                        </div>

                        <div class="col-md-6 col-12 mb-1">
                            <label class="form-label" for="country-floating">CTC </label>
                            <input type="text" id="country-floating" class="form-control"
                            name="recruiter_remark"
                            autocomplete="off" />
                        </div>


                        <div class="col-md-6 col-12 mb-1 ">
                            <label class="form-label" for="country-floating">Vertical </label>
                            <input type="text" id="country-floating" class="form-control"
                            name="int_remark" />
                        </div>


                        <div class="col-md-6 col-12 mb-1 ">
                            <label class="form-label" for="country-floating">Designation </label>
                            <input type="text" id="country-floating" class="form-control"
                            name="int_remark" />
                        </div>
                        <div class="col-md-6 col-12 mb-1">
                            <label class="form-label" for="country-floating">Branch </label>
                            <input type="text" id="country-floating" class="form-control"
                            name="recruiter_remark"
                            autocomplete="off" />
                        </div>


                        <div class="col-md-6 col-12 mb-1 ">
                            <label class="form-label" for="country-floating">E-Code </label>
                            <input type="text" id="country-floating" class="form-control"
                            name="int_remark" />
                        </div>


                        <div class="col-md-6 col-12 mb-1 ">
                            <label class="form-label" for="country-floating">Official E-mail ID</label>
                            <input type="text" id="country-floating" class="form-control"
                            name="int_remark" />
                        </div>
                        <div class="col-md-6 col-12 mb-1 ">
                            <label class="form-label" for="country-floating">Contact No </label>
                            <input type="text" id="country-floating" class="form-control"
                            name="int_remark" />
                        </div>
                    </div>
                </div>

                {{-- end Accepted div --}}

                {{-- start Not Accepted div --}}

                <div class="Not_Accepted box">
                    <div class="row">

                       <div class="col-md-6 col-12 mb-1">
                        <label class="form-label" for="country-floating">Rejected Remark</label>
                        <input type="text" id="country-floating" class="form-control" name="not_intrest" placeholder="insert resone" />
                    </div> 

                </div>
                <div class="col-md-6 col-12 mb-1 ">
                    <label class="form-label" for="country-floating">Reschedule </label>
                    <input type="text" id="country-floating" class="form-control datepick" name="not_answer"
                    />
                </div>
                <div class="col-md-6 col-12 mb-1 Not_Answerd box">
                    <label class="form-label" for="country-floating">Reschedule
                    Date</label>
                    <input type="datetime-local" id="country-floating" class="form-control"
                    name="Reschedule_date" />
                </div>


                {{-- end not approved div --}}

                <div class=" text-center mt-2  pt-10 col-12">
                    <button class="btn btn-primary" type="submit">Submit+</button>
                </div>

            </div>
        </div>
    </form>

    <div class="modal-footer">
     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

 </div>
</div>
</div>
</div>
@yield('footer')


<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js" integrity="sha512-K/oyQtMXpxI4+K0W7H25UopjM8pzq0yrVdFdG21Fh5dBe91I40pDd9A4lzNlHPHBIP2cwZuoxaUSX0GJSObvGA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
    $(document).ready(function() {

        // Add new element
        $(document).on('click', '.add-section', function() {
            var total_element = $(".element").length;
            /* alert(total_element); */

            // last <div> with element class id
            var lastid = $(".element:last").attr("id");
            var split_id = lastid.split("_");
            var nextindex = Number(split_id[1]) + 1;
            var max = 25;
            // Check total number elements
            if (total_element < max) {
                // Adding new div container after last occurance of element class
                $(".element:last").after("<div class='element' id='div_" + nextindex + "'></div>");



                var menu =$(".add_control").html();    
 // alert(menu);        
                // Adding element to <div>
                $("#div_" + nextindex).append(menu);
                $("#div_" + nextindex+" .showdate").flatpickr({
                 enableTime: true,
                 dateFormat: "Y-m-d H:i",
                 position: "auto"
             });
            }
            else {
                alert('Maximum Limit is 20');
            }
        });
        // Remove element
        $('.container').on('click', '.remove', function() {
            var id = this.id;
            var split_id = id.split("_");
            var deleteindex = split_id[1];
            // Remove <div> with id
            $("#div_" + deleteindex).remove();
        });

        var postURL = "<?php echo url('addmore'); ?>";
        var i = 1;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }); 
</script>


<script type="text/javascript">


    $(document).ready(function() {
        $(".box").hide();

        $("#basicDate").flatpickr({
         enableTime: true,
         dateFormat: "Y-m-d H:i",
         position: "center"
     });

        $(".showdate").flatpickr({
         enableTime: true,
         dateFormat: "Y-m-d H:i",
         position: "auto"
     });

        $(".datepick").flatpickr({
         enableTime: true,
         dateFormat: "Y-m-d H:i",
         position: "center"
     });

        $("#basicDate1").flatpickr({
         enableTime: true,
         dateFormat: "Y-m-d H:i",
         position: "center"
     });

        $("#basicDate2").flatpickr({
         enableTime: true,
         dateFormat: "Y-m-d H:i",
         position: "center"
     });

        $("#basicDate3").flatpickr({
         enableTime: true,
         dateFormat: "Y-m-d H:i",
         position: "center"
     });

        $('#join_status').change(function() {
            var docs = $(this).val();
            // alert(docs);
            var condicateId = $("#condicate_id").val();
            let _token   = $('meta[name="csrf-token"]').attr('content');
            // console.log(docs);
            // console.log(condicateId);
            $.ajax({
                url: "/lead/update",
                data: {
                    _token:_token,
                    'candidate_id': condicateId,
                    "value": $("#join_status").val()
                },
                dataType: "html",
                type: "POST",
                success: function(data) {
                    // console.log(docs);
                    $('#join_status').append(data);
                }
            });
        });
    });



    $(document).ready(function() {
        $(".status").change(function() {
            $(this).find("option:selected").each(function() {
                var optionValue = $(this).attr("value");
                if (optionValue) {
                    $(".box").not("." + optionValue).hide();
                    $("." + optionValue).show();
                } else {
                    $(".box").hide();
                }
            });
        }).change();
    });

    $(document).ready(function() {
        $(".schudle").change(function() {
            $(this).find("option:selected").each(function() {
                var optionValue = $(this).attr("value");
                if (optionValue) {
                    $(".ofv").not("." + optionValue).hide();
                    $("." + optionValue).show();
                } else {
                    $(".ofv").hide();
                }
            });
        }).change();

        $(document).on('change', '.companyName', function() {


            var parent_id = "#"+$(this).closest('.element').attr('id');

            var idcompany = this.value;
            // alert(idCompany)
            // $("#sub-location").html('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ Route('fetch-location-apply') }}",
                type: "POST",
                data: {
                    location_id: idcompany,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                 $(parent_id+" .position_name_").html('');
                 $(parent_id+" .designation_name_").html('');
                 $(parent_id+" .vartical_name_").html('');
                    // $('#candidateName').html('<option value="">Select State</option>');
                 $.each(result.states, function(key, value) {


                    $(parent_id+" .designation_name_").append('<option value="' + value.desID + '">' + value.designation_name + '</option>');

                    $(parent_id+" .position_name_").append('<option value="' + value.posID + '">' + value.position_name + '</option>');

                    $(parent_id+" .vartical_name_").append('<option value="' + value.vertID + '">' + value.vartical_name + '</option>');

                        // console.log(value.vartical_name);
                        // console.log(value.number);

                });

             }
         });
        });

        $('#recruiterName').on('change', function() {
            var idRecruiter = this.value;
            // alert(idRecruiter)
            // $("#sub-location").html('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ Route('fetch-recruiter-branch') }}",
                type: "POST",
                data: {
                    recruiter_id: idRecruiter,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {

                    $('#recruiterBranch').html('<option value="">Select Branch Location</option>');
                    $.each(result.recruiterBranch, function(key, value) {
                        console.log(value);
                        $("#recruiterBranch").append('<option value="' + value
                            .branch_name + '">' + value.branch_name + '</option>');
                    });

                }
            });
        } );
    });

</script>
<script type="text/javascript">

    $(document).ready(function() {

    });

    $(document).ready(function() {
        $("#user_add").on('click', function(e) {
            e.preventDefault();
            var name = $("#user_name").val();

            var candidate = [];
            $(".candidate_checkbox").each(function() {
                if ($(this).is(":checked")) {
                    candidate.push($(this).val());
                }
            });
                candidate = candidate.toString(); // toString function convert array to string
                // console.log(candidate)
                // console.log(name)
                let _token = $('meta[name="csrf-token"]').attr('content');
                var ajax_url = "{{ route('candidateAdd') }}";
                if (name !== "" && candidate.length > 0) {


                    $.ajax({
                        url: ajax_url,
                        type: "POST",
                        cache: false,
                        dataType: "json",
                        data: {
                            _token: _token,
                            'name': name,
                            'candidate': candidate
                        },
                        success: function(data) {
                            window.location.reload(true);
                        // console.log(data)
                            if (data > 0) {
                                window.location.reload();
                                $("#user_add").trigger("reset");
                                alert("Data insert in database successfully");
                            }


                        }
                    });
                } else {
                    alert("Fill the required fields");
                }
            });
    });
</script>

<script>
    jQuery(document).ready(function($) {

        $(".user-list-tables").on("click", ".form-check-input", function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var value = $(this).data('test');
            getRow(id, value);
        });

    });

    function getRow(id, value) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({


        });
    }

    $(document).ready(function() {
        $('#example').DataTable( {
            "ordering": false,
            initComplete: function () {
            this.api().columns([3,4,5,6,7]).every( function (d) {//THis is used for specific column
                var column = this;
                var theadname = $('#example th').eq([d]).text();
                var select = $('<select class="mx-1 form-control"><option value="">'+theadname+': All</option></select>')
                .appendTo( '#filter_table' )
                .on( 'change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                        );

                    column
                    .search( val ? '^'+val+'$' : '', true, false )
                    .draw();
                } );
                column.data().unique().sort().each( function ( d, j ) {
                    var val = $('<div/>').html(d).text();
                    select.append( '<option value="'+val+'">'+val+'</option>' )
                } );
            } );
        }
    } );
    } );


</script>

<script>
    function myFunction() {
        if(!confirm("Are You Sure to delete this"))
            event.preventDefault();
    }
</script>
