
@extends('layouts.layout')
@section('header')
    @include('header')
@stop
@section('body')

<H3 class="custom_header">Downloadbereich | Private Banking</h3>

<a class="text-sm text-gray-700 dark:text-gray-500 underline" href="{{ url('overViewVb') }}">Zurück</a>

<div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="p-6">
                  
                    <form action="{{url('downloadVdDocuments')}}" method="post">

                    @csrf
                    <p><b>Bitte wählen Sie die gewünschte(n) Sparkasse(n)</b></p>
                    <div class="dropdown">

                        <div id="spkAuswahl" class="content">
                        <input type="checkbox" name="spkSelect[]" value="10050000"> 10050000 Berliner Sparkasse <br>
                        <input type="checkbox" name="spkSelect[]" value="13050000"> 13050000 OstseeSparkasse Rostock <br>
                        <input type="checkbox" name="spkSelect[]" value="14051000"> 14051000 Sparkasse Mecklenburg-Nordwest <br>
                        <input type="checkbox" name="spkSelect[]" value="14051362"> 14051362 Sparkasse Parchim-Lübz <br>
                        <input type="checkbox" name="spkSelect[]" value="14052000"> 14052000 Sparkasse Mecklenburg-Schwerin <br>
                        <input type="checkbox" name="spkSelect[]" value="15050100"> 15050100 Müritz-Sparkasse <br>
                        <input type="checkbox" name="spkSelect[]" value="15050200"> 15050200 Sparkasse Neubrandenburg-Demmin <br>
                        <input type="checkbox" name="spkSelect[]" value="15050400"> 15050400 Sparkasse Uecker-Randow <br>
                        <input type="checkbox" name="spkSelect[]" value="15050500"> 15050500 Sparkasse Vorpommern <br>
                        <input type="checkbox" name="spkSelect[]" value="10050000"> 10050000 Sparkasse Mecklenburg-Strelitz <br>
                        <br> <br>


                        </div>
                        
                        <div id="dokumentenAuswahl" class="content" style="display: block;">
                        <p><b>Bitte wählen Sie die gewünschte(n) Dokumente(n)</b></p>
                        <input type="checkbox" name="documentSelect[]" value="mappenlager/Vertriebsunterstützung/DVK Foliensatz/"> DVK Foliensatz <br>
                        <input type="checkbox" name="documentSelect[]" value="mappenlager/Vertriebsunterstützung/SDD Foliensatz/"> SDD Foliensatz <br>
                        <input type="checkbox" name="documentSelect[]" value="mappenlager/Vertriebsunterstützung/DekaStruktur_Auswertung/"> DekaStruktur - Auswertung <br>
                        <input type="checkbox" name="documentSelect[]" value="mappenlager/Vertriebsunterstützung/AGB Änderungsmechanismus FVV_Foliensatz/"> AGB Änderungsmechanismus FVV <br>
                        <input type="checkbox" name="documentSelect[]" value="mappenlager/Vertriebsunterstützung/AGB Änderungsmechanismus FVV_Rückmeldungen/"> AGB Änderungsmechanismus FVV_Rückmeldungen <br>
                        

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



