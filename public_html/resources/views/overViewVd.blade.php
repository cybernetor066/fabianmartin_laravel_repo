@extends('layouts.layout')
@section('header')
    @include('header')
@stop
@section('body')

<H3  class="custom_header">Vertriebsdirektor</h3>

<div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="p-6">
                <div class="flex items-center">
                    <i class="fas fa-people-arrows"></i>
                    <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ url('downloadVd') }}" class="underline text-gray-900 dark:text-white">Vertriebsuntersützung</a></div>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center">
                    <i class="fas fa-user-friends"></i>
                    <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ url('downloadVd') }}" class="underline text-gray-900 dark:text-white">Planungsworkshop</a></div>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center">
                    <i class="fas fa-user-clock"></i>
                    <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ url('downloadVd') }}" class="underline text-gray-900 dark:text-white">Planungsgespräch</a></div>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center">
                    <i class="fas fa-users"></i>
                    <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ url('downloadVd') }}" class="underline text-gray-900 dark:text-white">Vertriebsgespräch</a></div>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center">
                    <i class="fas fa-chart-line"></i>
                    <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ url('downloadVd') }}" class="underline text-gray-900 dark:text-white">Investmentprozessgespräch</a></div>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center">
                    <i class="fas fa-paste"></i>
                    <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ url('downloadVb') }}" class="underline text-gray-900 dark:text-white">Individuelle Vertriebsfolien</a></div>
                </div>
            </div>
        

        </div>
    </div>

@stop
@section('footer')
    @include('footer')
@stop