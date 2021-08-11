<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = auth()->user()->statuses()->with('tasks')->get();
        //dd($tasks);

        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:1000'],
            'status_id' => ['required', 'exists:statuses,id']
        ]);

        return $request->user()
            ->tasks()
            ->create($request->only('title', 'description', 'status_id'));
    }

    public function update(Request $request, $taskId)
    {
        $this->validate($request, [
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:1000'],
            'status_id' => ['required', 'exists:statuses,id']
        ]);

        $request->user()
            ->tasks()
            ->where('id', $taskId)
            ->update($request->only('title', 'description', 'status_id'));
        return Task::where('id', $taskId)->first();
    }

    public function sync(Request $request)
    {
        $this->validate(request(), [
            'columns' => ['required', 'array']
        ]);

        foreach ($request->columns as $status) {
            foreach ($status['tasks'] as $i => $task) {
                $order = $i + 1;
                if (($task['status_id'] !== $status['id']) || ($task['order'] !== $order)) {
                    request()->user()->tasks()->find($task['id'])->update(['status_id' => $status['id'], 'order' => $order]);
                }
            }
        }

        return $request->user()->statuses()->with('tasks')->get();
    }
}
