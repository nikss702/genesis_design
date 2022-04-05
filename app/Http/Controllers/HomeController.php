<?php

namespace App\Http\Controllers;

use App\ProjectType;
use App\Project;
use App\Statics\Statics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $return = null;
        
        switch (Auth::user()->role) {
            case (Statics::USER_TYPE_CUSTOMER):
                $types = ProjectType::where('is_hidden', false)->get();
                $user_id=Auth::user()->id;
                if(Auth::user()->hasRole('customer'))
                $projectQuery = Auth::user()->projects()->latest()->paginate(3);
                else
                $projectQuery = Auth::user()->assignedProjects()->latest()->paginate(3);
                $return = view('index')->with('projectQuery', $projectQuery)->with('projectTypes',$types);
                break;
            case (Statics::USER_TYPE_ENGINEER):
                $types = ProjectType::where('is_hidden', false)->get();
                $user_id=Auth::user()->id;
                if(Auth::user()->hasRole('customer'))
                $projectQuery = Auth::user()->projects()->latest()->paginate(3);
                else
                $projectQuery = Auth::user()->assignedProjects()->latest()->paginate(3);
                $return = view('index')->with('projectQuery', $projectQuery)->with('projectTypes',$types);
                break;

            case (Statics::USER_TYPE_ADMIN):
                $return = redirect(url('admin/home'));
                break;
            case (Statics::USER_TYPE_MANAGER):
                    $return = redirect(url('manager/home'));
                    break;
        }

        return $return;
    }
}
