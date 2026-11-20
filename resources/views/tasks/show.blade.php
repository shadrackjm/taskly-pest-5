<x-layout title="{{ $task->title }}">
    <h1>{{ $task->title }}</h1>

    <p><strong>Priority:</strong> {{ $task->priority->label() }}</p>
    <p><strong>Due:</strong> {{ $task->due_date?->toFormattedDateString() ?? 'No due date' }}</p>
    <p><strong>Status:</strong> {{ $task->isComplete() ? 'Complete' : 'Pending' }}</p>

    @if ($task->description)
        <p>{{ $task->description }}</p>
    @endif

    <form method="POST" action="{{ route('tasks.destroy', $task) }}">
        @csrf
        @method('DELETE')
        <button type="submit">Delete task</button>
    </form>

    <p><a href="{{ route('tasks.index') }}">Back to tasks</a></p>
</x-layout>
