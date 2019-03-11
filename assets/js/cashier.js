var siteurl ="";
$(document).ready(function() {	
	$('#username').focus();
	siteurl = $('input[name="baseurl"]').val();
    $('.form-login').on('submit','form#cashierLogin',function(){

    	var formURL = $(this).attr('action'), formData = $(this).serialize();

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
                        window.location = siteurl+'home/cashiermain';     
                    });     				
    			} 
                else 
                {
					$('.response').html('<div class="alert alert-danger">'+data['msg']+'</div>');
    			}              
    		}
    	});

    	return false;
    });



});