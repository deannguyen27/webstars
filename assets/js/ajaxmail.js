$(document).ready(function() {
    $('#contact').validate({
        rules: {
            name: "required",            
            email: {
                required: true,
                email: true
            },
            subject: "required",
            message: "required"
        },
        errorElement: "span" ,                            
        messages: {
            name: "Please enter your name",
            email: "Please enter valid email address",
            subject: "Please enter your subject",
            message: "Please enter your message"
        },
        submitHandler: function(form) {
            var dataparam = $('#contact').serialize();

            $.ajax({
                type: 'POST',
                async: true,
                url: 'mailer.php',
                data: dataparam,
                datatype: 'json',
                cache: true,
                global: false,
                beforeSend: function() { 
                    $('#loader').show();
                },
                success: function(data) {
                    if(data == 'success'){
                        console.log(data);
                    } else {
                        $('.no-config').show();
                        console.log(data);
                    }

                },
                complete: function() { 
                    $('#loader').hide();
                }
            });
        }                
    });
});