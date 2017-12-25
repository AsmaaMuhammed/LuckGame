
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
    }
});
function incTimer() {
    var currentMinutes = Math.floor(totalSecs / 60);
    var currentSeconds = totalSecs % 60;
    if(currentSeconds <= 9) currentSeconds = "0" + currentSeconds;
    if(currentMinutes <= 9) currentMinutes = "0" + currentMinutes;
    totalSecs++;
    $("#timer").text(currentMinutes + ":" + currentSeconds);

    // //check if the attempt is third attempt or not
    // var counter=$('#counter').val();
    // var division=3;
    // counter = parseInt(counter);
    // if(counter==1)
    //     division=3;
    // else if(counter==1)
    //     division=4;
    // else if(counter==1)
    //     division=5;

    setTimeout('incTimer()', 1000/division);
}

totalSecs = 0;
division=1;

$(document).ready(function() {
    $("#start").click(function() {
        var url = $(this).attr('route');
        var user_id=$('#user_id').val();
        var user_token=$('#token').val();
        $.ajax({
            type:'POST',
            url: url,
            data:{user_id:user_id , token:user_token},
            error: function (request, error) {
                console.log(arguments);
                alert(" Can't do because: " + error);
            },
            success: function (data) {
                if(data != false) {
                    incTimer();
                    $('#start').css('display', 'none');
                    $('#new_attempt').css('display', 'block');
                    $('#game_id').val(data['game_id']);
                    $('#b').text(data['b']);
                }
            }
        });
    });
    function storeAttemptsData(counter,timer_value) {

        var attempts_nos = $('#attempts_nos').val(); //retrieve array
        var attempts_nos_array = [];
        attempts_nos_array = JSON.parse(attempts_nos);
        //alert(attempts_nos_array);
        attempts_nos_array.push(counter);
        $('#attempts_nos').val(JSON.stringify(attempts_nos_array));
    }
    $("#new_attempt").click(function() {
        //all attempts counter
        var counter=$('#counter').val();
        counter = parseInt(counter) + 1;
        $('#counter').val(counter);

        var timer_value=$("#timer").text();
        var b=$('#b').text();

        storeAttemptsData(counter,timer_value);

        if( b == timer_value)
        {
            var success_counter=$('#success_attempt_counter').val();
            success_counter = parseInt(success_counter) + 1;
            if(success_counter == 3)
            {
                endTheGame();
            }
            else
                $('#success_attempt_counter').val(success_counter);
        }
        else {
            $('#success_attempt_counter').val(0);
        }

        totalSecs=0;
        incTimer();

    });
    $("#states").click(function() {
        $('#states_data').css('display','block')
    });
    function endTheGame() {
        var url = $('#new_attempt').attr('route');
        var user_id=$('#user_id').val();
        var user_token=$('#token').val();
        $.ajax({
            type:'PUT',
            url: url,
            data:{user_id:user_id , token:user_token},
            error: function (request, error) {
                console.log(arguments);
                alert(" Can't do because: " + error);
            },
            success: function (data) {
                if(data != false) {
                    alert('bravo!');
                    $('#start').css('display', 'block');
                    $('#new_attempt').css('display', 'none');
                }
            }
        });
    }
});
