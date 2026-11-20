<x-layout title="Your tasks">
    <h1>Your tasks</h1>

    <p><a href="{{ route('tasks.create') }}">New task</a></p>

    <form method="GET" action="{{ route('tasks.index') }}">
        <label for="search">Search</label>
        <input id="search" name="search" type="search" value="{{ $filters['search'] ?? '' }}">

        <label for="priority">Priority</label>
        <select id="priority" name="priority">
            <option value="">Any priority</option>
            @foreach ($priorities as $priority)
                <option value="{{ $priority->value }}" @selected(($filters['priority'] ?? '') === $priority->value)>
                    {{ $priority->label() }}
                </option>
            @endforeach
        </select>

        <button type="submit">Filter</button>
    </form>

    @if ($tasks->isEmpty())
        @if (array_filter($filters))
            <p>No tasks match those filters.</p>
        @else
            <p>Nothing to do. Enjoy it while it lasts.</p>
        @endif
    @else
        <ul class="tasks">
            @foreach ($tasks as $task)
                <li>
                    <a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a>
                    <small>({{ $task->priority->label() }})</small>
                    @if ($task->isOverdue())
                        <span class="overdue">Overdue</span>
                    @endif

                    <form method="POST" action="{{ route('tasks.complete', $task) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit">Complete</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Sign out</button>
    </form>
</x-layout>
