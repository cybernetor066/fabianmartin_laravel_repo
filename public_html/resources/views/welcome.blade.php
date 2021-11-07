@extends('layouts.layout')
@section('header')
    @include('header')
@stop
@section('body')
    <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="p-6">
                <div class="flex items-center">
                    <i class="fas fa-university"></i>
                    <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ url('overViewVd') }}" class="underline text-gray-900 dark:text-white">Vertriebsdirektor</a></div>
                </div>

                <div class="ml-12">
                    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                    "Ansicht der Nutzergruppe: VD"
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="flex items-center">
                    <i class="fas fa-university"></i>
                    <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ url('overViewVb') }}" class="underline text-gray-900 dark:text-white">Vertriebsbetreuer</a></div>
                </div>

                <div class="ml-12">
                    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                    "Ansicht der Nutzergruppe: VB"
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="flex items-center">
                    <i class="fas fa-university"></i>
                    <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ url('overViewPb') }}" class="underline text-gray-900 dark:text-white">Private Banking</a></div>
                </div>

                <div class="ml-12">
                    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                    "Ansicht der Nutzergruppe: PB"
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="flex items-center">
                    <i class="fas fa-stream"></i>
                    <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ url('contentManagement') }}" class="underline text-gray-900 dark:text-white">Content Management</a></div>
                </div>

                <div class="ml-12">
                    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                    "Ansicht der Nutzergruppe: Content Management"
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="flex items-center">
                    <i class="fas fa-truck"></i>
                    <div class="ml-4 text-lg leading-7 font-semibold"><a href="{{ url('overViewOe') }}" class="underline text-gray-900 dark:text-white">Ansicht Zulieferer</a></div>
                </div>

                <div class="ml-12">
                    <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                    "Ansicht der Nutzergruppe: Content Management"
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop
@section('footer')
    @include('footer')
@stop