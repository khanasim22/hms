@extends('layouts.app')
@section('title')
    {{ __('messages.complaint') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')

            <livewire:complaint-table />
            
            @include('complaints.add_modal')
            @include('complaints.edit_modal')
            @include('complaints.response_modal')
            @include('complaints.view_complaint_modal')
        </div>
    </div>
@endsection
