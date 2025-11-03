<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        
        $projects = Project::whereHas('members', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with(['tasks', 'members'])
        ->get();
        
        error_log('Projects returned: ' . $projects->count());
        
        return response()->json($projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = $request->user()->projects()->create($request->all());

        return response()->json($project->load('members'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {   
        if(!$project->isMember(request()->user()->id)){
            abort(403, 'No sos miembro de este proyecto');
        }

        return response()->json($project->load('tasks.user', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        if (!$project->isOwner($request->user()->id)) {
            abort(403, 'Only project owners can edit');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project->update($request->all());
        return response()->json($project);
    }

    public function destroy(Project $project)
    {
        if (!$project->isOwner(request()->user()->id)) {
            abort(403, 'Only project owners can delete');
        }
        
        $project->delete();
        return response()->json(['message' => 'Project deleted successfully']);
    }
}
