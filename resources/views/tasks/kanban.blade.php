@extends('layouts.app')

@section('title', 'Task Kanban')

@section('content')

<div
    class="p-8"
    x-data="kanbanBoard()"
>

{{-- HEADER --}}
<div class="mb-8 flex items-center justify-between">

    <div>
        <p class="text-sm text-slate-500">
            Productivity
        </p>

        <h1 class="mt-1 text-3xl font-bold text-slate-900">
            Task Board
        </h1>

        <p class="mt-2 text-slate-500">
            Visualize your tasks and track your progress.
        </p>
    </div>

    <div class="flex items-center gap-3">

        <a
            href="{{ route('tasks.index') }}"
            class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50"
        >
            List View
        </a>

        <a
            href="{{ route('tasks.create') }}"
            class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800"
        >
            + New Task
        </a>

    </div>

</div>


{{-- SUCCESS --}}
@if(session('success'))

    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        {{ session('success') }}
    </div>

@endif


{{-- KANBAN --}}
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    @foreach($columns as $status => $label)

        @php
            $columnTasks = $tasks->where('status', $status);
        @endphp

        {{-- COLUMN --}}
        <div
            class="min-h-[300px] rounded-2xl border border-slate-200 bg-slate-100/70 p-4 transition"
            :class="dragOver === '{{ $status }}' ? 'border-indigo-400 bg-indigo-50/50' : ''"
            @dragover.prevent
            @dragenter.prevent="dragOver = '{{ $status }}'"
            @dragleave="dragOver = null"
            @drop.prevent="dropTask('{{ $status }}', $event)"
        >

            {{-- COLUMN HEADER --}}
            <div class="mb-4 flex items-center justify-between">

                <div class="flex items-center gap-2">

                    <h2 class="font-semibold text-slate-800">
                        {{ $label }}
                    </h2>

                    <span class="rounded-full bg-white px-2.5 py-1 text-xs font-medium text-slate-500">
                        {{ $columnTasks->count() }}
                    </span>

                </div>

            </div>


            {{-- TASKS --}}
            <div class="space-y-3">

                @forelse($columnTasks as $task)

                    {{-- TASK CARD --}}
                    <div
                        draggable="true"
                        @dragstart="dragTask({{ $task->id }}, $event)"
                        @dragend="dragOver = null; draggedTask = null"
                        class="cursor-grab rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md active:cursor-grabbing"
                    >

                        {{-- TASK TITLE --}}
                        <a
                            href="{{ route('tasks.edit', $task) }}"
                            class="block font-medium text-slate-900 hover:text-indigo-600"
                        >
                            {{ $task->title }}
                        </a>


                        {{-- DESCRIPTION --}}
                        @if($task->description)

                            <p class="mt-2 text-sm leading-relaxed text-slate-400">
                                {{ Str::limit($task->description, 70) }}
                            </p>

                        @endif


                        {{-- PROJECT --}}
                        @if($task->project)

                            <a
                                href="{{ route('projects.show', $task->project) }}"
                                class="mt-3 inline-flex rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-600 hover:bg-indigo-100"
                            >
                                {{ $task->project->name }}
                            </a>

                        @endif


                        {{-- META --}}
                        <div class="mt-4 flex items-center justify-between">

                            {{-- PRIORITY --}}
                            <span
                                class="rounded-full px-2.5 py-1 text-xs font-medium
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


                            {{-- DUE DATE --}}
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

                        </div>


                        {{-- STATUS --}}
                        <div class="mt-4 border-t border-slate-100 pt-3">

                            <form
                                action="{{ route('tasks.update-status', $task) }}"
                                method="POST"
                            >

                                @csrf
                                @method('PATCH')

                                <select
                                    name="status"
                                    onchange="this.form.submit()"
                                    @mousedown.stop
                                    @click.stop
                                    class="w-full rounded-lg border-slate-200 bg-slate-50 text-xs font-medium text-slate-600 focus:border-slate-300 focus:ring-slate-300"
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

                        </div>

                    </div>

                @empty

                    <div
                        class="rounded-xl border border-dashed border-slate-300 bg-white/50 px-4 py-10 text-center"
                    >
                        <p class="text-sm text-slate-400">
                            No tasks
                        </p>
                    </div>

                @endforelse

            </div>

        </div>

    @endforeach

</div>


</div>

@endsection

@push('scripts')

<script>

    function kanbanBoard() {

        return {

            draggedTask: null,

            dragOver: null,


            dragTask(taskId, event) {

                this.draggedTask = taskId;

                event.dataTransfer.effectAllowed = 'move';

                event.dataTransfer.setData(
                    'text/plain',
                    taskId
                );

            },


           async dropTask(status, event) {

                this.dragOver = null;

                let taskId = this.draggedTask;


                /*
                 * Ambil task ID dari dataTransfer
                 * sebagai fallback jika Alpine state hilang.
                 */
                if (!taskId) {

                    const data = event.dataTransfer.getData(
                        'text/plain'
                    );

                    if (data) {
                        taskId = data;
                    }

                }


                if (!taskId) {

                    console.error(
                        'Task ID tidak ditemukan.'
                    );

                    return;

                }


                this.draggedTask = null;


                try {

                    const response = await fetch(
                        `/tasks/${taskId}/status`,
                        {
                            method: 'PATCH',

                            headers: {

                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        .getAttribute(
                                            'content'
                                        )

                            },

                            body: JSON.stringify({
                                status: status
                            })

                        }
                    );


                    if (!response.ok) {

                        const errorText =
                            await response.text();

                        console.error(
                            'Server response:',
                            errorText
                        );

                        throw new Error(
                            `HTTP ${response.status}`
                        );

                    }


                    const data =
                        await response.json();

                    console.log(
                        'Task berhasil dipindahkan:',
                        data
                    );


                    window.location.reload();

                } catch (error) {

                    console.error(
                        'Drag & Drop Error:',
                        error
                    );

                    alert(
                        'Gagal memperbarui status task.'
                    );

                }

            }

        }

    }

</script>

@endpush
