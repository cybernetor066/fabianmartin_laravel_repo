@extends('layouts.layout')
@section('header')
    @include('header')
@stop
@section('body')

<H3 class="custom_header">Downloadbereich | Vertriebsdirektor</h3>

    <a class="text-sm text-gray-700 dark:text-gray-500 underline" href="{{ url('overViewVd') }}">Zurück</a>

    <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-1">
                <div class="p-6">
                    

                    <form action="{{url('serachSpkStammdaten')}}" method="post">
                    @csrf

                    <div id="spkSuche" class="content">
                    <p><b>Welche Sparkasse(n) wollen Sie suchen?</b></p>
                        <label for="fname">Spk-Name oder Bankleitzahl:</label><br>
                        <input type="text" id="searchParameter" name="searchParameter"><br><br>
                        <input type="submit" value="Suchen">
                    
                    </div>

                    </form>

                    <form action="{{url('downloadVdDocuments')}}" method="post">

                    @csrf

                    <br>
                    <p><b>Bitte wählen Sie die gewünschte(n) Sparkasse(n)</b></p>
                    <div class="dropdown">

                        <div id="spkAuswahl" class="content">
                        <br>
                        
                        @foreach ($spk as $spkInstance)

                            <input type="checkbox" name="spkSelect[]" value=" {{ $spkInstance->spkblz }}">  {{ $spkInstance->spkblz }}  {{ $spkInstance->spkname }} <br>
                            
                        @endforeach
                    
                        <br> <br>

                        </div>
                        
                        <div id="dokumentenAuswahl" class="content" style="display: block;">
                        <p><b>Bitte wählen Sie die gewünschte(n) Dokumente(n)</b></p>
                        <input type="checkbox" name="documentSelect[]" value="mappenlager/Vertriebsunterstützung/DVK Foliensatz/"> DVK Foliensatz <br>
                        <input type="checkbox" name="documentSelect[]" value="mappenlager/Vertriebsunterstützung/SDD Foliensatz/"> SDD Foliensatz <br>
                        <input type="checkbox" name="documentSelect[]" value="mappenlager/Vertriebsunterstützung/DekaStruktur_Auswertung/"> DekaStruktur - Auswertung <br>
                        <input type="checkbox" name="documentSelect[]" value="mappenlager/Vertriebsunterstützung/AGB Änderungsmechanismus FVV_Foliensatz/"> AGB Änderungsmechanismus FVV <br>
                        <input type="checkbox" name="documentSelect[]" value="mappenlager/Vertriebsunterstützung/AGB Änderungsmechanismus FVV_Rückmeldungen/"> AGB Änderungsmechanismus FVV_Rückmeldungen <br>
                        <input type="checkbox" name="documentSelect[]" value="mappenlager/Vertriebsunterstützung/Planungsworkshop_Maßnahmen/"> Planungsworkshop Maßnahmen <br>
                        <input type="checkbox" name="documentSelect[]" value="mappenlager/Vertriebsunterstützung/VEV Musteranschreiben/"> VEV 2020 Musteranschreiben <br>
                        <input type="checkbox" name="documentSelect[]" value="mappenlager/Vertriebsunterstützung/VEV Report_Avis/"> VEV 2020 Report/Avis <br>

                        <br> <br>
                        <input type="submit" value="Herunterladen">

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
