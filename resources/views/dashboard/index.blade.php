@extends('layouts.app')
@section('title', 'Dashboard')
@php
    $auth = Auth::user();
@endphp

@push('title-header')
    Dashboard
@endpush
