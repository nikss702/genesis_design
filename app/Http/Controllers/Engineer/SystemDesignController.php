<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use App\Statics\Statics;
use App\Project;
use App\User;
use App\SystemDesign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemDesignController extends Controller
{
    public function index($project_id)
    {
        $projects = Project::findOrFail($project_id);
        if($projects->enginner_id)
        {
            $user = User::findOrFail($projects->engineer_id);
            $project = $user->assignedProjects->where('id', $project_id)->where('engineer_id', Auth::id())->firstOrFail();
        }
        else{
            $project = $projects;
        }
        //$project = Auth::user()->assignedProjects()->where('id', $project_id)->where('engineer_id', Auth::id())->firstOrFail();
        return view('design.list', ["project" => $project]);
    }

    public function view($id)
    {
        $engineer_id = SystemDesign::findOrFail($id)->project->engineer;
        $design = $engineer_id->assignedDesigns()->with(['project.files.type', 'type', 'files', 'changeRequests', 'proposals' => function($query){
            $query->with(['changeRequest' => function($query){
                $query->select('id', 'proposal_id', 'status', 'created_at');
            }])->latest();
        }])->where('system_designs.id', $id)->firstOrFail();

        return view('engineer.design.view', ["design" => $design, 'fileTypes' => $design->project->files->groupBy('type.name')]);
    }

    public function getDesigns(Request $request)
    {
        $this->validate($request, [
            "id" => "required|numeric"
        ]);
        $project = Project::findOrFail($request->id);
        if($project->enginner_id)
        {
            $user = User::findOrFail($project->engineer_id);
            $project = $user->assignedProjects()->where('id', $request->id)->where('engineer_id', Auth::id())->firstOrFail();
        }
        //$project = Auth::user()->assignedProjects()->where('id', $request->id)->where('engineer_id', Auth::id())->firstOrFail();
        if ($project) {

            $query = $project->designs()->with('type')->withCount('proposals');

            $term = trim($request->search);
            if ($term)
                $query->where('name', 'LIKE', '%' . $term . "%");

            $filters = json_decode($request->filters);
            foreach ($filters as $filter)
                if ($filter->value != 'all')
                    $query->where($filter->field, '=', $filter->value);

            return $query->latest()->paginate(5);
        } else {
            abort(403);
            return false;
        }
    }

    public function start($id){
        $design = Auth::user()->assignedDesigns()->with(['project.files.type', 'type', 'files'])->where('system_designs.id', $id)->firstOrFail();
        $design->status = Statics::DESIGN_STATUS_IN_PROGRESS;
        $design->update();
        return view('engineer.design.view', ["design" => $design, 'fileTypes' => $design->project->files->groupBy('type.name')]);
    }
}
