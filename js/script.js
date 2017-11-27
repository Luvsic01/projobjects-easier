$(function () {

    // Add Update User
    $('#addUpdateForm').on('submit',function (e) {
        e.preventDefault();
        var data = $(this).serialize();
        console.log(data);
        $.ajax({
            url: "ajax/addUpdate.php",
            method: "POST",
            data: data,
            dataType: "json"
        })
            .done(function(data) {
                console.log(data);
                if (data.code === 1){
                    $('#alertPhp').html('<div class="alert alert-success" role="alert">' + data.msg + '</div>');
                }else{
                    $('#alertPhp').html('<div class="alert alert-danger" role="alert"></div>');
                    $.each(data.msg, function (key, value) {
                        $('#alertPhp').find('div').append(value + '<br>');
                    });
                }
            });
    });

    // Add Update User
    $('#sessionForm').on('submit',function (e) {
        e.preventDefault();
        var data = $(this).serialize();
        console.log(data);
        $.ajax({
            url: "ajax/session.php",
            method: "POST",
            data: data,
            dataType: "json"
        })
            .done(function(data) {
                console.log(data);
                if (data.code === 1){
                    $('#alertPhp').html('<div class="alert alert-success" role="alert">' + data.msg + '</div>');
                }else{
                    $('#alertPhp').html('<div class="alert alert-danger" role="alert"></div>');
                    $.each(data.msg, function (key, value) {
                        $('#alertPhp').find('div').append(value + '<br>');
                    });
                }
            });
    });





});