@extends('layouts.app')
@section('title', 'Tambah Rekomendasi')

@section('content')
    <form action="{{ route('admin.rekomendasi.store') }}" method="POST"
          class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 max-w-xl">
        @csrf
        @include('admin.rekomendasi._form')
    </form>
@endsection