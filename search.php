<?php
include_once("includes/header.php");

?>

<div class="textbox-container">
    <input type="text" class="search-input" placeholder="search TV Series or Movies">

</div>

<div class="search-results">

</div>

<script>
    $(function(){

        var username = '<?php echo $userLoggedIn; ?>';
        var timer;

        $(".search-input").keyup(function() {
            clearTimeout(timer);

            timer = setTimeout(function() {
                var val = $(".search-input").val();
                
                if(val != ""){
                    $.post("ajax/getSearchResults.php", {term: val, username: username }, function(data){
                        $(".search-results").html(data);
                    })
                }
                else{
                    $(".search-results").html("");
                }
            }, 500);
        })

    }) 
        
</script>