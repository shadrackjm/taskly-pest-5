<x-layout title="New task">
    <h1>New task</h1>

    <form method="POST" action="{{ route('tasks.store') }}">
        @csrf

        <label for="title">Title</label>
        <input id="title" name="title" type="text" value="{{ old('title') }}">
        @error('title') <p class="error">{{ $message }}</p> @enderror

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
        @error('description') <p class="error">{{ $message }}</p> @enderror

        <label for="priority">Priority</label>
        <select id="priority" name="priority">
            @foreach ($priorities as $priority)
                <option value="{{ $priority->value }}" @selected(old('priority') === $priority->value)>
                    {{ $priority->label() }}
                </option>
            @endforeach
        </select>
        @error('priority') <p class="error">{{ $message }}</p> @enderror

        <label for="due_date">Due date</label>
        <input id="due_date" name="due_date" type="date" value="{{ old('due_date') }}">
        @error('due_date') <p class="error">{{ $message }}</p> @enderror

        <button type="submit">Create task</button>
    </form>
</x-layout>
