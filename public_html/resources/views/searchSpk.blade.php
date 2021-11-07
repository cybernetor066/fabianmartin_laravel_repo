
<form action="{{url('serachSpkStammdaten')}}" method="post">
@csrf

    <div id="spkSuche" class="content">
    <p>Welche Sparkasse(n) wollen Sie suchen?</p>
        <label for="fname">Spk-Name oder Bankleitzahl:</label><br>
        <input type="text" id="searchParameter" name="searchParameter"><br><br>
        <input type="submit" value="Search">
  
    </div>

</form>




