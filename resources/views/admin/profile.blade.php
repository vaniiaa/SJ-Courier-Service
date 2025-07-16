@extends('layouts.admin')
@section('title', 'Admin Profile')

@section('content')
    {{-- Cukup panggil konten profil yang sama --}}
    <div class="absolute top-32 left-0 right-0 px-4 pt-20">
        <div class="max-w-[90rem] h-[20rem] mx-auto flex flex-col md:flex-row items-center sm:px-6 lg:px-8">
    @include('profile.partials.update-profile-information-form')
    </div>
    </div>
@endsection