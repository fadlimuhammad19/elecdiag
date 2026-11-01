@extends('layouts.app')
@section('title', 'Edit Komponen')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Komponen</h1>

    <form action="{{ route('admin.komponen.update', $komponen) }}" method="POST" class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 max-w-xl">
        @csrf
        @method('PUT')
        @include('admin.komponen._form')
    </form>
@endsection