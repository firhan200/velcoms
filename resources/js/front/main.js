$(document).ready(function(){
    /* contact form */
    $(document).on("submit", "#contact-us-form", function(e){
        e.preventDefault();

        console.log("sumit contact");

        var submitBtn = $(this).find("#submit");
        var originalLabel = submitBtn.text();
        var $name = $(this).find('#name');
        var $email = $(this).find('#email');
        var $comments = $(this).find('#comments');

        var name = $name.val();
        var email = $email.val();
        var comments = $comments.val();

        //create ajax request
        $.ajax({
            url:'/api/contacts',
            method: 'post',
            data:{
                name: name,
                email: email,
                message : comments
            },
            beforeSend: function(){
                submitBtn.attr('disabled', true);
                submitBtn.text('Please wait...');
            },
            success: function(data){
                //success
                submitBtn.attr('disabled', false);
                submitBtn.text(originalLabel);

                if(data.is_success){
                    //success send message
                    alert('Thank your for contacting us');
                    

                    //reset form
                    $name.val('');
                    $email.val('');
                    $comments.val('');
                }else{
                    alert(data.status);
                }
            },
            error: function(err){
                //error
                submitBtn.attr('disabled', false);
                submitBtn.text(originalLabel);

                alert(err);
            }
        });
    })
    /* contact form */
})