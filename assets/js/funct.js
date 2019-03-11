var siteurl ="";
var flag = 0;
$(document).ready(function() {	
    siteurl = $('input[name="baseurl"]').val();
    checksession();
    
    if($("#salesChart").length == 1) 
    {
        $.ajax({
            url:siteurl+'home/eloadchart',		
            beforeSend:function(){									
            },
            success:function(data1){	

                var data1 = JSON.parse(data1);

                $('#chartTitle').text(data1['chartTitle']);
               
                var salesChartCanvas = $("#salesChart").get(0).getContext("2d");
                // This will get the first returned node in the jQuery collection.
                labels = [];
                data = [];
                var salesChart = new Chart(salesChartCanvas);
                
                for(i = 0; i <= data1['months'].length -1 ;i++)
                {
                    labels.push(data1['months'][i]);
                }

                for(i = 0; i <= data1['sales'].length -1 ;i++)
                {
                    data.push(data1['sales'][i].toLocaleString());
                }

                var salesChartData = {
                    labels:labels,
                    datasets: [
                        {
                        label: "E - Load",
                        fillColor: "rgba(60,141,188,0.9)",
                        strokeColor: "rgba(60,141,188,0.8)",
                        pointColor: "#3b8bba",
                        pointStrokeColor: "rgba(60,141,188,1)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data:data
                        }
                    ]
                };

                var salesChartOptions = {
                    //Boolean - If we should show the scale at all
                    showScale: true,
                    //Boolean - Whether grid lines are shown across the chart
                    scaleShowGridLines: false,
                    //String - Colour of the grid lines
                    scaleGridLineColor: "rgba(0,0,0,.05)",
                    //Number - Width of the grid lines
                    scaleGridLineWidth: 1,
                    //Boolean - Whether to show horizontal lines (except X axis)
                    scaleShowHorizontalLines: true,
                    //Boolean - Whether to show vertical lines (except Y axis)
                    scaleShowVerticalLines: true,
                    //Boolean - Whether the line is curved between points
                    bezierCurve: true,
                    //Number - Tension of the bezier curve between points
                    bezierCurveTension: 0.3,
                    //Boolean - Whether to show a dot for each point
                    pointDot: false,
                    //Number - Radius of each point dot in pixels
                    pointDotRadius: 4,
                    //Number - Pixel width of point dot stroke
                    pointDotStrokeWidth: 1,
                    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                    pointHitDetectionRadius: 20,
                    //Boolean - Whether to show a stroke for datasets
                    datasetStroke: true,
                    //Number - Pixel width of dataset stroke
                    datasetStrokeWidth: 2,
                    //Boolean - Whether to fill the dataset with a color
                    datasetFill: true,
                    //String - A legend template
                    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%=datasets[i].label%></li><%}%></ul>",
                    //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                    maintainAspectRatio: true,
                    //Boolean - whether to make the chart responsive to window resizing
                    responsive: true
                };
            
                //Create the line chart
                salesChart.Line(salesChartData, salesChartOptions);
 
            }
        });  

    }

    var bindDateRangeValidation = function (f, s, e) {
        if(!(f instanceof jQuery)){
                console.log("Not passing a jQuery object");
        }
      
        var jqForm = f,
            startDateId = s,
            endDateId = e;
      
        var checkDateRange = function (startDate, endDate) {
            var isValid = (startDate != "" && endDate != "") ? startDate <= endDate : true;
            return isValid;
        }
    
        var bindValidator = function () {
            var bstpValidate = jqForm.data('bootstrapValidator');
            var validateFields = {
                startDate: {
                    validators: {
                        notEmpty: { message: 'This field is required.' },
                        callback: {
                            message: 'Start Date must less than or equal to End Date.',
                            callback: function (startDate, validator, $field) {
                                return checkDateRange(startDate, $('#' + endDateId).val())
                            }
                        }
                    }
                },
                endDate: {
                    validators: {
                        notEmpty: { message: 'This field is required.' },
                        callback: {
                            message: 'End Date must greater than or equal to Start Date.',
                            callback: function (endDate, validator, $field) {
                                return checkDateRange($('#' + startDateId).val(), endDate);
                            }
                        }
                    }
                },
                  customize: {
                    validators: {
                        customize: { message: 'customize.' }
                    }
                }
            }
            if (!bstpValidate) {
                jqForm.bootstrapValidator({
                    excluded: [':disabled'], 
                })
            }
          
            jqForm.bootstrapValidator('addField', startDateId, validateFields.startDate);
            jqForm.bootstrapValidator('addField', endDateId, validateFields.endDate);
          
        };
    
        var hookValidatorEvt = function () {
            var dateBlur = function (e, bundleDateId, action) {
                jqForm.bootstrapValidator('revalidateField', e.target.id);
            }
    
            $('#' + startDateId).on("dp.change dp.update blur", function (e) {
                $('#' + endDateId).data("DateTimePicker").setMinDate(e.date);
                dateBlur(e, endDateId);
            });
    
            $('#' + endDateId).on("dp.change dp.update blur", function (e) {
                $('#' + startDateId).data("DateTimePicker").setMaxDate(e.date);
                dateBlur(e, startDateId);
            });
        }
    
        bindValidator();
        hookValidatorEvt();
    };
    
    
    $(function () {
        var sd = new Date(), ed = new Date();
      
        $('#startDate').datetimepicker({ 
          pickTime: false, 
          format: "MM/DD/YYYY", 
          defaultDate: sd, 
          maxDate: ed 
        });
      
        $('#endDate').datetimepicker({ 
          pickTime: false, 
          format: "MM/DD/YYYY", 
          defaultDate: ed, 
          minDate: sd 
        });
    
        //passing 1.jquery form object, 2.start date dom Id, 3.end date dom Id
        bindDateRangeValidation($("#form"), 'startDate', 'endDate');
    });
    
    $('#datepicker').datepicker({
      autoclose: true
    });

    $('#datepicker1').datepicker({
      autoclose: true
    });

	$('input[id^=dennum]').inputmask("integer", { allowMinus: false,autoGroup: true, groupSeparator: ",", groupSize: 3 });  
	$('.inpmedx').inputmask();
	$('#querytrdate').daterangepicker();

    $('#list,#loadtransferlist').dataTable( {
        "pagingType": "full_numbers",
        "ordering": false,
        "processing": true
    });

    $('.datepickers').datepicker({
      autoclose: true
    });

    $('button#exportreceived').click(function(){
    	window.location = siteurl+'Excel_export/allreceived';
    });

    $('.salessrp').blur(function(){
    	var newsrp = $(this).text();
		newsrp = newsrp.replace(/,/g , "");			
    	var salesid = $(this).parent().closest('tr').attr('data-id');
    	var srp = $(this).parent().closest('tr').attr('data-srp');
    	srp = srp.replace(/,/g , "");	
    	var qty = $(this).parent().closest('tr').attr('data-qty');
    	qty = qty.replace(/,/g , "");	

    	if(newsrp.trim()==srp.trim())
    	{
    		return false;
    	}

    	if(isNaN(newsrp))
    	{
    		alert("Value must be numeric.");
    		$(this).text(srp);
    		return false;
    	}

    	if(flag==0)
    	{
    		//check if admin  
			$.ajax({
				url:siteurl+'user/checkusertype',		
				beforeSend:function(){									
				},
				success:function(datacheck){	
					// alert(response);
					console.log(datacheck);

					var datacheck = JSON.parse(datacheck);
					if(datacheck['st'])
					{					
						updateSRP(salesid,newsrp,srp,qty);
					}
					else 
					{
						$('table tr.list-'+salesid+' td.salessrp').text(srp);
						alert('User Not Allowed');
					}
				}
			});	
    	}
    });

    $('.salesnet').blur(function(){
    	var newnet = $(this).text();
		newnet = newnet.replace(/,/g , "");			
    	var salesid = $(this).parent().closest('tr').attr('data-id');
    	var net = $(this).parent().closest('tr').attr('data-net');
    	net = net.replace(/,/g , "");		
    	var qty = $(this).parent().closest('tr').attr('data-qty');
    	qty = qty.replace(/,/g , "");	

    	if(newnet.trim()==net.trim())
    	{
    		return false;
    	}

    	if(isNaN(newnet))
    	{
    		alert("Value must be numeric.");
    		$(this).text(net);
    		return false;
    	}

    	if(flag==0)
    	{
    		//check if admin  
			$.ajax({
				url:siteurl+'user/checkusertype',		
				beforeSend:function(){									
				},
				success:function(datacheck){	
					// alert(response);
					console.log(datacheck);

					var datacheck = JSON.parse(datacheck);
					if(datacheck['st'])
					{					
						updateNET(salesid,newnet,net,qty);
					}
					else 
					{
						$('table tr.list-'+salesid+' td.salessrp').text(srp);
						alert('User Not Allowed');
					}
				}
			});	
    	}

    });

    $('.achangedate').click(function(){    	
    	var salestrid = $(this).parent().closest('tr').attr('data-trid');
    	var salesid = $(this).parent().closest('tr').attr('data-id');		

    	var qdate = $('#dquery').val();
    		//check if admin  
			$.ajax({
				url:siteurl+'user/checkusertype',		
				beforeSend:function(){									
				},
				success:function(datacheck){	
					// alert(response);
					console.log(datacheck);

					var datacheck = JSON.parse(datacheck);
					if(datacheck['st'])
					{					
					    BootstrapDialog.show({
					        title: '<i class="fa fa-fw fa-calendar"></i> Change Date',
					        closable: true,
					        closeByBackdrop: false,
					        closeByKeyboard: true,
					        message: function(dialog) {
					            var $message = $("<div><img src='"+siteurl+"assets/img/ajax.gif'><small class='text-danger'>please wait...</small></div>");
					            var pageToLoad = dialog.getData('pageToLoad');
					            setTimeout(function(){
					                $message.load(pageToLoad); 
					            },1000);
					          return $message;
					        },
					        data: {
					            'pageToLoad': siteurl+'item/changeTransactionDate/'+salestrid,
					        },
					        cssClass: 'changepass',             
					        onshown: function(dialogRef){
					               
					        },
					        onhidden: function(dialogRef){                  
					            flag=0;
					        },
					        buttons: [{
					            icon: 'glyphicon glyphicon-ok ',
					            label: '  Submit',
					            cssClass: 'btn-primary',
					            hotkey: 13, // Enter.
					            action: function(dialogItself1) {  
					            	$buttons = this;
					            	$buttons.disable();
					            	$('.response').html('');

					            	var formURL = $('form#_changeTRDate').attr('action'), formData = $('form#_changeTRDate').serialize();
					            	formData+="&qdate="+qdate;

					            	var dpick = $('#datepickers').val();

					            	if(dpick.trim()=='')
					            	{
					            		$buttons.enable();
					            		return false;
					            	}

					            	if(qdate.trim()==dpick.trim())
					            	{					            		
										$('.response-dialog').html('<div class="alert alert-danger alert-res">Date selected must not equal to transaction date.</div>');
										$buttons.enable();
					            		return false;
					            	}

								    BootstrapDialog.show({
								    	title: 'Confirmation',
								        message: 'Update '+qdate+' to '+dpick+'?',
								        closable: true,
								        closeByBackdrop: false,
								        closeByKeyboard: true,
								        onshow: function(dialog) {
								            flag = 1;
								        },
								        onshown:function(dialog){
								           
								        },
								        onhidden:function(dialog)
								        {
								        	flag = 0;
								        },
								        buttons: [{
								            icon: 'glyphicon glyphicon-ok-sign',
								            label: 'Ok',
								            cssClass: 'btn-primary',
								            hotkey: 13,
								            action:function(dialogItself){

								            	$button1 = this;

								            	$button1.disable();

												$.ajax({
													url:siteurl+'transaction/changeTransactionDateByTrID',
													data:formData,
													type:'POST',			
													beforeSend:function(){									
													},
													success:function(data){	
														// alert(response);
														console.log(data);

														var data = JSON.parse(data);
														if(data['st'])
														{			
															BootstrapDialog.closeAll();
															alert('Date Updated.');
															$("table.demo-table4 tbody tr.list-"+salesid+" td").css( "background-color","red");

															$("table.demo-table4 tbody tr.list-"+salesid+" td i.achangedate").hide();
															
															$("table.demo-table4 tbody tr.list-"+salesid+" td i.aremove").hide();

															$("table.demo-table4 tbody tr.list-"+salesid+" td.salessrp").prop('contenteditable', false); 

															$("table.demo-table4 tbody tr.list-"+salesid+" td.salesnet").prop('contenteditable', false); 

															var salestotal = $("table.demo-table4 tbody tr.list-"+salesid+" td.salestotal").text();

															salestotal = salestotal.replace(/,/g , "");	

															var totsales = $('span._totsales').text();
															totsales = totsales.replace(/,/g , "");	

															totsales = parseFloat(totsales) - parseFloat(salestotal);

															$('span._totsales').text(totsales.toFixed(2));
														}
														else 
														{
															$buttons.enable();
															dialogItself.close();
															$('.response-dialog').html('<div class="alert alert-danger alert-res">'+data['msg']+'</div>');
														}
													}
												});
								            }
								        }, {
								        	icon: 'glyphicon glyphicon-remove-sign',
								            label: 'Cancel',
								            action: function(dialogItself){
								                dialogItself.close();		
								                $buttons.enable();										
								            }
								        }]
								    });                    

					            }            
					        }, {
					        icon: 'glyphicon glyphicon-remove-sign',
					        label: ' Cancel',
					        cssClass: 'btn-default',
					        action: function(dialogItself1){
					            dialogItself1.close();
					        }
					        }]
					    });
					}
					else 
					{
						//$('table tr.list-'+salesid+' td.salessrp').text(srp);
						alert('User Not Allowed');
					}
				}
			});
    });

    $('.aremove').click(function(){    	
    	var salestrid = $(this).parent().closest('tr').attr('data-trid');
    	var salesid = $(this).parent().closest('tr').attr('data-id');			
		$.ajax({
			url:siteurl+'user/checkusertype',		
			beforeSend:function(){									
			},
			success:function(usercheck){	
				// alert(response);
				console.log(usercheck);

				var usercheck = JSON.parse(usercheck);
				if(usercheck['st'])
				{					
					var r = confirm("Delete this item?");
					if (r == true) 
					{
						//get transaction items

						$.ajax({
							url:siteurl+'transaction/deleteItemsBySalesID',
							data:{salestrid:salestrid,salesid:salesid},
							type:'POST',			
							beforeSend:function(){									
							},
							success:function(data){	
								// alert(response);
								console.log(data);

								var data = JSON.parse(data);
								if(data['st'])
								{			
									alert('Item Deleted.');
									$("table.demo-table4 tbody tr.list-"+salesid+" td").css( "background-color","red");

									$("table.demo-table4 tbody tr.list-"+salesid+" td i.achangedate").hide();
									
									$("table.demo-table4 tbody tr.list-"+salesid+" td i.aremove").hide();

									$("table.demo-table4 tbody tr.list-"+salesid+" td.salessrp").prop('contenteditable', false); 

									$("table.demo-table4 tbody tr.list-"+salesid+" td.salesnet").prop('contenteditable', false); 

									var salestotal = $("table.demo-table4 tbody tr.list-"+salesid+" td.salestotal").text();

									salestotal = salestotal.replace(/,/g , "");	

									var totsales = $('span._totsales').text();
									totsales = totsales.replace(/,/g , "");	

									totsales = parseFloat(totsales) - parseFloat(salestotal);

									$('span._totsales').text(totsales.toFixed(2));
								}
								else 
								{						
									alert(data['msg']);
								}
							}
						});
					}


				}
				else 
				{
					//$('table tr.list-'+salesid+' td.salessrp').text(srp);
					alert('User Not Allowed');
				}
			}
		});
    });


	$('div.trwrap').on('click','button.btnchdt',function(event){
		event.preventDefault()
		var trid = $(this).attr('data-datebt');
		var newdate = $('input.datep'+trid).val();

		if(newdate.trim()=="")
		{
			alert('Please input date.');
			return false;
		}

		$.ajax({
			url:siteurl+'transaction/changeTransactionDate',
			data:{newdate:newdate,trid:trid},
			type:'POST',			
			beforeSend:function(){									
			},
			success:function(data){	
				// alert(response);
				console.log(data);

				var data = JSON.parse(data);
				if(data['st'])
				{
					$('span.sp'+trid).html('<span class="alert alert-danger">'+data['newdate']+'</span>');
					alert('Date Updated..');
				}
				else 
				{
					alert(data['msg']);
				}
			}
		});		
	});

	$('#addnewuser').click(function(){
	    BootstrapDialog.show({
	        title: '<i class="fa fa-user-plus" aria-hidden="true"></i> Add New User',
	        closable: true,
	        closeByBackdrop: false,
	        closeByKeyboard: true,
	        message: function(dialog) {
	            var $message = $("<div><img src='"+siteurl+"assets/img/ajax.gif'><small class='text-danger'>please wait...</small></div>");
	            var pageToLoad = dialog.getData('pageToLoad');
	            setTimeout(function(){
	                $message.load(pageToLoad); 
	            },1000);
	          return $message;
	        },
	        data: {
	            'pageToLoad': siteurl+'user/addnewuser',
	        },
	        cssClass: 'gcrevalidate',             
	        onshown: function(dialogRef){
	               
	        },
	        onhidden: function(dialogRef){                  
	            flag=0;
	        },
	        buttons: [{
	            icon: 'glyphicon glyphicon-ok ',
	            label: '  Submit',
	            cssClass: 'btn-primary',
	            hotkey: 13, // Enter.
	            action: function(dialogItself) {  
	            	$buttons = this;
	            	$buttons.disable();
	            	$('.response').html('');
	                var formURL = $('form#_saveUser').attr('action'), formData = $('form#_saveUser').serialize();
	                var username = $('#username').val();
	                var fullname = $('#fullname').val();
	                var bunit = $('#bunit').val();
	                var department = $('#department').val();
	                var password = $('#password').val();
	                var status = $('#status').val();
	                var idnumber = $('#idnumber').val();

	                if(username.trim()==undefined)
	                {
	                	$buttons.enable();
	                	return false;
	                }

	                if(username.trim()=="")
	                {
	                	$buttons.enable();
	                	$('.response').html('<div class="alert alert-danger alert-res">Please input username.</div>');
	                	return false;
	                }

	                if(fullname.trim()=="")
	                {
	                	$buttons.enable();
	                	$('.response').html('<div class="alert alert-danger alert-res">Please input fullname.</div>');
	                	return false;
	                }	

	                if(password.trim()=="")
	                {
	                	$buttons.enable();
	                	$('.response').html('<div class="alert alert-danger alert-res">Please input password.</div>');
	                	return false;
	                }

	                if(department.trim()=="")
	                {
	                	$buttons.enable();
	                	$('.response').html('<div class="alert alert-danger alert-res">Please select department.</div>');
	                	return false;
	                }

	                if(status.trim()=="")
	                {
	                	$buttons.enable();
	                	$('.response').html('<div class="alert alert-danger alert-res">Please select status.</div>');
	                	return false;
	                }

	                if(department.trim()=='retail store' && idnumber.trim()=="")
	                {
	                	$buttons.enable();
	                	$('.response').html('<div class="alert alert-danger alert-res">Please input idnumber.</div>');
	                	return false;
	                }

	                if(department.trim()=='retail store' && idnumber.trim()=="")
	                {
	                	$buttons.enable();
	                	$('.response').html('<div class="alert alert-danger alert-res">Please input idnumber.</div>');
	                	return false;
	                }

	                if(bunit.trim()=="")
	                {
	                	if(department.trim()!="admin")
	                	{
		                	$buttons.enable();
		                	$('.response').html('<div class="alert alert-danger alert-res">Please select business unit.</div>');
		                	return false;	                		
	                	}
	                }

			        BootstrapDialog.show({
			        	title: 'Confirmation',
			            message: 'Add New User?',
			            closable: true,
			            closeByBackdrop: false,
			            closeByKeyboard: true,
			            onshow: function(dialog) {
			                // dialog.getButton('button-c').disable();
			            },
			            onshown:function(dialog){
			                restrictback=0;
			            },
			            buttons: [{
			                icon: 'glyphicon glyphicon-ok-sign',
			                label: 'Yes',
			                cssClass: 'btn-primary',
			                hotkey: 13,
			                action:function(dialogItself1){	
			                	$buttons1 = this;		
			                	$buttons1.disable();	            	
								$.ajax({
						    		url:formURL,
						    		type:'POST',
						    		data:formData,
									beforeSend:function(){
										//$('#processing-modal').modal('show');
									},
									success:function(data){
										console.log(data);
										var data = JSON.parse(data);
										
										if(data['st'])
										{
											dialogItself.close();
											dialogItself1.close();
											var dialog = new BootstrapDialog({
								            message: function(dialogRef){
								            var $message = $('<div>User successfully saved.</div>');			        
								                return $message;
								            },
								            closable: false
									        });
									        dialog.realize();
									        dialog.getModalHeader().hide();
									        dialog.getModalFooter().hide();
									        dialog.getModalBody().css('background-color', '#0088cc');
									        dialog.getModalBody().css('color', '#fff');
									        dialog.open();
						               		setTimeout(function(){
						                    	window.location = siteurl+'user/manageusers';
						               		}, 1700);											
										}
										else 
										{
											dialogItself1.close();
											$('.response').html('<div class="alert alert-danger alert-res">'+data['msg']+'</div>');
											$buttons.enable();
										}
									}
								});
			                }
			            }, {
			            	icon: 'glyphicon glyphicon-remove-sign',
			                label: 'Cancel',
			                action: function(dialogItself){
			                    dialogItself.close();
			                    $buttons.enable();
			                }
			            }]
			        });
	            }            
	        }, {
	        icon: 'glyphicon glyphicon-remove-sign',
	        label: ' Cancel',
	        cssClass: 'btn-default',
	        action: function(dialogItself){
	            dialogItself.close();
	        }
	        }]
	    });
	});

    //item events add
	$('#addnewitem').click(function(){
	    BootstrapDialog.show({
	        title: '<i class="fa fa-user-plus" aria-hidden="true"></i> Add New Item',
	        closable: true,
	        closeByBackdrop: false,
	        closeByKeyboard: true,
	        message: function(dialog) {
	            var $message = $("<div><img src='"+siteurl+"assets/img/ajax.gif'><small class='text-danger'>please wait...</small></div>");
	            var pageToLoad = dialog.getData('pageToLoad');
	            setTimeout(function(){
	                $message.load(pageToLoad); 
	            },1000);
	          return $message;
	        },
	        data: {
	            'pageToLoad': siteurl+'item/addedNewItemDialog',
	        },
	        cssClass: 'gcrevalidate',             
	        onshown: function(dialogRef){
	               
	        },
	        onhidden: function(dialogRef){                  
	            flag=0;
	        },
	        buttons: [{
	            icon: 'glyphicon glyphicon-ok ',
	            label: '  Submit',
	            cssClass: 'btn-primary',
	            hotkey: 13, // Enter.
	            action: function(dialogItself) {  
	            	$buttons = this;
	            	$buttons.disable();
	            	$('.response').html('');

	            	var formURL = $('form#_addItem').attr('action'), formData = $('form#_addItem').serialize();
	            	var itemname = $('#itemname').val();
	            	var itemtype = $('#itemtypeadd').val();
	            	var srp = $('#srp').val();
	            	var netprice = $('#netprice').val();
	            	var faditemcode = $('#faditemcode').val();
           	

	                if(itemname.trim()==undefined)
	                {
	                	$buttons.enable();
	                	return false;
	                }

	                if(itemname.trim()=="")
	                {
	                	$buttons.enable();
	                	$('.response').html('<div class="alert alert-danger alert-res">Please input item name.</div>');
	                	return false;
	                }

	                if(itemtype.trim()=="")
	                {
	                	$buttons.enable();
	                	$('.response').html('<div class="alert alert-danger alert-res">Please select item type.</div>');
	                	return false;
	                }	  

	                if(itemtype.trim()!='1')
	                {
	                	if(srp.trim()=='0.00' || srp=="0")
	                	{
		                	$buttons.enable();
		                	$('.response').html('<div class="alert alert-danger alert-res">Please input srp.</div>');	                		
	                		return false;
	                	}

	                	if(netprice.trim()=='0.00' || netprice=="0")
	                	{
		                	$buttons.enable();
		                	$('.response').html('<div class="alert alert-danger alert-res">Please input netprice.</div>');	                		
	                		return false;
	                	}
	                }

			        BootstrapDialog.show({
			        	title: 'Confirmation',
			            message: 'Add New Item?',
			            closable: true,
			            closeByBackdrop: false,
			            closeByKeyboard: true,
			            onshow: function(dialog) {
			                // dialog.getButton('button-c').disable();
			            },
			            onshown:function(dialog){
			                restrictback=0;
			            },
			            buttons: [{
			                icon: 'glyphicon glyphicon-ok-sign',
			                label: 'Yes',
			                cssClass: 'btn-primary',
			                hotkey: 13,
			                action:function(dialogItself1){	
			                	$buttons1 = this;		
			                	$buttons1.disable();	            	
								$.ajax({
						    		url:formURL,
						    		type:'POST',
						    		data:formData,
									beforeSend:function(){
										//$('#processing-modal').modal('show');
									},
									success:function(data){
										console.log(data);
										var data = JSON.parse(data);
										
										if(data['st'])
										{
											dialogItself.close();
											dialogItself1.close();
											var dialog = new BootstrapDialog({
								            message: function(dialogRef){
								            var $message = $('<div>Item successfully saved.</div>');			        
								                return $message;
								            },
								            closable: false
									        });
									        dialog.realize();
									        dialog.getModalHeader().hide();
									        dialog.getModalFooter().hide();
									        dialog.getModalBody().css('background-color', '#0088cc');
									        dialog.getModalBody().css('color', '#fff');
									        dialog.open();
						               		setTimeout(function(){
						                    	window.location = siteurl+'item/manageitems';
						               		}, 1700);											
										}
										else 
										{
											dialogItself1.close();
											$('.response').html('<div class="alert alert-danger alert-res">'+data['msg']+'</div>');
											$buttons.enable();
										}
									}
								});
			                }
			            }, {
			            	icon: 'glyphicon glyphicon-remove-sign',
			                label: 'Cancel',
			                action: function(dialogItself){
			                    dialogItself.close();
			                    $buttons.enable();
			                }
			            }]
			        });                    

	            }            
	        }, {
	        icon: 'glyphicon glyphicon-remove-sign',
	        label: ' Cancel',
	        cssClass: 'btn-default',
	        action: function(dialogItself){
	            dialogItself.close();
	        }
	        }]
	    });
    });

    $('#addnewsimcard').click(function(){
	    BootstrapDialog.show({
	        title: '<i class="fa fa-user-plus" aria-hidden="true"></i> Add New Simcard',
	        closable: true,
	        closeByBackdrop: false,
	        closeByKeyboard: true,
	        message: function(dialog) {
	            var $message = $("<div><img src='"+siteurl+"assets/img/ajax.gif'><small class='text-danger'>please wait...</small></div>");
	            var pageToLoad = dialog.getData('pageToLoad');
	            setTimeout(function(){
	                $message.load(pageToLoad); 
	            },1000);
	          return $message;
	        },
	        data: {
	            'pageToLoad': siteurl+'item/addNewSimcardDialog',
	        },
	        cssClass: 'modal-small',             
	        onshown: function(dialogRef){
                $('#addnewsimcard').prop("disabled",true);
	        },
	        onhidden: function(dialogRef){                  
	            $('#addnewsimcard').prop("disabled",false);
	        },
	        buttons: [{
	            icon: 'glyphicon glyphicon-ok ',
	            label: '  Submit',
	            cssClass: 'btn-primary',
	            hotkey: 13, // Enter.
	            action: function(dialogItself) {  
                    dialogItself.enableButtons(false);
                    dialogItself.setClosable(false);
                    $('.response').html('');

                    //aridri
                    //$('#processing-modal').modal('show');

	            	var formURL = $('form#_addSimcard').attr('action'), formData = $('form#_addSimcard').serialize();
                    var simcardnum = $('#simcardnum').val();
                    var simtype = $('#simtype').val();
                    var begbal = $('#begbal').val();

                    var begbaln = convertToNumber(begbal);

	                if(simcardnum.trim()==undefined)
	                {
                        dialogItself.enableButtons(true);
                        dialogItself.setClosable(true);
	                	return false;
	                }

	                if(simcardnum.trim()=="")
	                {
                        dialogItself.enableButtons(true);
                        dialogItself.setClosable(true);
	                	$('.response').html('<div class="alert alert-danger alert-res">Please input Sim Card Number.</div>');
	                	return false;
	                }

			        BootstrapDialog.show({
			        	title: 'Confirmation',
			            message: 'Add New Sim Card?',
			            closable: true,
			            closeByBackdrop: false,
			            closeByKeyboard: true,
			            onshow: function(dialog) {
			                // dialog.getButton('button-c').disable();
			            },
			            onshown:function(dialog){
			                restrictback=0;
                        },
                        onhidden:function(dialog){
                            dialogItself.enableButtons(true);
                            dialogItself.setClosable(true);
                        },
			            buttons: [{
			                icon: 'glyphicon glyphicon-ok-sign',
			                label: 'Yes',
			                cssClass: 'btn-primary',
			                hotkey: 13,
			                action:function(dialogItself1){	
                                // dialogItself1.enableButtons(false);
                                // dialogItself1.setClosable(false);
                                dialogItself1.close();
                                $('#processing-modal').modal('show');
								$.ajax({
						    		url:formURL,
						    		type:'POST',
						    		data:{ simcardnum:simcardnum, simtype:simtype, begbaln:begbaln },
									beforeSend:function(){
										//$('#processing-modal').modal('show');
									},
									success:function(data){
										console.log(data);
										var data = JSON.parse(data);
										
										if(data['st'])
										{
                                            $('#processing-modal').modal('hide');
											dialogItself.close();
											var dialog = new BootstrapDialog({
								            message: function(dialogRef){
								            var $message = $('<div>Sim Card successfully saved.</div>');			        
								                return $message;
								            },
								            closable: false
									        });
									        dialog.realize();
									        dialog.getModalHeader().hide();
									        dialog.getModalFooter().hide();
									        dialog.getModalBody().css('background-color', '#0088cc');
									        dialog.getModalBody().css('color', '#fff');
									        dialog.open();
						               		setTimeout(function(){
						                    	window.location = siteurl+'item/managesimcard';
						               		}, 1700);											
										}
										else 
										{
                                            $('#processing-modal').modal('hide');
											$('.response').html('<div class="alert alert-danger alert-res">'+data['msg']+'</div>');
                                            dialogItself.enableButtons(true);
                                            dialogItself.setClosable(true);
										}
									}
								});
			                }
			            }, {
			            	icon: 'glyphicon glyphicon-remove-sign',
			                label: 'Cancel',
			                action: function(dialogItself){
			                    dialogItself.close();
			                    $buttons.enable();
			                }
			            }]
			        });                    

	            }            
	        }, {
	        icon: 'glyphicon glyphicon-remove-sign',
	        label: ' Cancel',
	        cssClass: 'btn-default',
	        action: function(dialogItself){
	            dialogItself.close();
	        }
	        }]
	    });
    });

	// $('body').on('change','select#department',function(event){
	// 	if($(this).val()=="1")
	// 	{
	// 		$('#bunit option:eq(0)').prop('selected', true); 
	// 		$('#bunit').prop("disabled",true);
	// 	}
	// 	else 
	// 	{
	// 		$('#bunit').prop("disabled",false);
	// 	}
	// });

	$('body').on('change','select#itemtypeadd',function(event){
		if($(this).val().trim()=="")
		{				
			$('#simcard').prop("disabled",true);
			$('#simcard').val(0);

			$('#srp').prop("disabled",true);
			$('#srp').val(0.00);

			$('#netprice').prop("disabled",true);
			$('#netprice').val(0.00);
		}
		else if($(this).val().trim()=="1") 
		{
			$('#simcard').prop("disabled",false);
			$('#simcard').val(0);

			$('#srp').prop("disabled",true);
			$('#srp').val(0.00);

			$('#netprice').prop("disabled",true);
			$('#netprice').val(0.00);

		}
		else 
		{
			$('#simcard').prop("disabled",true);
			$('#simcard').val(0);

			$('#srp').prop("disabled",false);
			$('#srp').val(0.00);

			$('#netprice').prop("disabled",false);
			$('#netprice').val(0.00);
		}
	});

	$('body').on('change','select#itemtype',function(event){
		if($(this).val()=="admin")
		{
			$('#bunit option:eq(0)').prop('selected', true); 
			$('#bunit').prop("disabled",true);
		}
		else 
		{
			$('#bunit').prop("disabled",false);
		}
	});


	$('body').on('click','button#generatePassword',function(event){
		var pass = randompass();
		$('input#password').val(pass);
		$('select#status').select();
	});	

    $('div.trwrap').on('click','button.updatenet',function(event){
    	event.preventDefault();
    	var itrid = $(this).attr('data-itemidbtn');
    	var netprice = $('.net-'+itrid).val();
    	var itemid = $('.itemid-'+itrid).val();

    	if(isNaN(itrid))
    	{
    		alert("Invalid Transaction Number.");
    		return false;
    	}

    	if(isNaN(itemid))
    	{
    		alert("Invalid Item ID Number.");
    		return false;
    	}    	

    	netprice = netprice.replace(/,/g , "");

    	if(isNaN(netprice))
    	{
    		alert("Invalid Item ID Net Price.");
    		return false;
    	}
		$.ajax({
			url:siteurl+'transaction/updateLoadDetails',
			data:{itrid:itrid,netprice:netprice,itemid:itemid},
			type:'POST',			
			beforeSend:function(){									
			},
			success:function(data){	
				// alert(response);
				//console.log(data);

				var data = JSON.parse(data);
				if(data['st'])
				{
					$("div.iname-"+itrid).css( "background-color","red" );
					alert('Item Updated..');

				}
				else 
				{
					alert('Error Changing Date..');
				}
			}
		});

    	
    });

	$('div.trwrap').on('click','button.btnremovetr',function(event){
		event.preventDefault()
		var trid = $(this).attr('data-trid');

		var trnum = $('span.span'+trid).text();

        BootstrapDialog.show({
        	title: 'Confirmation',
            message: 'Are you sure you like to delete transaction # '+trnum+'?',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog) {
                // dialog.getButton('button-c').disable();
            },
            onshown:function(dialog){
                restrictback=0;
            },
            buttons: [{
                icon: 'glyphicon glyphicon-ok-sign',
                label: 'Ok',
                cssClass: 'btn-primary',
                hotkey: 13,
                action:function(dialogItself){

                	$button = this;

                	$button.disable();

					$.ajax({
						url:siteurl+'transaction/removeTransaction',
						data:{trid:trid},
						type:'POST',			
						beforeSend:function(){									
						},
						success:function(data){	
							// alert(response);
							console.log(data);

							var data = JSON.parse(data);
							if(data['st'])
							{								
								alert('Transaction # '+trid+" successfully deleted.");
								dialogItself.close();
								$("div").remove(".wrap"+trid);
							}
							else 
							{
								dialogItself.close();
								alert(data['msg']);
							}
						}
					});
                }
            }, {
            	icon: 'glyphicon glyphicon-remove-sign',
                label: 'Cancel',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });		
	});

	$('.form-container').on('submit','form#_gcrequest',function(event){
		event.preventDefault()
		$('.response').html('');
		var file_data = $('#file').prop('files')[0];
		var formURL = $(this).attr('action');
		var formData = new FormData($(this)[0]);	
		formData.append('file', file_data);
				// var file_data = $('#file').prop('files')[0];
		  //       var formData = new FormData($(this)[0]);
		  //       formData.append('file', file_data);

        BootstrapDialog.show({
        	title: 'Confirmation',
            message: 'Are you sure you want to submit GC Request?',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog) {
                // dialog.getButton('button-c').disable();
                $('#externalbtn').prop("disabled",true);
            },
            onhidden:function(dialog){
            	$('#externalbtn').prop("disabled",false);
            },
            buttons: [{
                icon: 'glyphicon glyphicon-ok-sign',
                label: 'Yes',
                cssClass: 'btn-primary',
                hotkey: 13,
                action:function(dialogItself){
                	$buttons = this;
                	$buttons.disable();                	
                	dialogItself.close();
						$.ajax({
				    		url:formURL,
				    		type:'POST',
							data: formData,
							dataType: 'text',
							enctype: 'multipart/form-data',
						    async: false,
						    cache: false,
						    contentType: false,
						    processData: false,
							beforeSend:function(){
							},
							success:function(data){
								console.log(data);
								var data = JSON.parse(data);

								if(data['st'])
								{
									dialogItself.close();
									var dialog = new BootstrapDialog({
						            message: function(dialogRef){
						            var $message = $('<div>GC Request Saved.</div>');			        
						                return $message;
						            },
						            closable: false
							        });
							        dialog.realize();
							        dialog.getModalHeader().hide();
							        dialog.getModalFooter().hide();
							        dialog.getModalBody().css('background-color', '#0088cc');
							        dialog.getModalBody().css('color', '#fff');
							        dialog.open();
				               		setTimeout(function(){
				                    	window.location = siteurl;
				               		}, 1700);											
								}
								else 
								{
									$('.response').html('<div class="alert alert-danger" id="danger-x">'+data['msg']+'</div>');
									$buttons.enable();
								}
							}
						});
                }
            }, {
            	icon: 'glyphicon glyphicon-remove-sign',
                label: 'No',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });


	});

    $('table#saleslist').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax":{
	    "url": siteurl+'transaction/saleslist',
	    "dataType": "json",
	    "type": "POST"
	    },
    	"columns": [
	        { "data": "st_datetime" },
	        { "data": "st_trnum" },
	        { "data": "it_name" },
	        { "data": "si_qty" },
	        { "data": "si_srp"}
	    ]
    });

    $('table#userlist').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax":{
	    "url": siteurl+'user/userlist',
	    "dataType": "json",
	    "type": "POST"
	    },
    	"columns": [
	        { "data": "u_idnumber" },
	        { "data": "u_fullname" },
	        { "data": "u_username" },
	        { "data": "dept_name" },
	        { "data": "bu_name" },
	        { "data": "u_status"},
	        { "data": "action"},
	        { "data": "u_datecreated"}       
	    ]
    });

    $('table#itemList').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax":{
	    "url": siteurl+'item/itemlist',
	    "dataType": "json",
	    "type": "POST"
	    },
    	"columns": [
	        { "data": "it_name" },
	        { "data": "ity_name" },
	        { "data": "it_netprice" },
	        { "data": "it_srp" },
	        { "data": "it_fad_itemcode"},
	        { "data": "action" },
	        { "data": "it_item_datecreated"}
	    ]

    });

    $('table#simeodlist').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax":{
	    "url": siteurl+'item/simcardeodlist',
	    "dataType": "json",
	    "type": "POST"
	    },
    	"columns": [
	        { "data": "date" },
            { "data": "simcard" },
            { "data": "name" },
	        { "data": "balance" },
	        { "data": "eodby" }
	    ]
    });

    $('table#simcardList').DataTable({
        "processing": true,
        "serverSide": true,
        "ordering": false,
        "ajax":{
	    "url": siteurl+'item/simcardlist',
	    "dataType": "json",
	    "type": "POST"
	    },
    	"columns": [
	        { "data": "simcardnum" },
            { "data": "simcarditemref" },
            { "data": "status"},
            { "data": "begbal"},
	        { "data": "addedby" },
	        { "data": "action" },
	        { "data": "dateadded"}
	    ]
    }); 

    $('#btn-report').click(function(){
    	$('.response').html('');
    	$('#btn-report').prop("disabled",true);
    	var date = $('#datepicker').val();
    	date = convertToSqlDate(date);
    	var reportype = "1";

    	if(date.trim()=="" || date.trim()=="undefined--undefined")
    	{
    		$('.response').html('<div class="alert alert-danger alert-res">Please select date.</div>');
    		$('#btn-report').prop("disabled",false);
    		return false;
    	}

		$.ajax({
			url:siteurl+'transaction/checkTransaction',
			data:{date:date,reportype:reportype},
			type:'POST',			
			beforeSend:function(){									
			},
			success:function(data){	
				// alert(response);
				console.log(data);

				var data = JSON.parse(data);
				if(data['st'])
				{								
					window.location.href = siteurl+'Excel_export/reportAccounting/'+date+'/'+reportype;
				}
				else 
				{
					$('.response').html('<div class="alert alert-danger alert-res">'+data['msg']+'</div>');
				}
			}
		});

		$('#btn-report').prop("disabled",false);
    	return false;
    });    

    $('#btn-reportran').click(function(){
    	$('.response').html('');
        $('#btn-reportran').prop("disabled",true);
        
        var sdate = $('#startDate').val();
        var edate = $('#endDate').val();

        sdate = convertToSqlDate(sdate);
        edate = convertToSqlDate(edate);

    	if(sdate.trim()=="" || sdate.trim()=="undefined--undefined" || edate.trim()=="" || edate.trim()=="undefined--undefined")
    	{
    		$('.response').html('<div class="alert alert-danger alert-res">Please select date range.</div>');
    		$('#btn-reportran').prop("disabled",false);
    		return false;
        }

		$.ajax({
			url:siteurl+'transaction/checkTransactionByRange',
			data:{sdate:sdate,edate:edate},
			type:'POST',			
			beforeSend:function(){									
			},
			success:function(data){	
				// alert(response);
				console.log(data);

				var data = JSON.parse(data);
				if(data['st'])
				{								
                    window.location.href = siteurl+'Excel_export/reportAccountingByRange/'+sdate+'/'+edate;                    
				}
				else 
				{
					$('.response').html('<div class="alert alert-danger alert-res">'+data['msg']+'</div>');
				}
			}
		});      

		$('#btn-reportran').prop("disabled",false);
    	return false;
    }); 


	$('#_logout').click(function(){

        BootstrapDialog.show({
        	title: 'Logout',
            message: 'Are you sure you want to log out?',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog) {
                // dialog.getButton('button-c').disable();
            },
            onshown:function(dialog){
                restrictback=0;
            },
            buttons: [{
                icon: 'glyphicon glyphicon-ok-sign',
                label: 'Yes, Please',
                cssClass: 'btn-primary',
                hotkey: 13,
                action:function(dialogItself){
					$.ajax({
						url:siteurl+'User/logoutuser2',			
						beforeSend:function(){									
						},
						success:function(data){	
							// alert(response);
							console.log(data);
							var  data = JSON.parse(data);
							if(data['st'])
							{
								window.location.href = siteurl+'home/login';
							} 

						}
					});
                	//window.location.href = siteurl+'User/logoutuser';
                }
            }, {
            	icon: 'glyphicon glyphicon-remove-sign',
                label: 'No Thanks',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });
    }); 

	$("input[id^=dennum]").keyup(function(){
		var sum = 0;
		var qty = 0;

		$('input[id^=dennum]').each(function(){
			// var a = $(this).parent('.col-sm-3').find('input.denval').val();
			var dnid = $(this).attr('id').slice(6);
			var a = $("#dennum"+dnid).val();
			a = parseInt(a.replace(/,/g , ""));			
			a = isNaN(a) ? 0 : a;
			qty +=a;
			var den = parseInt($("#m"+dnid).val());
			var sub = a * den;
			sum+=sub;
		});	
		$('span#internaltot').text(addCommas(parseFloat(sum).toFixed(2)));
		$('span#totgcreqqty').text(addCommas(parseInt(qty)));
    });
    
    var upfiles = $('#_uploadtxtfile');    
    $('form#_uploadtxtfile').submit(function(e){
        e.preventDefault(); 

        var x = document.getElementById("upload");
        var txt = "";
        if ('files' in x) 
        {
            if(x.files.length == 0)
            {
                alert('Select one or more files');
                return false;
            }

        }        

        var formData = new FormData(upfiles[0]);
        $.ajax({
            url:siteurl+'/Excel_export/textfiletoexcel',
            type:'POST',
            data: formData,
            enctype: 'multipart/form-data',
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function (success) {
                //console.log(success);
    
                //var success = JSON.parse(success);
    
            },
            error: function (error) {
                //console.log(error);
            },
            complete: function (complete) {
                //console.log(complete);
            },
            beforeSend: function (jqXHR, settings) {
    
                // var self = this;
                var xhr = settings.xhr;
                
                settings.xhr = function () {
                    var output = xhr();
                    output.previous_text = '';
                    //dialogItself.close();
                    $('#processing-modal').modal('show');
                    //console.log(output);
                    output.onreadystatechange = function () {
                        try{
                            //console.log(output.readyState);
                            if (output.readyState == 3) {
                                
                                var new_response = output.responseText.substring(output.previous_text.length);
    
                                var result = JSON.parse( output.responseText.match(/{[^}]+}$/gi) );                               
                                
                                //var result2 = JSON.parse( new_response );
                                console.log(result);
    
                                if(result.status=='data-process')
                                {
                                    $('h4.loading').html(result.message);
                                }

                                if(result.status=='data-matching')
                                {
                                    $('h4.loading').html(result.message);
                                }

                                if(result.status=='data-matched')
                                {
                                    $('h4.loading').html(result.message);
                                }
                                
                                if(result.status=='error')
                                {
                                    
                                    $('#processing-modal').modal('hide');
                                    swal({
                                        title: "EOD Failed",
                                        type: "warning",
                                        text: result.message,
                                        showCancelButton: false,
                                        confirmButtonColor: "#DD6B55",
                                        confirmButtonText: "OK",
                                        closeOnConfirm: true
                                    })
                                }     
    
                                if(result.status=='complete')
                                {
                                    $('h4.loading').html(result.message);
                                    window.location = siteurl+'excelfiles/txtfiletoexcel.xls';
                                    setTimeout(function(){
                                        window.location = siteurl+'home/textfileExcelConversion';
                                    },10000)
                                }                              
                          
    
                                output.previous_text = output.responseText;
    
                                //console.log(new_response);
                            }
                        }catch(e){
                            console.log("[XHR STATECHANGE] Exception: " + e);
                        }
                    };
                    return output;
                }
            }
        });
    });

});

function updateSRP(salesid,newsrp,srp,qty)
{
    BootstrapDialog.show({
    	title: 'Confirmation',
        message: 'Update '+srp+' to '+newsrp+'?',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        onshow: function(dialog) {
            flag = 1;
        },
        onshown:function(dialog){
           
        },
        onhidden:function(dialog)
        {
        	flag = 0;
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Ok',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself){

            	$button = this;

            	$button.disable();

				$.ajax({
					url:siteurl+'transaction/updateSRPSales',
					data:{salesid:salesid,newsrp:newsrp,srp:srp},
					type:'POST',			
					beforeSend:function(){									
					},
					success:function(data){	
						// alert(response);
						console.log(data);

						var data = JSON.parse(data);
						if(data['st'])
						{				
							var totsales = $('span._totsales').text();
							totsales = totsales.replace(/,/g , "");	
							console.log("totsales:"+totsales);
							console.log(srp);
							totsales = parseFloat(totsales) - parseFloat(srp);
							console.log("minus srp "+totsales);
							if(data['uom']=='load')
							{
								console.log("load");
								$('tr.list-'+salesid).attr('data-srp',newsrp);
								$('tr.list-'+salesid+' td.salestotal').text(newsrp);
								totsales = parseFloat(totsales) + parseFloat(newsrp);
								console.log("add new srp: "+totsales);
							}
							else 
							{
								$('tr.list-'+salesid).attr('data-srp',newsrp);
								totsales = parseFloat(qty) * parseFloat(newsrp);
							}

							$('span._totsales').text(totsales.toFixed(2));
							BootstrapDialog.closeAll();	
							alert('Updated!');
						}
						else 
						{
							BootstrapDialog.closeAll();	
							$('table tr.list-'+salesid+' td.salessrp').text(srp);
							alert('Something went wrong.');
						}
					}
				});
            }
        }, {
        	icon: 'glyphicon glyphicon-remove-sign',
            label: 'Cancel',
            action: function(dialogItself){
                dialogItself.close();
				$('table tr.list-'+salesid+' td.salessrp').text(srp);
            }
        }]
    });   
}

function updateNET(salesid,newnet,net,qty)
{
    BootstrapDialog.show({
    	title: 'Confirmation',
        message: 'Update '+net+' to '+newnet+'?',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        onshow: function(dialog) {
            flag = 1;
        },
        onshown:function(dialog){
           
        },
        onhidden:function(dialog)
        {
        	flag = 0;
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Ok',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself){

            	$button = this;

            	$button.disable();

				$.ajax({
					url:siteurl+'transaction/updateNetSales',
					data:{salesid:salesid,newnet:newnet,net:net},
					type:'POST',			
					beforeSend:function(){									
					},
					success:function(data){	
						// alert(response);
						console.log(data);

						var data = JSON.parse(data);
						if(data['st'])
						{				
							if(data['uom']=='load')
							{
								$('tr.list-'+salesid).attr('data-qty',newnet);
								$('tr.list-'+salesid).attr('data-net',newnet);
								$('table tr.list-'+salesid+' td.salesqty').text(newnet);
							}
							
							BootstrapDialog.closeAll();	
							alert('Updated!');

						}
						else 
						{
							BootstrapDialog.closeAll();	
							$('table tr.list-'+salesid+' td.salesnet').text(net);
							alert('Something went wrong.');
						}
					}
				});
            }
        }, {
        	icon: 'glyphicon glyphicon-remove-sign',
            label: 'Cancel',
            action: function(dialogItself){
                dialogItself.close();
				$('table tr.list-'+salesid+' td.salesnet').text(net);
            }
        }]
    });  
}

function changeUserPassword(id)
{
    BootstrapDialog.show({
        title: '<i class="fa fa-exchange" aria-hidden="true"></i> Change Password',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        message: function(dialog) {
            var $message = $("<div><img src='"+siteurl+"assets/img/ajax.gif'><small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
                $message.load(pageToLoad); 
            },1000);
          return $message;
        },
        data: {
            'pageToLoad': siteurl+'user/changeUserPasswordDialog/'+id,
        },
        cssClass: 'changepass',             
        onshown: function(dialogRef){
               
        },
        onhidden: function(dialogRef){                  
            flag=0;
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok ',
            label: '  Submit',
            cssClass: 'btn-primary',
            hotkey: 13, // Enter.
            action: function(dialogItself) {  
            	$buttons = this;
            	$buttons.disable();

            	$('.response').html("");
            	 var formURL = $('form#_changeUserPassword').attr('action'), formData = $('form#_changeUserPassword').serialize();

            	var password = $('#password').val();
            	var userid = $('#userid').val();
            	

                if(password.trim()=="")
                {
                	$buttons.enable();
                	$('.response').html('<div class="alert alert-danger alert-res">Please input password.</div>');
                	return false;
                }         	

                if(userid.trim()=="")
                {
                	$buttons.enable();
                	$('.response').html('<div class="alert alert-danger alert-res">Please input password.</div>');
                	return false;
                }  		                

                if(password.trim().length <= 4)
                {
                	$buttons.enable();
                	$('.response').html('<div class="alert alert-danger alert-res">Invalid Password.</div>');
                	return false;
                }  

		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Change User Password?',
		            closable: true,
		            closeByBackdrop: false,
		            closeByKeyboard: true,
		            onshow: function(dialog) {
		                // dialog.getButton('button-c').disable();
		            },
		            onshown:function(dialog){
		                restrictback=0;
		            },
		            buttons: [{
		                icon: 'glyphicon glyphicon-ok-sign',
		                label: 'Yes',
		                cssClass: 'btn-primary',
		                hotkey: 13,
		                action:function(dialogItself1){	
		                	$buttons1 = this;		
		                	$buttons1.disable();	 

							$.ajax({
					    		url:formURL,
					    		type:'POST',
					    		data:formData,
								beforeSend:function(){
									//$('#processing-modal').modal('show');
								},
								success:function(data){
									console.log(data);
									var data = JSON.parse(data);
									
									if(data['st'])
									{
										dialogItself.close();
										dialogItself1.close();
										var dialog = new BootstrapDialog({
							            message: function(dialogRef){
							            var $message = $('<div>User password successfully changed.</div>');			        
							                return $message;
							            },
							            closable: false
								        });
								        dialog.realize();
								        dialog.getModalHeader().hide();
								        dialog.getModalFooter().hide();
								        dialog.getModalBody().css('background-color', '#0088cc');
								        dialog.getModalBody().css('color', '#fff');
								        dialog.open();
					               		setTimeout(function(){
					                    	window.location = siteurl+'user/manageusers';
					               		}, 1700);											
									}
									else 
									{
										dialogItself1.close();
										$('.response').html('<div class="alert alert-danger alert-res">'+data['msg']+'</div>');
										$buttons.enable();
									}
								}
							});
		                }
		            }, {
		            	icon: 'glyphicon glyphicon-remove-sign',
		                label: 'Cancel',
		                action: function(dialogItself){
		                    dialogItself.close();
		                    $buttons.enable();
		                }
		            }]
		        });
            }            
        }, {
        icon: 'glyphicon glyphicon-remove-sign',
        label: ' Cancel',
        cssClass: 'btn-default',
        action: function(dialogItself){
            dialogItself.close();
        }
        }]
    });
}

function editUser(id)
{
    BootstrapDialog.show({
        title: '<i class="fa fa-edit" aria-hidden="true"></i> Edit User',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        message: function(dialog) {
            var $message = $("<div><img src='"+siteurl+"assets/img/ajax.gif'><small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
                $message.load(pageToLoad); 
            },1000);
          return $message;
        },
        data: {
            'pageToLoad': siteurl+'user/addnewuser/1/'+id,
        },
        cssClass: 'gcrevalidate',             
        onshown: function(dialogRef){
               
        },
        onhidden: function(dialogRef){                  
            flag=0;
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok ',
            label: '  Submit',
            cssClass: 'btn-primary',
            hotkey: 13, // Enter.
            action: function(dialogItself) {  
            	$buttons = this;
            	$buttons.disable();
            	$('.response').html('');
                var formURL = $('form#_updateUser').attr('action'), formData = $('form#_updateUser').serialize();
                var username = $('#username').val();
                var fullname = $('#fullname').val();
                var bunit = $('#bunit').val();
                var department = $('#department').val();
                var idnumber = $('#idnumber').val();

                if(username.trim()==undefined)
                {
                	$buttons.enable();
                	return false;
                }

                if(username.trim()=="")
                {
                	$buttons.enable();
                	$('.response').html('<div class="alert alert-danger alert-res">Please input username.</div>');
                	return false;
                }

                if(fullname.trim()=="")
                {
                	$buttons.enable();
                	$('.response').html('<div class="alert alert-danger alert-res">Please input fullname.</div>');
                	return false;
                }	

                if(department.trim()=="")
                {
                	$buttons.enable();
                	$('.response').html('<div class="alert alert-danger alert-res">Please select department.</div>');
                	return false;
                }

                if(department.trim()=='retail store' && idnumber.trim()=="")
                {
                	$buttons.enable();
                	$('.response').html('<div class="alert alert-danger alert-res">Please input idnumber.</div>');
                	return false;
                }

                if(department.trim()=='retail store' && idnumber.trim()=="")
                {
                	$buttons.enable();
                	$('.response').html('<div class="alert alert-danger alert-res">Please input idnumber.</div>');
                	return false;
                }

                if(bunit.trim()=="")
                {
                	if(department.trim()!="admin")
                	{
	                	$buttons.enable();
	                	$('.response').html('<div class="alert alert-danger alert-res">Please select business unit.</div>');
	                	return false;	                		
                	}
                }

		        BootstrapDialog.show({
		        	title: 'Confirmation',
		            message: 'Update User?',
		            closable: true,
		            closeByBackdrop: false,
		            closeByKeyboard: true,
		            onshow: function(dialog) {
		                // dialog.getButton('button-c').disable();
		            },
		            onshown:function(dialog){
		                restrictback=0;
		            },
		            buttons: [{
		                icon: 'glyphicon glyphicon-ok-sign',
		                label: 'Yes',
		                cssClass: 'btn-primary',
		                hotkey: 13,
		                action:function(dialogItself1){	
		                	$buttons1 = this;		
		                	$buttons1.disable();	            	
							$.ajax({
					    		url:formURL,
					    		type:'POST',
					    		data:formData,
								beforeSend:function(){
									//$('#processing-modal').modal('show');
								},
								success:function(data){
									console.log(data);
									var data = JSON.parse(data);
									
									if(data['st'])
									{
										dialogItself.close();
										dialogItself1.close();
										var dialog = new BootstrapDialog({
							            message: function(dialogRef){
							            var $message = $('<div>User successfully updated.</div>');			        
							                return $message;
							            },
							            closable: false
								        });
								        dialog.realize();
								        dialog.getModalHeader().hide();
								        dialog.getModalFooter().hide();
								        dialog.getModalBody().css('background-color', '#0088cc');
								        dialog.getModalBody().css('color', '#fff');
								        dialog.open();
					               		setTimeout(function(){
					                    	window.location = siteurl+'user/manageusers';
					               		}, 1700);											
									}
									else 
									{
										dialogItself1.close();
										$('.response').html('<div class="alert alert-danger alert-res">'+data['msg']+'</div>');
										$buttons.enable();
									}
								}
							});
		                }
		            }, {
		            	icon: 'glyphicon glyphicon-remove-sign',
		                label: 'Cancel',
		                action: function(dialogItself){
		                    dialogItself.close();
		                    $buttons.enable();
		                }
		            }]
		        });
            }            
        }, {
        icon: 'glyphicon glyphicon-remove-sign',
        label: ' Cancel',
        cssClass: 'btn-default',
        action: function(dialogItself){
            dialogItself.close();
        }
        }]
    });
}

function editItem(id)
{
    BootstrapDialog.show({
        title: '<i class="fa fa-edit" aria-hidden="true"></i> Edit Item',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        message: function(dialog) {
            var $message = $("<div><img src='"+siteurl+"assets/img/ajax.gif'><small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
                $message.load(pageToLoad); 
            },1000);
          return $message;
        },
        data: {
            'pageToLoad': siteurl+'item/addedNewItemDialog/1/'+id,
        },
        cssClass: 'gcrevalidate',             
        onshown: function(dialogRef){
               
        },
        onhidden: function(dialogRef){                  
            flag=0;
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok ',
            label: '  Submit',
            cssClass: 'btn-primary',
            hotkey: 13, // Enter.
            action: function(dialogItself) {  
            	$buttons = this;
                $buttons.disable();
                $('.response').html('');

                var formURL = $('form#_updateItem').attr('action'), formData = $('form#_updateItem').serialize();
                var itemname = $('#itemname').val();
                var itemtype = $('#itemtypeadd').val();
                var srp = $('#srp').val();
                var netprice = $('#netprice').val();
                var faditemcode = $('#faditemcode').val();       

                if(itemname.trim()==undefined)
                {
                    $buttons.enable();
                    return false;
                }

                if(itemname.trim()=="")
                {
                    $buttons.enable();
                    $('.response').html('<div class="alert alert-danger alert-res">Please input item name.</div>');
                    return false;
                }

                if(itemtype.trim()=="")
                {
                    $buttons.enable();
                    $('.response').html('<div class="alert alert-danger alert-res">Please select item type.</div>');
                    return false;
                }	  

                if(itemtype.trim()!='1')
                {
                    if(srp.trim()=='0.00' || srp=="0")
                    {
                        $buttons.enable();
                        $('.response').html('<div class="alert alert-danger alert-res">Please input srp.</div>');	                		
                        return false;
                    }

                    if(netprice.trim()=='0.00' || netprice=="0")
                    {
                        $buttons.enable();
                        $('.response').html('<div class="alert alert-danger alert-res">Please input netprice.</div>');	                		
                        return false;
                    }
                }

                BootstrapDialog.show({
                    title: 'Confirmation',
                    message: 'Update Item?',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: true,
                    onshow: function(dialog) {
                        // dialog.getButton('button-c').disable();
                    },
                    onshown:function(dialog){
                        restrictback=0;
                    },
                    onhidden: function(dialog) {
                        
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-ok-sign',
                        label: 'Yes',
                        cssClass: 'btn-primary',
                        hotkey: 13,
                        action:function(dialogItself1){	
                            $buttons1 = this;		
                            $buttons1.disable();	            	
                            $.ajax({
                                url:formURL,
                                type:'POST',
                                data:formData,
                                beforeSend:function(){
                                    //$('#processing-modal').modal('show');
                                },
                                success:function(data){
                                    console.log(data);
                                    var data = JSON.parse(data);
                                    
                                    if(data['st'])
                                    {
                                        dialogItself.close();
                                        dialogItself1.close();
                                        var dialog = new BootstrapDialog({
                                        message: function(dialogRef){
                                        var $message = $('<div>Item successfully updated.</div>');			        
                                            return $message;
                                        },
                                        closable: false
                                        });
                                        dialog.realize();
                                        dialog.getModalHeader().hide();
                                        dialog.getModalFooter().hide();
                                        dialog.getModalBody().css('background-color', '#0088cc');
                                        dialog.getModalBody().css('color', '#fff');
                                        dialog.open();
                                           setTimeout(function(){
                                            window.location = siteurl+'item/manageitems';
                                           }, 1700);											
                                    }
                                    else 
                                    {
                                        dialogItself1.close();
                                        $('.response').html('<div class="alert alert-danger alert-res">'+data['msg']+'</div>');
                                        $buttons.enable();
                                    }
                                }
                            });
                        }
                    }, {
                        icon: 'glyphicon glyphicon-remove-sign',
                        label: 'Cancel',
                        action: function(dialogItself){
                            dialogItself.close();
                            $buttons.enable();
                        }
                    }]
                });   

            }            
        }, {
        icon: 'glyphicon glyphicon-remove-sign',
        label: ' Cancel',
        cssClass: 'btn-default',
        action: function(dialogItself){
            dialogItself.close();
        }
        }]
    });
}

function simStatus(id,st)
{
    var msg = "";
    if(st=='active')
    {
        msg = "inactive";
    }
    else 
    {
        msg = "active";
    }

    BootstrapDialog.show({
        title: 'Confirmation',
        message: 'Set as '+msg,
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onshown:function(dialog){
            restrictback=0;
        },
        onhidden: function(dialog) {
            
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Yes',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself1){	
                dialogItself1.close();
                $('#processing-modal').modal('show');
                $.ajax({
                    url:siteurl+'item/updateSimcardStatus',
                    type:'POST',
                    data:{ id:id, st:st},
                    beforeSend:function(){
                        //$('#processing-modal').modal('show');
                    },
                    success:function(data){
                        console.log(data);
                        var data = JSON.parse(data);
                        
                        if(data['st'])
                        {
                            $('#processing-modal').modal('hide');
                            var dialog = new BootstrapDialog({
                            message: function(dialogRef){
                            var $message = $('<div>Sim Card successfully updated.</div>');			        
                                return $message;
                            },
                            closable: false
                            });
                            dialog.realize();
                            dialog.getModalHeader().hide();
                            dialog.getModalFooter().hide();
                            dialog.getModalBody().css('background-color', '#0088cc');
                            dialog.getModalBody().css('color', '#fff');
                            dialog.open();
                               setTimeout(function(){
                                window.location = siteurl+'item/managesimcard';
                               }, 1700);											
                        }
                        else 
                        {
                            $('#processing-modal').modal('hide');
                            setTimeout(function(){
                                alert(data['msg']);
                            },300)                            
                            dialogItself.enableButtons(true);
                            dialogItself.setClosable(true);
                        }
                    }
                });

            }
        }, {
            icon: 'glyphicon glyphicon-remove-sign',
            label: 'Cancel',
            action: function(dialogItself){
                dialogItself.close();
                $buttons.enable();
            }
        }]
    });  

    return false;
}


function updateReleasedGC()
{
    BootstrapDialog.show({
    	title: 'Confirmation',
        message: 'Update Released GC?',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
            $('#updateReleasedGC').prop("disabled",true);
        },
        onhidden:function(dialog){
        	$('#updateReleasedGC').prop("disabled",false);
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Yes',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself){
            	
            	$buttons = this;
            	$buttons.disable();                	
            	dialogItself.close();
				$.ajax({
		    		url:siteurl+'transaction/updateReleasedGC',
					beforeSend:function(){
						$('#processing-modal').modal('show');
					},
					success:function(data){
						console.log(data);
						var data = JSON.parse(data);
						$('#processing-modal').modal('hide');
						if(data['st'])
						{
							dialogItself.close();
							var dialog = new BootstrapDialog({
				            message: function(dialogRef){
				            var $message = $('<div>Released GC Updated.</div>');			        
				                return $message;
				            },
				            closable: false
					        });
					        dialog.realize();
					        dialog.getModalHeader().hide();
					        dialog.getModalFooter().hide();
					        dialog.getModalBody().css('background-color', '#0088cc');
					        dialog.getModalBody().css('color', '#fff');
					        dialog.open();
		               		setTimeout(function(){
		                    	window.location = siteurl;
		               		}, 1700);											
						}
						else 
						{
							alert(data['msg']);
							$buttons.enable();
						}
					}
				});

            }
        }, {
        	icon: 'glyphicon glyphicon-remove-sign',
            label: 'No',
            action: function(dialogItself){
                dialogItself.close();
            }
        }]
    });
}

function updateUserList()
{
    BootstrapDialog.show({
    	title: 'Confirmation',
        message: 'Update User List?',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
            $('#updateUserList').prop("disabled",true);
        },
        onhidden:function(dialog){
        	$('#updateUserList').prop("disabled",false);
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Yes',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself){
            	
            	$buttons = this;
            	$buttons.disable();                	
            	dialogItself.close();
				$.ajax({
		    		url:siteurl+'transaction/updateUserListServerToStore',
					beforeSend:function(){
						$('#processing-modal').modal('show');
					},
					success:function(data){
						console.log(data);
						var data = JSON.parse(data);
						$('#processing-modal').modal('hide');
						if(data['st'])
						{
							dialogItself.close();
							var dialog = new BootstrapDialog({
				            message: function(dialogRef){
				            var $message = $('<div>User List Successfully Updated.</div>');			        
				                return $message;
				            },
				            closable: false
					        });
					        dialog.realize();
					        dialog.getModalHeader().hide();
					        dialog.getModalFooter().hide();
					        dialog.getModalBody().css('background-color', '#0088cc');
					        dialog.getModalBody().css('color', '#fff');
					        dialog.open();
		               		setTimeout(function(){
		                    	window.location = siteurl;
		               		}, 1700);											
						}
						else 
						{
							alert(data['msg']);
							$buttons.enable();
						}
					}
				});

            }
        }, {
        	icon: 'glyphicon glyphicon-remove-sign',
            label: 'No',
            action: function(dialogItself){
                dialogItself.close();
            }
        }]
    });
}

function updateServerPendingGCRequest()
{
    BootstrapDialog.show({
    	title: 'Confirmation',
        message: 'Update Server?',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
            $('#updateServerBut').prop("disabled",true);
        },
        onhidden:function(dialog){
        	$('#updateServerBut').prop("disabled",false);
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Yes',
            cssClass: 'btn-primary',
            hotkey: 13,
            action:function(dialogItself){
            	
            	$buttons = this;
            	$buttons.disable();                	
            	dialogItself.close();
					$.ajax({
			    		url:siteurl+'transaction/updateGCRequestMainServer',
						beforeSend:function(){
							$('#processing-modal').modal('show');
						},
						success:function(data){
							console.log(data);
							var data = JSON.parse(data);
							$('#processing-modal').modal('hide');
							if(data['st'])
							{
								dialogItself.close();
								var dialog = new BootstrapDialog({
					            message: function(dialogRef){
					            var $message = $('<div>Main Server Updated.</div>');			        
					                return $message;
					            },
					            closable: false
						        });
						        dialog.realize();
						        dialog.getModalHeader().hide();
						        dialog.getModalFooter().hide();
						        dialog.getModalBody().css('background-color', '#0088cc');
						        dialog.getModalBody().css('color', '#fff');
						        dialog.open();
			               		setTimeout(function(){
			                    	window.location = siteurl;
			               		}, 1700);											
							}
							else 
							{
								alert(data['msg']);
								$buttons.enable();
							}
						}
					});
            }
        }, {
        	icon: 'glyphicon glyphicon-remove-sign',
            label: 'No',
            action: function(dialogItself){
                dialogItself.close();
            }
        }]
    });
}

function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function convertToNumber(num)
{
    newnum = num.replace(/,/g , "");

    newnum = parseFloat(newnum);

    newnum = isNaN(newnum) ? 0 : newnum;
    return newnum;
}

function convertToSqlDate(dValue)
{
    //m - d y
    dValue = dValue.split('/');
    return dValue[2]+'-'+dValue[0]+'-'+dValue[1];
}

function randompass()
{
    var num = Math.floor(Math.random() * 90000) + 10000;
    return num;
}

function checksession()
{
    setInterval(function() {
        $.ajax({
            url: siteurl+'transaction/checksession1',
            success:function(data)
            {
                var data = JSON.parse(data);
                if(!data['st'])
                {
                    BootstrapDialog.closeAll();
                    var dialog = new BootstrapDialog({
                    message: function(dialogRef){
                    var $message = $('<div>Session already expired, Logging out...</div>');                 
                        return $message;
                    },
                    closable: false
                    });
                    dialog.realize();
                    dialog.getModalHeader().hide();
                    dialog.getModalFooter().hide();
                    dialog.getModalBody().css('background-color', '#0088cc');
                    dialog.getModalBody().css('color', '#fff');
                    dialog.open();
                    setTimeout(function(){
                        window.location.href =siteurl+'home/login';
                    }, 1500);    
                    //$('.responsechangepass').html('<div class="alert alert-danger alert-no-bot alertpad8">'+data['msg']+'</div>');
                }
            }
        });
    },40000); // 60000 milliseconds = one minute
    
}

function changeUsername()
{

}

function changePassword()
{
    
}