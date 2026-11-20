<x-mail::message>
# Reminder

Your task **{{ $task->title }}** is marked {{ $task->priority->label() }} priority.

@if ($task->due_date)
It is due on {{ $task->due_date->toFormattedDateString() }}.
@endif

<x-mail::button :url="route('tasks.show', $task)">
View task
</x-mail::button>

Thanks,<br>
Taskly
</x-mail::message>
