@extends('layouts.layout')
@section('header')
    @include('header')
@stop
@section('body')

<H3 class="custom_header">Downloadbereich | Vertriebsbetreuer</h3>

<a class="text-sm text-gray-700 dark:text-gray-500 underline" href="{{ url('overViewVb') }}">Zurück</a>

<div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="p-6">
                                
                <form action="{{url('downloadVbDocuments')}}" method="post">

                @csrf

                <div class="dropdown">


                    <div id="dokumentenAuswahl" class="content" style="display: block;">
                    <p>Bitte wählen Sie die gewünschte(n) Dokumente(n)</p>
                    <input type="checkbox" name="documentSelect[]" value="mappenlager/Vertriebsfolien/Guidebook.pdf"> Guidebook<br>
                    <input type="checkbox" name="documentSelect[]" value="mappenlager/Vertriebsfolien/Beispielfolien_Guidebook.pptx"> Guidebook Beispielfolien <br>

                    <br> <br>
                    <input type="submit" value="Download">

                    </div>




                </form>
            </div>
        
            </div>

        </div>
    </div>

@stop
@section('footer')
    @include('footer')
@stop