<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Task $task)
    {
        if (!$task->project->isMember(request()->user()->id)) {
            abort(403);
        }

        return response()->json($task->comments()->with('user')->latest()->get());
    }

    public function store(Request $request, Task $task)
    {
        if (!$task->project->isMember($request->user()->id)) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment = $task->comments()->create([
            'content' => $request->content,
            'user_id' => $request->user()->id
        ]);

        // Notificar al asignado de la tarea (si no es quien comenta)
        if ($task->user_id !== $request->user()->id) {
            \App\Models\Notification::create([
                'user_id' => $task->user_id,
                'type' => 'comment_added',
                'title' => 'Tienes un nuevo comentario en tu tarea',
                'message' => $request->user()->name . ' comentó en: ' . $task->title,
                'data' => [
                    'task_id' => $task->id,
                    'project_id' => $task->project_id,
                    'comment_id' => $comment->id
                ]
            ]);
        }

        return response()->json($comment->load('user'), 201);
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== request()->user()->id) {
            abort(403, 'You can only delete your own comments');
        }

        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully']);
    }
}