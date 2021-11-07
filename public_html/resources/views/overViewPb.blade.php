@extends('layouts.layout')
@section('header')
    @include('header')
@stop
@section('body')

<H3  class="custom_header">Private Banking</h3>

<div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="p-6">
                <div class="flex items-center">
                    <i class="fas fa-people-arrows"></i>
                    <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ url('downloadPb') }}" class="underline text-gray-900 dark:text-white">Vertriebsuntersützung</a></div>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center">
                    <i class="fas fa-user-friends"></i>
                    <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ url('downloadPb') }}" class="underline text-gray-900 dark:text-white">Planungsworkshop</a></div>
                </div>
            </div>
        

        </div>
    </div>

@stop
@section('footer')
    @include('footer')
@stop