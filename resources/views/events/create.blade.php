@extends('layouts.app')

@section('title', 'Create Event')

@section('content')

    <div class="p-8">

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900">
                Create Event
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Tambahkan jadwal baru ke kalender.
            </p>
        </div>


        <div class="max-w-2xl rounded-2xl border border-slate-200 bg-white p-6">

            <form action="{{ route('events.store') }}" method="POST" class="space-y-6">

                @csrf


                {{-- TITLE --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Title
                    </label>

                    <input type="text" name="title" value="{{ old('title') }}" required
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Contoh: Meeting dengan client">

                    @error('title')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- DESCRIPTION --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Description
                    </label>

                    <textarea name="description" rows="4"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Deskripsi event...">{{ old('description') }}</textarea>

                </div>


                {{-- START & END --}}
                <div class="grid gap-5 md:grid-cols-2">

                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Start
                        </label>

                        <input type="datetime-local" name="start_at"
                            value="{{ old('start_at', $date ? $date . 'T09:00' : '') }}" required
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5">

                        @error('start_at')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <div>

                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            End
                        </label>

                        <input type="datetime-local" name="end_at" value="{{ old('end_at') }}"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5">

                        @error('end_at')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>


                {{-- LOCATION --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Location
                    </label>

                    <input type="text" name="location" value="{{ old('location') }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5"
                        placeholder="Contoh: Google Meet / Kampus">

                </div>


                {{-- BUTTON --}}
                <div class="flex items-center gap-3">

                    <a href="{{ route('events.index') }}"
                        class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Cancel
                    </a>

                    <button type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
                        Create Event
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
