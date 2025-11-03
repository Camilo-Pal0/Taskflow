<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    public function index(Project $project){
        if(!$project->isMember(request()->user()->id)){
            abort(403);
        }

        return response()->json($project->members);
    }

    public function store(Request $request, Project $project){
        if($project->user_id !== $request->user()->id){
            abort(403, 'Solo el dueño puede agregar miembros');
        }

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'in:owner,member'
        ]);

        $user = User::where('email', $request->email)->first();

        if($project->isMember($user->id)){
            return response()->json(['message' => 'El usuario ya es miembro'], 400);
        }

        $project->members()->attach($user->id, ['role' => $request->role ?? 'member']);

        return response()->json([
            'message' => 'Miembro agregado exitosamente',
            'member' => $user
        ], 201);
    }

    public function destroy(Project $project, User $user)
    {
        // Solo owners pueden remover miembros
        if (!$project->isOwner(request()->user()->id)) {
            abort(403, 'Only project owners can remove members');
        }

        // Verificar que no sea el último owner
        $ownersCount = $project->members()->wherePivot('role', 'owner')->count();
        $isOwner = $project->members()->where('user_id', $user->id)->wherePivot('role', 'owner')->exists();
        
        if ($isOwner && $ownersCount <= 1) {
            return response()->json(['message' => 'Cannot remove the last owner'], 400);
        }

        $project->members()->detach($user->id);

        return response()->json(['message' => 'Member removed successfully']);
    }

    public function leave(Project $project)
    {
        $userId = request()->user()->id;
        
        if (!$project->isMember($userId)) {
            abort(403, 'You are not a member of this project');
        }

        $isOwner = $project->members()->where('user_id', $userId)->wherePivot('role', 'owner')->exists();
        $ownersCount = $project->members()->wherePivot('role', 'owner')->count();

        // Si es owner y es el único, transferir ownership antes de salir
        if ($isOwner && $ownersCount === 1) {
            // Buscar otro miembro para hacerlo owner
            $nextOwner = $project->members()
                ->where('user_id', '!=', $userId)
                ->first();
            
            if ($nextOwner) {
                // Hacer al siguiente miembro owner en la tabla pivot
                $project->members()->updateExistingPivot($nextOwner->id, ['role' => 'owner']);
                
                // CRÍTICO: Actualizar el user_id en la tabla projects
                $project->update(['user_id' => $nextOwner->id]);
            } else {
                // Si no hay más miembros, eliminar el proyecto
                $project->delete();
                return response()->json(['message' => 'Project deleted as you were the last member']);
            }
        }

        // Si eres el creador original (user_id) pero hay otro owner
        if ($project->user_id === $userId && $ownersCount > 1) {
            // Transferir a otro owner
            $nextOwner = $project->members()
                ->where('user_id', '!=', $userId)
                ->wherePivot('role', 'owner')
                ->first();
            
            if ($nextOwner) {
                $project->update(['user_id' => $nextOwner->id]);
            }
        }

        $project->members()->detach($userId);
        return response()->json(['message' => 'Left project successfully']);
    }
}
