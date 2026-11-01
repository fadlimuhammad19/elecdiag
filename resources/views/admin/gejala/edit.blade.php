@extends('layouts.app')
@section('title', 'Edit Gejala')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Gejala</h1>

    <form action="{{ route('admin.gejala.update', $gejala) }}" method="POST" class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 max-w-xl">
        @csrf
        @method('PUT')
        @include('admin.gejala._form')
    </form>
@endsection