<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Task::class);
        return TaskResource::collection(auth()->user()->tasks()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {

        if(request()->user()->cannot('create', Task::class)){
            abort(403, 'This action is unthorized.');
        }
        $task = $request->user()->tasks()->create($request->validated());
        return TaskResource::make($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        Gate::authorize('view', $task);
        return TaskResource::make($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {

        if($request->user()->cannot('update', $task)){
            abort(403, 'This action is unthorized.');
        }

        $task->update($request->validated());
        return TaskResource::make($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        
        if(request()->user()->cannot('delete', $task)){
            abort(403, 'This action is unthorized.');
        }
        $task->delete();

        return response()->noContent();
    }
}