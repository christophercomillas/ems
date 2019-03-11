var siteurl ="";
$(document).ready(function() {	
	$('#username').focus();
	siteurl = $('input[name="baseurl"]').val();
    $('.login-box-body').on('submit','form#_userlogin',function(event){
        event.preventDefault()
        $('.response').html('');

        var formURL = $(this).attr('action'), formData = $(this).serialize();

        if($('#username').val().trim()=='' || $('#password').val().trim()=='')
        {
            $('.response').html('<div class="alert alert-danger alert-x">* Please input username and password.</div>');   
            $('#username').focus();         
            return false;
        }

        $.ajax({
            url:formURL,
            type:'POST',
            data:formData,
            beforeSend:function(){

            },
            success:function(data){
                console.log(data);
                var data = JSON.parse(data);   

                if(data['st'])
                {                    
                    $('body').fadeOut(1000, function()
                    {
                        window.location = siteurl+'home/dashboard';     
                    });                
                       
                } 
                else 
                {
                    $('.response').html('<div class="alert alert-danger alert-x">'+data['msg']+'</div>');
                }              
            }
        });

        return false;
        
    });
});