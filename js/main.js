$(document).ready(function(e){
    $("#myform").on('submit', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'php_function/upload.php',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
                $('.submit').attr("disabled","disabled");
                $('.fileToUpload').prop('disabled', true);
                $('#myform').css("opacity",".5");
                $('#contact').hide();
                $('#loading').show();
            },
            success: function(msg){
                $('.statusMsg').html('');
                if(msg == 'ok'){
                    $('#myform')[0].reset();
                    $("#getCode").html("<p>Se ha cargado la campaña, y esta lista para ser ejecutada.</p>");
                    $("#TitleErr").html('<h4 id="TitleErr" class="modal-title">Exito!</h4>');
                    $("#myModal").modal('show');
                    //$('.statusMsg').html('<span style="font-size:18px;color:#34A853">Se ha cargado la campaña, y esta lista para ser ejecutada.</span>');
                }else if(msg == 'Creada'){
                    $("#getCode").html("<p>Esta lista ya esta creada.</p>");
                    $("#TitleErr").html('<h4 id="TitleErr" class="modal-title">Error!</h4>');
                    $("#myModal").modal('show');
                   // $('.statusMsg').html('<span style="font-size:18px;color:#EA4335">Esta lista ya esta cargada.</span>');
                }else if (msg == 'create') {
                    $("#getCode").html("<p>Esta campaña ya esta creada.</p>");
                    $("#TitleErr").html('<h4 id="TitleErr" class="modal-title">Error!</h4>');
                    $("#myModal").modal('show');
                   // $('.statusMsg').html('<span style="font-size:18px;color:#EA4335">Esta campaña ya esta cargada.</span>');
                }else{
                    $("#getCode").html("<p>Se presento un error, favor comuniquese con soporte@movigoo.com</p>");
                    $("#TitleErr").html('<h4 id="TitleErr" class="modal-title">Error!</h4>');
                    $("#myModal").modal('show');
                   // $('.statusMsg').html('<span style="font-size:18px;color:#EA4335">Se presento un error, favor comuniquese con soporte@movigoo.com</span>');
                }
                $('#myform').css("opacity","");
                $(".submit").removeAttr("disabled");
                $('#contact').show();
                $('#loading').hide();
            }
        });
    });
});