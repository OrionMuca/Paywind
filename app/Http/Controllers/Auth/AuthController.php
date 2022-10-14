<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFormRequest;
use App\Rules\MatchOldPassword;
use GrahamCampbell\ResultType\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\LoginFormRequest;
use Yajra\DataTables\Contracts\DataTables;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{

    public function guest(){
        return view('welcome');
    }

    public function index(){
        return view('auth.login');
    }

    public function user_index(){
        if(Auth::check()){
            if(Auth::user()->role=='user'){
                return view('after_logged.user',['user'=>Auth::user()]);
            }elseif (Auth::user()->role=='admin'){
                return view('after_logged.admin.index',['user'=>Auth::user()]);
            }
        }
        return redirect()->route('login')
                    ->with('error','Ops! You do not have access');
    }

    public function fetch_single(Request $request){
        $user = User::where('id', $request->id)->first();
        return $user;
    }

    public function registration(){
        return view('auth.registration');
    }

    public function postLogin(LoginFormRequest $request){
        $input=$request->validated();

        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';//per tu loguar me email ose phone

        if(Auth::attempt(array($fieldType=>$input['email'], 'password'=>$input['password']))){
            if(Auth::user()->role =='user'){
                return redirect()->route('user.logged',['user'=>Auth::user()])
                    ->with('success','You have logged in');
            }elseif(Auth::user()->role =='admin'){
                return redirect()->route('user.logged',['user'=>Auth::user()])
                    ->with('success','You have logged in');
            }

      }
        return redirect()->route('login')->with('errors','Invalid credentials');

     }


    public function postRegistration(RegisterFormRequest $request){
         $data=$request->validated();
         $this->create($data);
           // dd($data);
         return redirect('login');
    }

    public function create($data){
       // dd($data);
        return User::query()->create([
            'first_name'=>$data['first_name'],
            'last_name'=>$data['last_name'],
            'fatherhood'=>$data['fatherhood'],
            'phone'=>$data['phone'],
            'birth_date'=>$data['birth_date'],
            'username'=>$data['username'],
            'email'=>$data['email'],
            'password'=> Hash::make($data['password']),
            'profile_pic'=>'default.jpg',
            'role'=>'user',
            'month_payment'=>'1000'
        ]);
    }

    public function logout(){
        Session::flush();
        Auth::logout();

//        return redirect('login');
    }

    public function image_insert(Request $request){
//
//        $request->validate([
//            'profile_pic' => 'required|max:2048'
//        ]);

        dd($request);
//        $avatar=$request->profile_pic;
//        $filename=time(). '.' . $avatar->getClientOriginalExtension();
//
//        $user=Auth::user();
//        $user['profile_pic']= $filename;
//
//        $request->profile_pic->move(public_path('\uploads\avatars'), $filename);
//
//        $user->save();
//
//        return redirect()->back()->with($user);
   }

   public function reset_pass(Request $request){
//        $request->validate([
//            'actual_password'=>['required',new MatchOldPassword],
//            'new_password'=>'required|min:6|different:actual_password',
//            'new_password_confirm'=> 'required|same:new_password'
//        ]);
//
//      Auth::user()->update(['password'=>Hash::make($request->new_password)]);

       $pass=User::find($request->id)->password;

       $request->validate([
           'actual_password'=>['required', function ($attribute, $value, $fail) use ($pass) {
               if (!Hash::check($value, $pass)) {
                   return $fail(__('The current password is incorrect.'));
               }
           }],
           'new_password'=>'required|min:6|different:actual_password',
           'new_password_confirm'=> 'required|same:new_password'
       ]);


       $user=User::find($request->id);
      $user->update(['password'=>Hash::make($request->new_password)]);

    }

    public function user_update(UpdateFormRequest $request){
        
        $input=$request->validated();
      
        $user=User::find($request->id);
        $user->update($input);
        
        //ddd($user);
        return $user;
    }

    public function fetch_all(Request $request){
       $users= User::select(['id','first_name','last_name','fatherhood','phone','birth_date','email','username','dateEntered','profile_pic']);

        if($_GET['start_date']!=''&& $_GET['end_date']!=''){
            $start_date= date('Y-m-d', strtotime($_GET["start_date"]));
            $end_date= date('Y-m-d', strtotime($_GET["end_date"]));
            $users->whereBetween('birth_date',[$start_date, $end_date]);

        }
       if($request->ajax()){
           $allData= datatables()->of($users)
               ->addIndexColumn()
               ->addColumn('action', function ($row){
                    $btn='<a href="javascript:void(0)" data-id="'.$row['id'].'"  class="btn btn-info btn-sm editbtn" >Edit</a>
                          <a href="javascript:void(0)" data-id="'.$row['id'].'"  class="btn btn-danger btn-sm deleteBtn" >Delete</a>
                          <a href="javascript:void(0)" data-id="'.$row['id'].'"  class="btn btn-warning btn-sm resetPass" >Reset Pass</a>';
                    return $btn;
               })
               ->addColumn('profilePicture', function ($row){
                   return '<img src="uploads/avatars/'.$row['profile_pic'].'" width="100" height="100">';
               })
               ->addColumn('full_name', function ($row){
                   return $row['first_name'].' '.$row['last_name'];
               })
                ->rawColumns(['action','profilePicture'])
                ->make();

       }
        return $allData;
    }

//    public function reset_passUser(Request $request){
//
//        $pass=User::find($request->id)->password;
//
//            $request->validate([
//                'actual_password'=>['required', function ($attribute, $value, $fail) use ($pass) {
//                    if (!Hash::check($value, $pass)) {
//                        return $fail(__('The current password is incorrect.'));
//                    }
//                }],
//                'new_password'=>'required|min:6|different:actual_password',
//                'new_password_confirm'=> 'required|same:new_password'
//            ]);
//
//            dd('Passed');
//
//    }

    public function delete_user(Request $request){
        $user =User::find($request->id);
        $user->delete();
    }

    public function create_user(RegisterFormRequest $request){
        $data= $request->validated();
        dd($data->profile_pic);
        $this->create($data);
        

    }

    public function payment(){//figure out how to show in columns

        return view('after_logged.admin.payment');
    }

    public function payment_calculate(Request $request){

        //For the weekend
        function isWeekend($date) {
            return (date('N', strtotime($date)) >= 6);}

        $query1= DB::table('working_days') //Correct??
            ->leftJoin('users','working_days.user_id','=','users.id')
            ->select('user_id','first_name','last_name','date','hours','month_payment')
            ->where(function($query1) use ($request) {
                if($request->start_date && $request->end_date){
                    $start_date= date('Y-m-d', strtotime($request->start_date));
                    $end_date= date('Y-m-d', strtotime($request->end_date));
                    $query1->whereBetween('date',[$start_date, $end_date]);
                    }

            })
            ->get();
            //  print_r($query1);
           

        $query2 = DB::table('off_days')
            ->select('*')
            ->get();


        $offDays= [];
        foreach ($query2 as $row){
            $offDays[]=  $row->date_;
        }

        $data=[];

        foreach ($query1 as $row) {

            if($row->hours<=8){
                $normalHours= $row->hours;
                $extraHours=0;
            }else{
                $normalHours=8;
                $extraHours= $row->hours-8;
            }
            $month_pay= $row->month_payment;
            $date= date('Y-m', strtotime($row->date));

            $normal_day_pay=$month_pay/22/8;//Normal day pay

            if(in_array($row->date,$offDays)){//Holiday
                $holiday_nrml=1.5*$normal_day_pay;
                $outTime_holiday=2*$normal_day_pay;
                $temp_sal=$normalHours*$holiday_nrml+$extraHours*$outTime_holiday;
            }elseif(isWeekend($row->date)){//Weekend
                $weekend_nrml=1.25*$normal_day_pay;
                $outTime_weekend=1.5*$normal_day_pay;
                $temp_sal=$normalHours*$weekend_nrml+$extraHours*$outTime_weekend;
            }else{//Normal day
                $outTime_normal_day=1.25*$normal_day_pay;
                $temp_sal=$normalHours*$normal_day_pay+$extraHours*$outTime_normal_day;
            }

            $temp_sal=round($temp_sal, 2);
            $normal_day_pay=round($normal_day_pay, 2);

            if (!isset($data[$row->user_id])){//Nese kjo id nuk eshte fare tek array data
                $data[$row->user_id] = [
                    'id' => $row->user_id,
                    'full_name' => $row->first_name." ".$row->last_name,
                    'hourly_rate' => $normal_day_pay,
                    'total_hours' => $row->hours,
                    'total_payment' => $temp_sal,
                    'months'=>[
                        $date =>[
                            'month' => $date,
                            'total_month_payment' => $temp_sal,
                            'month_hours' => $row->hours,
                            'days' => [
                                [
                                    'date' => $row->date,
                                    'day_hours' => $row->hours,
                                    'day_pay' => $temp_sal
                                ],
                            ]
                        ],
                    ]
                ];
            }else {//Nese eshte tek array data
                $data[$row->user_id]['total_hours'] +=$row->hours; //updetojme oret
                $data[$row->user_id]['total_payment'] +=$temp_sal; //updetojme pagen

                //print_r($data[$row['user_id']]['months'][$date]['month']);

                if(isset($data[$row->user_id]['months'][$date])){//Nese ky muaj ekziston
                    $data[$row->user_id]['months'][$date]['total_month_payment']+=$temp_sal;
                    $data[$row->user_id]['months'][$date]['month_hours']+=$row->hours;

                    $data[$row->user_id]['months'][$date]['days'][]=[
                        'date' => $row->date,
                        'day_hours' => $row->hours,
                        'day_pay' => $temp_sal
                    ];
                }else{//Nese jo
                    $data[$row->user_id]['months'][$date]=[
                        'month' => $date,
                        'total_month_payment' => $temp_sal,
                        'month_hours' => $row->hours,
                        'days' => [
                            [
                                'date' => $row->date,
                                'day_hours' => $row->hours,
                                'day_pay' => $temp_sal
                            ],
                        ]
                    ];
                }

            }

        }


        if($request->ajax()){
            $allData= datatables()->of($data)
                ->addIndexColumn()
                ->make();

        }
            return $allData;
    }
}
