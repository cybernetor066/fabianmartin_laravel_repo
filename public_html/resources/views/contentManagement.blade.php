@extends('layouts.layout')
@section('header')
    @include('header')
@stop
@section('body')

<H3  class="custom_header">Content Management</h3>


<div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="p-6">


                <form action="{{url('readPoolStructure')}}" method="post">

                @csrf

                <div class="dropdown">
                    <p>Anpassungen im SharePoint "Folienpool" mit der Webapplication synchronisieren</p>
                    <input type="submit" value="Sync">
                </div>
                </form>

                <form action="{{url('readPoolStructureCore')}}" method="post">

                @csrf

                <div class="dropdown">
                    <p>Anpassungen im SharePoint "Kernmappenlager" mit der Webapplication synchronisieren</p>
                    <input type="submit" value="Sync">
                </div>
                </form>
            </div>
        </div>
    </div>
@stop
@section('footer')
    @include('footer')
@stop
