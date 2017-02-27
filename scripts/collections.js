$(function(){
    $("add_collection").on("submit", function() {
        $.post("collections.php", 
        function() {
            alert("Hello");
        }
        );
    })
});