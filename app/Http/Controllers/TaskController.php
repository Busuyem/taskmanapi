<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();

        return response()->json([
            'status_code' => 200,
            'message' => "Success!",
            'data' => TaskResource::Collection($tasks)
        ]);
    }

    public function store(TaskRequest $request)
    {
        $validatedData = $request->validated();

        $task = Task::create($validatedData);

        return response()->json([
            'status_code' => 201,
            'message' => "Task created successfully!",
            'data' => new TaskResource($task)
        ], 201);
    }

    public function show($task)
    {
        $task = Task::find($task);

        if (is_null($task)) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json([
            'status_code' => 200,
            'message' => "Success!",
            'data' => new TaskResource($task)
        ]);
    }

    public function update(TaskRequest $request, $task)
    {
        $validatedData = $request->validated();

        $task = Task::find($task);

        if (is_null($task)) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->update($validatedData);

        return response()->json([
            'status_code' => 200,
            'message' => "Task updated successfully!",
            'data' => new TaskResource($task)
        ]);
    }

    public function destroy($task)
    {
        $task = Task::find($task);

        if (is_null($task)) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->delete();

        return response()->json([
            'status_code' => 200,
            'message' => "Task deleted Successfully!"
        ]);
    }
}
