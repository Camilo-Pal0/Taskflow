<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        $tasks = Task::whereHas('project.members', function ($query) use ($request){
            $query->where('user_id', $request->user()->id);
        })->with(['project', 'user'])->get();

        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'status' => 'in:todo,in_progress,done',
            'user_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date'
        ]);

        $project = Project::findOrFail($request->project_id);
        
        if (!$project->isMember($request->user()->id)) {
            abort(403, 'You are not a member of this project');
        }

        if ($request->has('user_id') && $request->user_id) {
            if (!$project->isMember($request->user_id)) {
                abort(403, 'Cannot assign task to non-member');
            }
            $userId = $request->user_id;
        } else {
            $userId = $request->user()->id;
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'project_id' => $request->project_id,
            'status' => $request->status ?? 'todo',
            'user_id' => $userId,
            'due_date' => $request->due_date
        ]);

        // Crear notificación si se asigna a otro usuario
        if ($userId !== $request->user()->id) {
            \App\Models\Notification::create([
                'user_id' => $userId,
                'type' => 'task_assigned',
                'title' => 'Nueva tarea asignada',
                'message' => $request->user()->name . ' te ha asignado una tarea: ' . $task->title,
                'data' => [
                    'task_id' => $task->id,
                    'project_id' => $project->id
                ]
            ]);
        }

        return response()->json($task->load('user'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        if (!$task->project->isMember(request()->user()->id)) {
            abort(403, 'No tienes permiso para ver esta tarea');
        }
        
        return response()->json($task->load(['project', 'user', 'comments.user']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        if (!$task->project->isMember($request->user()->id)) {
            abort(403);
        }

        $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:todo,in_progress,done',
            'user_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date'
        ]);

        // Si se cambia la asignación, verificar que el nuevo usuario sea miembro
        if ($request->has('user_id') && $request->user_id) {
            if (!$task->project->isMember($request->user_id)) {
                abort(403, 'Cannot assign task to non-member');
            }
        }

        $task->update($request->all());
        return response()->json($task->load('user', 'comments'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if(!$task->project->isMember(request()->user()->id)){
            abort(403, 'No tienes permiso para ver esta tarea');
        };

        $task->delete();
        return response()->json(['message' => 'Tarea eliminada correctamente']);
    }
}
