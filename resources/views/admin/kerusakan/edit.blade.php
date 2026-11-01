@extends('layouts.app')
@section('title', 'Edit Kerusakan')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Kerusakan</h1>

    <form action="{{ route('admin.kerusakan.update', $kerusakan) }}" method="POST" class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 max-w-xl">
        @csrf
        @method('PUT')
        @include('admin.kerusakan._form')
    </form>
@endsection