@extends('layouts.app')

@section('title', 'Tasks')

@section('content')

<div class="p-8">

{{-- HEADER --}}
<div class="mb-8 flex items-center justify-between">

    <div>
        <p class="text-sm text-slate-500">
            Productivity
        </p>

        <h1 class="mt-1 text-3xl font-bold text-slate-900">
            Tasks
        </h1>

        <p class="mt-2 text-slate-500">
            Manage everything you need to get done.
        </p>
    </div>

    <a
        href="{{ route('tasks.create') }}"
        class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800"
    >
        + New Task
    </a>

</div>


{{-- SUCCESS --}}
@if(session('success'))

    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        {{ session('success') }}
    </div>

@endif


{{-- FILTER --}}
<div class="mb-5 flex flex-wrap items-center gap-2">

    <a
        href="{{ route('tasks.index') }}"
        class="rounded-lg px-4 py-2 text-sm font-medium
            {{ !request('status')
                ? 'bg-slate-900 text-white'
                : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'
            }}"
    >
        All
    </a>

    <a
        href="{{ route('tasks.index', ['status' => 'todo']) }}"
        class="rounded-lg px-4 py-2 text-sm font-medium
            {{ request('status') === 'todo'
                ? 'bg-slate-900 text-white'
                : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'
            }}"
    >
        Todo
    </a>

    <a
        href="{{ route('tasks.index', ['status' => 'in_progress']) }}"
        class="rounded-lg px-4 py-2 text-sm font-medium
            {{ request('status') === 'in_progress'
                ? 'bg-slate-900 text-white'
                : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'
            }}"
    >
        In Progress
    </a>

    <a
        href="{{ route('tasks.index', ['status' => 'completed']) }}"
        class="rounded-lg px-4 py-2 text-sm font-medium
            {{ request('status') === 'completed'
                ? 'bg-slate-900 text-white'
                : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'
            }}"
    >
        Completed
    </a>

</div>


{{-- TASK LIST --}}
<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">

    @forelse($tasks as $task)

        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5 last:border-b-0">

            {{-- LEFT --}}
            <div class="flex min-w-0 items-center gap-4">

                {{-- STATUS BUTTON --}}
                <form
                    action="{{ route('tasks.update-status', $task) }}"
                    method="POST"
                >

                    @csrf
                    @method('PATCH')

                    <select
                        name="status"
                        onchange="this.form.submit()"
                        class="h-8 rounded-full border-0 bg-slate-100 px-3 text-xs font-medium text-slate-600 focus:ring-2 focus:ring-slate-300"
                    >

                        <option
                            value="todo"
                            @selected($task->status === 'todo')
                        >
                            Todo
                        </option>

                        <option
                            value="in_progress"
                            @selected($task->status === 'in_progress')
                        >
                            In Progress
                        </option>

                        <option
                            value="completed"
                            @selected($task->status === 'completed')
                        >
                            Completed
                        </option>

                    </select>

                </form>


                {{-- TASK INFO --}}
                <div class="min-w-0">

                    <h3
                        class="font-medium
                            {{ $task->status === 'completed'
                                ? 'text-slate-400 line-through'
                                : 'text-slate-900'
                            }}"
                    >
                        {{ $task->title }}
                    </h3>

                    @if($task->description)

                        <p class="mt-1 text-sm text-slate-400">
                            {{ Str::limit($task->description, 80) }}
                        </p>

                    @endif

                    {{-- PROJECT --}}
                    @if($task->project)

                        <div class="mt-2">

                            <a
                                href="{{ route('projects.show', $task->project) }}"
                                class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-600 hover:bg-indigo-100"
                            >
                                {{ $task->project->name }}
                            </a>

                        </div>

                    @endif

                </div>

            </div>


            {{-- RIGHT --}}
            <div class="ml-6 flex shrink-0 items-center gap-4">

                {{-- PRIORITY --}}
                <span
                    class="rounded-full px-3 py-1 text-xs font-medium
                        @if($task->priority === 'high')
                            bg-red-50 text-red-600
                        @elseif($task->priority === 'medium')
                            bg-yellow-50 text-yellow-600
                        @else
                            bg-green-50 text-green-600
                        @endif
                    "
                >
                    {{ ucfirst($task->priority) }}
                </span>


                {{-- DATE --}}
                @if($task->due_date)

                    <span
                        class="text-xs
                            {{ $task->due_date->isPast() && $task->status !== 'completed'
                                ? 'font-medium text-red-500'
                                : 'text-slate-400'
                            }}"
                    >
                        {{ $task->due_date->format('d M Y') }}
                    </span>

                @endif


                {{-- EDIT --}}
                <a
                    href="{{ route('tasks.edit', $task) }}"
                    class="text-sm font-medium text-slate-500 hover:text-slate-900"
                >
                    Edit
                </a>


                {{-- DELETE --}}
                <form
                    method="POST"
                    action="{{ route('tasks.destroy', $task) }}"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        onclick="return confirm('Hapus task ini?')"
                        class="text-sm font-medium text-red-500 hover:text-red-700"
                    >
                        Delete
                    </button>

                </form>

            </div>

        </div>

    @empty

        <div class="px-6 py-20 text-center">

            <div class="text-4xl">
                ✓
            </div>

            <h3 class="mt-4 font-semibold text-slate-900">
                No tasks found
            </h3>

            <p class="mt-1 text-sm text-slate-400">
                @if(request('status'))
                    Tidak ada task dengan status ini.
                @else
                    Create your first task to get started.
                @endif
            </p>

            @if(!request('status'))

                <a
                    href="{{ route('tasks.create') }}"
                    class="mt-6 inline-block rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white"
                >
                    Create Task
                </a>

            @endif

        </div>

    @endforelse

</div>

</div>

@endsection
