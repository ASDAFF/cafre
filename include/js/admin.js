$(document).ready(function(){

    if ($('#dropzone').length > 0) {
        var url = $('#dropzone').data('action');
		
        $('#dropzone').dropzone({
            uploadMultiple: false,
            parallelUploads: 1,
            url: url,
            success: function (file, response) {
                var data = JSON.parse(response);
                if(data.response == 'success'){
                     $('#dropzone-file').val(data.text);
                   
                }
               
            },
			
            sending : function () {
               $('#dropzone').html('Файл загружен');
            }
        });
    }

    $('#import-file').on('click', function(){
        var file = $('#dropzone-file').val();
        var url = $(this).data('action');
        var type = $(this).data('type');
        console.log(type);
        $.ajax({
            type : 'post',
            url : url,
            data : {
                file : file,
                type : type
            },
            success : function(data){
                $('#response').attr("style","display:true");
            }
        })
        return false;
    })
});
    
    