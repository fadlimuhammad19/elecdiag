@extends('layouts.app')
@section('title', 'Tambah Gejala')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Tambah Gejala</h1>

    <form action="{{ route('admin.gejala.store') }}" method="POST" class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 max-w-xl">
        @csrf
        @include('admin.gejala._form')
    </form>
@endsection