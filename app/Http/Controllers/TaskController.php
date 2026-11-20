<?php

namespace App\Http\Controllers;

use App\Enums\TaskPriority;
use App\Events\TaskCompleted;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Jobs\SendTaskReminder;
use App\Models\Task;
use App\Services\HolidayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'priority']);

        $tasks = $request->user()
            ->tasks()
            ->pending()
            ->filter($filters)
            ->orderBy('due_date')
            ->get();

        return view('tasks.index', [
            'tasks' => $tasks,
            'filters' => $filters,
            'priorities' => TaskPriority::cases(),
        ]);
    }

    public function create(): View
    {
        return view('tasks.create', ['priorities' => TaskPriority::cases()]);
    }

    public function store(StoreTaskRequest $request, HolidayService $holidays): RedirectResponse
    {
        $task = $request->user()->tasks()->create($request->validated());

        if ($task->priority->isUrgent()) {
            SendTaskReminder::dispatch($task);
        }

        if ($task->due_date && $holidays->isHoliday($task->due_date)) {
            session()->flash('warning', 'Heads up — that due date is a public holiday.');
        }

        return redirect()->route('tasks.index')->with('message', 'Task created.');
    }

    public function show(Task $task): View
    {
        $this->authorize('view', $task);

        return view('tasks.show', ['task' => $task]);
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        return redirect()->route('tasks.show', $task)->with('message', 'Task updated.');
    }

    public function complete(Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $task->complete();

        TaskCompleted::dispatch($task);

        return redirect()->route('tasks.index')->with('message', 'Task completed.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')->with('message', 'Task deleted.');
    }
}
