
flag = 0;
mode = 0;
modemanage = 0; 
siteurl = "";

checksession();
$(function(){
    siteurl = $('#siteurl').val();   

    var url = document.URL;
    var to = url.split('/');
    if(to[to.length-2]=='endofday')
    {
        // display report dialog`1b 
        var trnum = to[to.length-1];
        if(trnum.trim()!='')
        {
            BootstrapDialog.show({
                title: 'End of Day Report',
                closable: true,
                closeByBackdrop: false,
                closeByKeyboard: true,
                message: function(dialog) {
                    var $message = $("<div><img src='"+siteurl+"assets/img/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
                    var pageToLoad = dialog.getData('pageToLoad');
                    setTimeout(function(){
                        $message.load(pageToLoad); 
                    },1000);
                    return $message;
                },
                data: {
                    'pageToLoad': siteurl+'reports/displayPDF',
                },
                cssClass: 'pdfshowModal',           
                    onshown: function(dialogRef){                   
                },
                onhidden: function(dialogRef){ 
                    window.location.replace(siteurl+'home/cashiermain'); 

                },
                buttons: [{
                    icon: 'glyphicon glyphicon-print',
                    label: ' Print',
                    cssClass: 'btn-default printbut',
                    action: function(dialogItself){
                        callPrint('iframeId');
                    }
                },{
                    icon: 'glyphicon glyphicon-remove-sign',
                    label: ' Close',
                    cssClass: 'btn-default',
                    action: function(dialogItself){                    
                    window.location.replace(siteurl+'home/cashiermain'); 

                    dialogItself.close();
                      // window.location = '../cashiering';
                    }   
                }]
            });
        }
    }
    else if(to[to.length-2]=='terminalreport')
    {
            BootstrapDialog.show({
                title: 'Terminal Report',
                closable: true,
                closeByBackdrop: false,
                closeByKeyboard: true,
                message: function(dialog) {
                    var $message = $("<div><img src='"+siteurl+"assets/img/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
                    var pageToLoad = dialog.getData('pageToLoad');
                    setTimeout(function(){
                        $message.load(pageToLoad); 
                    },1000);
                    return $message;
                },
                data: {
                    'pageToLoad': siteurl+'reports/displayPDFTerminalReport',
                },
                cssClass: 'pdfshowModal',           
                    onshown: function(dialogRef){                   
                },
                onhidden: function(dialogRef){ 
                    window.location.replace(siteurl+'home/cashiermain'); 

                },
                buttons: [{
                    icon: 'glyphicon glyphicon-print',
                    label: ' Print',
                    cssClass: 'btn-default printbut',
                    action: function(dialogItself){
                        callPrint('iframeId');
                    }
                },{
                    icon: 'glyphicon glyphicon-remove-sign',
                    label: ' Close',
                    cssClass: 'btn-default',
                    action: function(dialogItself){                    
                    window.location.replace(siteurl+'home/cashiermain'); 

                    dialogItself.close();
                      // window.location = '../cashiering';
                    }   
                }]
            });
    }
    //x =  url.substring(0,to);


    //window.alert(this.href.substr(this.href.lastIndexOf('/') + 1));

    $('#numOnly, #numOnlyreval').inputmask();
    $("[name='data']").on('keypress', function (event) {
      if(event.which === 13){
        $('input.msgsales').val("");
        var value = this.value;
        $.ajax({
          type : "POST",
          url  : "../ajax-cashier.php?request=check",
          data : { value : value },
          beforeSend:function(){          
          },
          success : function(data){
            var data = JSON.parse(data);
            if(data['st'])
            {
              $("[name='data']").select();       
              $('div.items .receipt-items').load('../ajax-cashier.php?request=receipt');    
              $('._barcodes').load('../ajax-cashier.php?request=load');              
              $.ajax({
                type:"POST",
                url:"../ajax-cashier.php?request=totals",
                success:function(data)
                {
                    console.log(data);
                    var data = JSON.parse(data);
                    $('.sbtotal').val(data['sbtotal']);
                    $('._cashier_total').val(data['amtdue']);
                    $('.linediscount').val(data['linedisc']);
                    $('.docdiscount').val(data['docdiscount']);
                    $('.noitems').val(data['noitems']); 
                    $('.cdisc').val('0.00');
                }
              });
            }
            else 
            {
              flag=1;
              BootstrapDialog.show({
                  title:'Warning',
                  message: '<div class="dialog-alert">'+data['msg']+'</div>',
                  onhidden: function(dialogRef){ 
                    flag = 0;                
                    $("[name='data']").focus();         
                  }
              });
              $("[name='data']").val('');
            }
          } 
        });  
       }
    });

    $("[name='revalidategc']").on('keypress', function (event) {   
       if(event.which === 13){        
          $('.msgreval').val('');
          var value = this.value;
          $.ajax({
            type : "POST",
            url  : "../ajax-cashier.php?request=scanrevalidate",
            data : { value : value },
            beforeSend:function(){          
            },
            success:function(data){
              console.log(data)
              var data = JSON.parse(data);
              if(data['st'])
              {
                $("[name='revalidategc']").val('');
                $('._barcodesreval').load('../ajax-cashier.php?request=loadreval');
                $('input.inp-amtdue._cashier_totalreval').val(data['total']);
                $('input.noitemsreval').val(data['count']);
              }
              else 
              {
                flag=1;
                BootstrapDialog.show({
                    title:'Warning',
                    message: data['msg'],
                    onhidden: function(dialogRef){ 
                      flag = 0;                
                      $("[name='revalidategc']").focus();         
                    }
                });
                $("[name='revalidategc']").val('');
              }
            }
          });
       }

    });

    $("[name='inprefundgc']").on('keypress', function (event) {
      if(event.which === 13)
      {        
        $('.msgrefund').val('');
        var value = this.value;
        $.ajax({
            type : "POST",
            url  : "../ajax-cashier.php?request=scanrefund",
            data : { value : value },
            beforeSend:function(){          
            },
            success:function(data){
              console.log(data)
              var data = JSON.parse(data);
              if(data['st'])
              {                
                $("[name='inprefundgc']").val('');
                $('._barcodesrefund').load('../ajax-cashier.php?request=loadrefund');
                $('input.totdenomref').val(addCommas(data['reftotdenom']));
                $('input.totsubdiscref').val(data['refsub']);
                $('input.totlinedisref').val(data['refline']);
                $('input.noitemsref').val(data['refcnt']);
                $('input.serviceref').val(data['scharge']);
                
                $('input._cashier_totalrefund').val(addCommas(data['refamtdue'].toFixed(2)));
                //$('input.inp-amtdue._cashier_totalreval').val(data['total']);
                // $('input.noitemsreval').val(data['count']);
              }
              else 
              {
                flag=1;
                BootstrapDialog.show({
                    title:'Warning',
                    message: data['msg'],
                    onhidden: function(dialogRef){ 
                      flag = 0;                
                      $("[name='inprefundgc']").focus();         
                    }
                });
                $("[name='inprefundgc']").val('');
              }
            }
        });
      }
    });

    
});

function init() {
    // var flag=0;

    $("div.item-list").on("blur", "table.tablef tbody._loadtempsalesitems tr td button.btnside", function() {
        $(this).closest('tr').css('background-color','white');
    });

    $("div.item-list").on("focus", "table.tablef tbody._loadtempsalesitems tr td button.btnside", function() {
        $(this).closest('tr').css('background-color','yellow');
    });

    $("div.item-list").on("blur", "table.tablef tbody._loadtempsrecitems tr td button.btnside", function() {
        $(this).closest('tr').css('background-color','white');
    });

    $("div.item-list").on("focus", "table.tablef tbody._loadtempsrecitems tr td button.btnside", function() {
        $(this).closest('tr').css('background-color','yellow');
    });    

    shortcut.add("DOWN",function() {
        if ($("div.item-list table tbody._loadtempsalesitems tr td button.btnside").is(":focus") || $("div.item-list table tbody._loadtempsrecitems tr td button.btnside").is(":focus")) 
        {
            c=""
            c = currf.closest('tr').next().find('button.btnside:eq(' + currf.index() + ')');
            // If we didn't hit a boundary, update the current cell
            if (c.length > 0) {
                currf = c;
                currf.focus();
            }
        }
    });

    shortcut.add("UP",function() {
        if ($("div.item-list table tbody._loadtempsalesitems tr td button.btnside").is(":focus") || $("div.item-list table tbody._loadtempsrecitems tr td button.btnside").is(":focus")) 
        {
            c=""
            c = currf.closest('tr').prev().find('button.btnside:eq(' + currf.index() + ')');
            // If we didn't hit a boundary, update the current cell
            if (c.length > 0) {
                currf = c;
                currf.focus();
            }
        }    
    });

    shortcut.add("ESC",function() {
        if ($("div.item-list table.tablef tr td button.btnside").is(":focus")) 
        {
            $('input#numOnly').focus();
        }

        if($("div.item-list table tbody._barcodesreval tr td button.btnside").is(":focus"))
        {
            $('input#numOnlyreval').focus();
        }

        if($("div.item-list table tbody._barcodesrefund tr td button.btnside").is(":focus"))
        {
            $('input#numOnlyreturn').focus();
        }

    });
    shortcut.add("F1",function() {
        f1();       
    });
    shortcut.add("F2",function() {
        f2();
    });
    shortcut.add("F3",function() {
        f3(); 
    });
    shortcut.add("F4",function() {
        f4();
    });
    shortcut.add("F5",function() {
        f5();
    });
    shortcut.add("F6",function() {
        f6();
    });
    shortcut.add("F7",function() {
        f7();
    });
    shortcut.add("F8",function() {
        f8();
    });
        shortcut.add("F9",function() {
        var win = window.open('', '_self');
        win.close();
    });
    shortcut.add("F10",function() {
        var time = $('span#time').text();
        alert(time);
    });
}

window.onload=init;

//mode 0 - main menu

//mode 1 - add item sales

//mode 2 - cash payment menu

//mode 3 - receiving 

//mode 4 - reports

//mode 5 - user account

function f1()
{
    if($('#managerkey').is(':checked'))
    {
    }
    else 
    {
        if(mode==0)
        {
            if(flag==0)
            {
                $('.cashier-main').hide();
                $('.cashier-sales').show();
                $('.content-main').hide();
                $('.content-eloadsales').show();

                mode = 1;
                unsetTableItemsSales();
            }
        }
        else if(mode==1)
        {
            if(flag==0)                
            {
                itemSalesAddItem();                
            }
        }
        else if(mode==2)
        {
            //check if table is not empty

            if(flag==0)
            {
                paymentCash();
            }
        }
        else if(mode==3)
        {
            if(flag==0)
            {
                itemRecAddItem();
            }
        }
        else if(mode==4)
        {
            if(flag==0)
            {
                generateTerminalReport();
            }
        }
        else if(mode==5)
        {
            if(flag==0)
            {
                changeUsername();
            }
        }
        else if(mode==6)
        {
            if(flag==0)
            {
                loadtransfer();
            }
        }

        //end elseif
    }

    //end if
}


function f2()
{ 
    if($('#managerkey').is(':checked'))
    {
    }
    else 
    {
        if(mode==0)
        {
            if(flag==0)
            {
                $('.cashier-main').hide();
                $('.cashier-receiving').show();
                $('.content-main').hide();
                $('.content-receiving').show();

                mode = 3;

                unsetTableItemsRec();

                getReceivingNumber();

                $('.msgsalesrec').val("");  
                $('.sinumbers').val("");  
                $('.ponumber').val("");   
                $('.refnumber').val(""); 
                $('.checkedby').val("");
            }

        }
        else if(mode==1)
        {
            if(flag==0)
            {
                voidLineSales();
            }
            
        }
        else if(mode==2)
        {

        }
        else if(mode==3)
        {
            if(flag==0)
            {
                voidLineReceiving();
            }
        }
        else if(mode==4)
        {
            if(flag==0)
            {
                cashierReport();
            }
        }
        else if(mode==5)
        {
            if(flag==0)
            {
                changepassword();
            }

        }
        else if(mode==6)
        {
            if(flag==0)
            {
                $('.cashier-main').show();
                $('.cashier-others').hide();
                mode = 0;
            }
        }
        //end elseif
    }
}

function f3()
{
    if($('#managerkey').is(':checked'))
    {

    }
    else 
    {
        if(mode==5)
        {
            if(flag==0)
            {                
                $('.cashier-main').show();
                $('.cashier-account').hide();
                mode = 0;
            }
        }
    }
}

function f4()
{
    if($('#managerkey').is(':checked'))
    {
    }
    else 
    {
        if(mode==0)
        {

        }
        else if(mode==1)
        {
            if(flag==0)
            {
                $('.cashier-sales').hide();
                $('.cashier-paymentmode').show();
                mode = 2;
            }
        }
        else if(mode==2)
        {

        }
        else if (mode==3) 
        {
            if(flag==0)
            {
                saveReceiving();
            }
        }
        else if (mode==4)
        {
            if(flag==0)
            {
                endofday1();
            }
            
        }
    }
}


function f5()
{
    if($('#managerkey').is(':checked'))
    {
    }
    else 
    {
        if(mode==0)
        {
            if(flag==0)
            {
                $('.cashier-main').hide();
                $('.cashier-others').show();

                mode = 6
            }
        }
        else if(mode==1)
        {

            if(flag==0)
            {
                $('.cashier-main').show();
                $('.cashier-sales').hide();
                $('.content-main').show();
                $('.content-eloadsales').hide();

                unsetTableItemsSales();

                mode = 0;
            }
        }
        else if(mode==2)
        {
            if(flag==0)
            {
                $('.cashier-paymentmode').hide();
                $('.cashier-sales').show();
                mode = 1;
            }
        }
        else if(mode==3)
        {
            if(flag==0)
            {
                $('.cashier-receiving').hide();
                $('.cashier-main').show();
                $('.content-main').show();
                $('.content-receiving').hide();
                mode = 0;

                unsetTableItemsRec();

                //get receiving number

            }
        }
        else if(mode==4)
        {
            if(flag==0)
            {
                $('.cashier-reports').hide();
                $('.cashier-main').show();
                mode = 0;
            }
        }
    }
}

function f6()
{
    if($('#managerkey').is(':checked'))
    {

    }
    else 
    {
        if(mode==0)
        {
            if(flag==0)
            {
                $('.cashier-reports').show();
                $('.cashier-main').hide();
                mode = 4;
            }
        }
    }
}

function f7()
{
    if($('#managerkey').is(':checked'))
    {
        if(modemanager==0)
        {
            
        }
    //return  false;
    }
    else 
    {
        if(mode==0)
        {
            if(flag==0)
            {
                $('.cashier-account').show();
                $('.cashier-main').hide();
                mode = 5;
            }
        } 
    }
}

function f8()
{
    if($('#managerkey').is(':checked'))
    {
        if(modemanager==0)
        {
            supervisorlogout();
        }
    //return  false;
    }
    else 
    {
        if(mode==0)
        {
          logoutuser();
        } 
    }
}

function changepassword()
{
    flag = 1;
    BootstrapDialog.show({
        title: 'Change Password',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        message: function(dialog) {
            var $message = $("<div><img src='"+siteurl+"assets/img/ajax-loader.gif'><small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
                $message.load(pageToLoad); 
            },1000);
          return $message;
        },
        data: {
            'pageToLoad': siteurl+'user/changePasswordDialog',
        },
        cssClass: 'changeaccountpass',
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onhidden: function(dialogRef){
            $('#numOnly').focus();
            flag=0;
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Yes',
            cssClass: 'btn-success',
            hotkey: 13,
            action:function(dialogItself){   
                $('.response').html('');
                var $button = this;
                $button.disable();
                var postData = $('#_changepassword').serialize();
                var formURL = $('#_changepassword').attr("action");

                if($('input[name=opassword]').val()==undefined)
                {
                    $('input[name=opassword]').focus();
                    $button.enable();  
                    return false;
                }                

                if($('input[name=opassword]').val().trim()=="" || $('input[name=npassword]').val().trim()=="" || $('input[name=cpassword]').val().trim()=="")
                {
                    $('.response').html('<div class="alert alert-danger alert-med">Please fill all fields.</div>');
                    $('input[name=opassword]').focus();
                    $button.enable();  
                    return false;
                }
                //check session first


                BootstrapDialog.show({
                    title: 'Confirmation',
                    message: 'Are you sure you want to change your password?',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: true,
                    onshow: function(dialog) {
                        // dialog.getButton('button-c').disable();
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-ok-sign',
                        label: 'Ok',
                        cssClass: 'btn-primary',
                        hotkey: 13,
                        action:function(dialogItself){
                            var $button1 = this;
                            $button1.disable();
                            $.ajax({
                                url:formURL,
                                data:postData,
                                type:'POST',
                                success:function(data)
                                {
                                    console.log(data);
                                    var data = JSON.parse(data);
                                    if(data['st'])
                                    {
                                        BootstrapDialog.closeAll();
                                        var dialog = new BootstrapDialog({
                                        message: function(dialogRef){
                                        var $message = $('<div>Password successfully changed. Logging out...</div>');                 
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
                                            window.location=siteurl;   
                                        }, 1500);
                                    }
                                    else 
                                    {
                                        $('.response').html('<div class="alert alert-danger alert-med">'+data['msg']+'</div>');
                                        $('input[name=opassword]').focus();
                                        dialogItself.close();
                                        $button.enable();   
                                        return false;
                                    }
                                }
                            });                                                    
                        }
                    },{
                        icon: 'glyphicon glyphicon-remove-sign',
                        label: 'Cancel',
                        action: function(dialogItself){
                            dialogItself.close();
                            $button.enable();  
                        }
                    }]
                }); 

                return false;

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

function changeUsername()
{
    flag = 1;
    BootstrapDialog.show({
        title: 'Change Username',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        message: function(dialog) {
            var $message = $("<div><img src='"+siteurl+"assets/img/ajax-loader.gif'><small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
                $message.load(pageToLoad); 
            },1000);
          return $message;
        },
        data: {
            'pageToLoad': siteurl+'user/changeUsernameDialog',
        },
        cssClass: 'changeaccount',
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onhidden: function(dialogRef){
            $('#numOnly').focus();
            flag=0;
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Yes',
            cssClass: 'btn-success',
            hotkey: 13,
            action:function(dialogItself){   
                $('.response').html('');

                var $button = this;
                $button.disable();
                var postData = $('#_changeusername').serialize();
                var formURL = $('#_changeusername').attr("action");

                if($('input[name=username]').val()==undefined)
                {
                    $('input[name=username]').focus();
                    $button.enable();  
                    return false;
                }                

                if($('input[name=username]').val().trim()=="")
                {
                    $('.response').html('<div class="alert alert-danger alert-med">Username is required.</div>');
                    $('input[name=username]').focus();
                    $button.enable();  
                    return false;
                }
                //check session first
                var nusername = $('input[name=username]').val().trim();
                BootstrapDialog.show({
                    title: 'Confirmation',
                    message: 'Are you sure you want to change your username?',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: true,
                    onshow: function(dialog) {
                        // dialog.getButton('button-c').disable();
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-ok-sign',
                        label: 'Ok',
                        cssClass: 'btn-primary',
                        hotkey: 13,
                        action:function(dialogItself){
                            var $button1 = this;
                            $button1.disable();
                            $.ajax({
                                url:formURL,
                                data:postData,
                                type:'POST',
                                success:function(data)
                                {
                                    console.log(data);
                                    var data = JSON.parse(data);
                                    if(data['st'])
                                    {
                                        BootstrapDialog.closeAll();
                                        var dialog = new BootstrapDialog({
                                        message: function(dialogRef){
                                        var $message = $('<div>Username successfully changed. Logging out...</div>');                 
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
                                            window.location=siteurl;   
                                        }, 1500);
                                    }
                                    else 
                                    {
                                        $('.response').html('<div class="alert alert-danger alert-med">'+data['msg']+'</div>');
                                        $('input[name=username]').focus();
                                        dialogItself.close();
                                        $button.enable();   
                                        return false;
                                    }
                                }
                            });                                                    
                        }
                    },{
                        icon: 'glyphicon glyphicon-remove-sign',
                        label: 'Cancel',
                        action: function(dialogItself){
                            dialogItself.close();
                            $button.enable();  
                        }
                    }]
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

function generateTerminalReport()
{
    flag = 1;
    BootstrapDialog.show({
        title: 'Generate Terminal Report',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        message: function(dialog) {
            var $message = $("<div><img src='"+siteurl+"assets/img/ajax-loader.gif'><small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
                $message.load(pageToLoad); 
            },1000);
          return $message;
        },
        data: {
            'pageToLoad': siteurl+'reports/generateTerminalReport',
        },
        cssClass: 'posreport',
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onhidden: function(dialogRef){
            $('#numOnly').focus();
            flag=0;
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Yes',
            cssClass: 'btn-success',
            hotkey: 13,
            action:function(dialogItself){   
                $('.response').val('');
                var $button = this;
                $button.disable();
                if($('input[name=start]').val()==undefined)
                {
                    $('input[name=start]').focus();
                    $button.enable();  
                    return false;
                }

                var d1 = $('input[name=start]').val();
                var d2 = $('input[name=end]').val();
                var d1u = convertToSqlDate(d1);
                var d2u = convertToSqlDate(d2);
                var trans = $('select[name=trans]').val();    

                if(d1.trim()=='' || d2.trim()=='')
                {
                    $('.response').html('<div class="alert alert-danger alert-med">Please input Date Start and Date End.</div>');
                    $('input[name=start]').focus();
                    $button.enable();
                    return false;
                }

                if(validDate1(d1) && validDate1(d2))
                {
                    $('.response').html('<div class="alert alert-danger alert-med">Date Start / Date End is invalid.</div>');
                    $('input[name=start]').focus();
                    $button.enable();
                    return false;
                }

                if(!validDate(d1,d2))
                {
                    $('.response').html('<div class="alert alert-danger alert-med">Date End is Lesser than Date Start!</div>');
                    $('input[name=start]').focus();
                    $button.enable();   
                    return false;
                }

                $.ajax({
                    url:siteurl+'reports/terminalreportvalidation',
                    data:{d1:d1,d2:d2,trans:trans},
                    type:'POST',
                    success:function(data)
                    {
                        console.log(data);
                        var data = JSON.parse(data);
                        if(data['st'])
                        {
                            dialogItself.close();                      
                            window.location = siteurl+'reports/terminalreportpdf/'+trans+'/'+d1u+'/'+d2u;  
                        }
                        else 
                        {
                            $('.response').html('<div class="alert alert-danger alert-med">'+data['msg']+'</div>');
                            $('input[name=start]').focus();
                            $button.enable();   
                            return false;

                        }
                    }
                });

                //check session first
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

function endofday1()
{
    $.ajax({
        url:siteurl+'transaction/getAllActiveSimcards',
        beforeSend:function(){

        },
        success:function(datacheck){
            console.log(datacheck);
            var datacheck = JSON.parse(datacheck);

            if(datacheck['st'])
            {
                simCardBalance();
            }
            else 
            {
                endofdayproc();
            }

        }
    });

    return false;
}

function simCardBalance()
{
    BootstrapDialog.show({
        title: 'Sim Card Balance',
        cssClass: 'modal-medium',
        message: function(dialog) {
            var $message = $("<div><img src='"+siteurl+"assets/img/ajax-loader.gif'><small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
                $message.load(pageToLoad); 
            },1000);
            return $message;
        },
        data: {
            'pageToLoad': siteurl+'transaction/simBalanceDialog',
        },
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onhidden: function(dialogRef){
            $('#numOnly').focus();
            flag=0;
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Yes',
            cssClass: 'btn-success',
            hotkey: 13,
            action:function(dialogItselfc){   
                var formData = $('#_simBalance').serialize();
                dialogItselfc.enableButtons(false);
                dialogItselfc.setClosable(false);
                endofdayproc(dialogItselfc,formData);
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

function endofdayproc(dialogItselfc = null,formData = null)
{
    flag = 1;
    BootstrapDialog.show({
      title: 'Process End-Of-Day',
        message: '<div class="h4 class="hdialog">Are you sure you wish to process end of day?</div><div class="response-dialog"></div>',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onhidden: function(dialogRef){
          flag=0;
          dialogItselfc.enableButtons(true);
          dialogItselfc.setClosable(true);
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Yes',
            cssClass: 'btn-success',
            hotkey: 13,
            action:function(dialogItself){   
                var $button = this; 
                $button.disable(); 
                $.ajax({
                    url:siteurl+'reports/endofday',
                    data:formData,
                    type:'POST',
                    beforeSend:function(){

                    },
                    success:function(data){
                        console.log(data);
                        var data = JSON.parse(data);

                        if(data['st'])
                        {      
                            dialogItself.close();                      
                            window.location = siteurl+'reports/eodreportpdf/'+data['trnum'];                  
                        }
                        else 
                        {
                            $button.enable();
                            $('.response-dialog').html('<div class="alert alert-danger">'+data['msg']+'</div>')
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

function endofday()
{
    flag = 1;
    BootstrapDialog.show({
      title: 'Process End-Of-Day',
        message: '<div class="h4 class="hdialog">Are you sure you wish to process end of day?</div><div class="response-dialog"></div>',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onhidden: function(dialogRef){
          $('#numOnly').focus();
          flag=0;
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Yes',
            cssClass: 'btn-success',
            hotkey: 13,
            action:function(dialogItself){   
                var $button = this; 
                $button.disable(); 
                $.ajax({
                    url:siteurl+'reports/endofday',
                    beforeSend:function(){

                    },
                    success:function(data){
                        console.log(data);
                        var data = JSON.parse(data);

                        if(data['st'])
                        {      
                            dialogItself.close();                      
                            window.location = siteurl+'reports/eodreportpdf/'+data['trnum'];                  
                        }
                        else 
                        {
                            $button.enable();
                            $('.response-dialog').html('<div class="alert alert-danger">'+data['msg']+'</div>')
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

function cashierReport()
{
    flag = 1;
    BootstrapDialog.show({
        title: 'Cashier End of Shift Report',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        message: function(dialog) {
            var $message = $("<div><img src='"+siteurl+"assets/img/ajax-loader.gif'><small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
                $message.load(pageToLoad); 
            },1000);
          return $message;
        },
        data: {
            'pageToLoad': siteurl+'reports/eosreport',
        },
        cssClass: 'posreport',
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onhidden: function(dialogRef){
          $('#numOnly').focus();
          flag=0;
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Yes',
            cssClass: 'btn-success',
            hotkey: 13,
            action:function(dialogItself){   

                $('.msgsales').val('');

                //check session first

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

function itemRecAddItem()
{
    flag = 1;
    BootstrapDialog.show({
        title: 'Item Selection (Receiving)',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        message: function(dialog) {
            var $message = $("<div><img src='"+siteurl+"assets/img/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
                $message.load(pageToLoad); 
            },1000);
          return $message;
        },
        data: {
            'pageToLoad': siteurl+'transaction/itemSelectionReceiving',
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

                var formURL = $('form#_additem').attr('action'), formData = $('form#_additem').serialize();

                var $button = this;
                $button.disable();

                $('.response-dial').html('');
                if($('#itemtype').val()==undefined)
                {
                    $button.enable();
                    return false;
                }

                var itemtype = $('#itemtype').val();
                var item = $('#item').val();
                var loadamt = $('#loadamt').val();
                var mobnum = $('#mobnum').val();
                var loadref = $('#loadref').val();
                var nitems = $('#nitems').val();

                if(itemtype.trim()=='' || item.trim()=='')
                {
                    $('.response-dial').html('<div class="alert alert-danger">Please select item type and item name.<div>');
                    $button.enable();
                    return false;
                }

                if(itemtype.trim()==1)
                {
                    if(loadamt=='' || loadamt==0)
                    {
                        $('.response-dial').html('<div class="alert alert-danger">Please input all required fields.<div>');
                        $button.enable();
                        return false;
                    }
                }
                else 
                {
                    if(nitems=='' || nitems==0)
                    {
                        $('.response-dial').html('<div class="alert alert-danger">Please input all required fields.<div>');
                        $button.enable();
                        return false;                       
                    }
                }

                $.ajax({
                    url:siteurl+'transaction/addItemToCartReceiving',
                    type:'POST',
                    data:formData,
                    beforeSend:function(){

                    },
                    success:function(data){
                        console.log(data);
                        var data = JSON.parse(data);

                        if(data['st'])
                        {
                            $('._loadtempsrecitems').load(siteurl+'transaction/loadtempsalesitems');


                            var totalsales = addCommas(data['totalsales'].toFixed(2));
                            var totalqty = addCommas(data['totalqty'].toFixed(2));
                            //$('.sbtotal').val(totalsales);
                            $('.noitemsrec').val(totalqty);
                            $('._cashier_totalrec').val(totalsales);
                            alert('Item Successfully Added.');
                            dialogItself.close();
                        }
                        else 
                        {
                            $('.response-dial').html('<div class="alert alert-danger">'+data['msg']+'<div>');
                            $button.enable();
                        }
                    }
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

function saveReceiving()
{

    $('.msgsales').val('');
    var sinum = $('.sinumbers').val();
    var ponum = $('.ponumber').val();
    var refnum = $('.refnumber').val();
    var checkedby = $('.checkedby').val();

    if(sinum.trim()=='' || ponum.trim()=="" || refnum.trim()=="" || checkedby.trim()=='')
    {
        $('.msgsales').val('Please fill all required fields.');
        return false;
    }

    $.ajax({
        url:siteurl+'transaction/checkItemTableSales',
        beforeSend:function(){

        },
        success:function(data){
            console.log(data);
            var data = JSON.parse(data);

            if(data['st'])
            {
                flag = 1;
                BootstrapDialog.show({
                  title: 'Confirmation',
                    message: '<div class="msg-dialog">Save Items?</div>',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: true,
                    onshow: function(dialog) {
                        // dialog.getButton('button-c').disable();
                    },
                    onhidden: function(dialogRef){
                      $('#numOnly').focus();
                      flag=0;
                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-ok-sign',
                        label: 'Yes',
                        cssClass: 'btn-success',
                        hotkey: 13,
                        action:function(dialogItself){   
                            $button = this;
                            $('.msgsales').val('');
                            $button.disable();
                            //check session first
                            $.ajax({
                                url:siteurl+'transaction/saveReceiving',
                                type:'POST',
                                data:{sinum:sinum,ponum:ponum,refnum:refnum,checkedby:checkedby},
                                beforeSend:function(){

                                },
                                success:function(data){
                                    console.log(data);
                                    var data = JSON.parse(data);

                                    if(data['st'])
                                    {

                                        $('.cashier-receiving').hide();
                                        $('.cashier-main').show();
                                        $('.content-main').show();
                                        $('.content-receiving').hide();
                                        mode = 0;           
                                        dialogItself.setTitle('');                             
                                        $('.msg-dialog').html('<div class="alert alert-success">Saving Data..<div>');
                                        dialogItself.enableButtons(false);
                                        dialogItself.setClosable(false);
                                        
                                        setTimeout(function(){
                                            dialogItself.close();
                                        }, 2000);

                                        // $('._loadtempsalesitems').load(siteurl+'transaction/loadtempsalesitems');

                                        // var totalsales = addCommas(data['totalsales'].toFixed(2));
                                        // var totalqty = addCommas(data['totalqty'].toFixed(2));
                                        // $('.sbtotal').val(totalsales);
                                        // $('.noitems').val(totalqty);
                                        // $('._cashier_total').val(totalsales);
                                        // alert('Item Successfully Added.');
                                        // dialogItself.close();
                                    }
                                    else 
                                    {
                                        $('.msgsales').val(data['msg']);
                                        dialogItself.close();
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
            else
            {
                alert('Table is empty.');
            }

        }
    });
}

function getReceivingNumber()
{
    $.ajax({
        url:siteurl+'transaction/getReceivingNumber',
        beforeSend:function(){

        },
        success:function(data){
            console.log(data);
            var data = JSON.parse(data);

            if(data['st'])
            {
                $('input.recnumber').val(data['recnum']);
            }

        }
    });
}

function voidLineSales()
{
    $.ajax({
        url:siteurl+'transaction/checkItemTableSales',
        beforeSend:function(){

        },
        success:function(data){
            console.log(data);
            var data = JSON.parse(data);

            if(data['st'])
            {
                $('table tbody._loadtempsalesitems tr:first').css('background-color','yellow');
                $('table tbody._loadtempsalesitems tr:first td:nth-child(1) button').focus();
                currf = $('table tbody._loadtempsalesitems tr:first td:nth-child(1) button');
            }
            else
            {
                alert('Table is empty.');
            }
        }
    });

}

function voidLineReceiving()
{
    $.ajax({
        url:siteurl+'transaction/checkItemTableSales',
        beforeSend:function(){

        },
        success:function(data){
            console.log(data);
            var data = JSON.parse(data);

            if(data['st'])
            {
                $('table tbody._loadtempsrecitems tr:first').css('background-color','yellow');
                $('table tbody._loadtempsrecitems tr:first td:nth-child(1) button').focus();
                currf = $('table tbody._loadtempsrecitems tr:first td:nth-child(1) button');
            }
            else
            {
                alert('Table is empty.');
            }
        }
    });
}

function voidTableSalesItem(id,name)
{
    if(flag==0)
    {
        BootstrapDialog.show({
            title: 'Confirmation',
            message:  '<div class="row">'+
                '<div class="col-md-12">'+
                '<input type="hidden" value="0" id="stat">'+
                '<div class="dialog-alert">Are you sure you want to void '+name+'</div>'+
                '</div>'+                                                                       
                '</div>',
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: true,
            onshow: function(dialog) {
                // dialog.getButton('button-c').disable();
            },
            onshown: function(dialog) {

            },
            onhidden: function(dialogRef){
                flag=0;                 
            },
            buttons: [{
                icon: 'glyphicon glyphicon-ok-sign',
                label: 'Yes',
                cssClass: 'btn-success',
                action:function(dialogItself){
                    var $button = this;
                    $button.disable();

                    $.ajax({
                        url:siteurl+'transaction/voidTableItemSalesByItemIndex',
                        type:'POST',
                        data:{id:id},
                        beforeSend:function(){

                        },
                        success:function(data){
                            console.log(data);
                            var data = JSON.parse(data);

                            if(data['st'])
                            {                  

                                updateTableItemSalesData();
                                dialogItself.close();
                            }
                            else
                            {
                                alert('Something went wrong');
                            }
                        }
                    });
                }
            }, 
            {
                icon: 'glyphicon glyphicon-remove-sign',
                label: 'No',
                action: function(dialogItself){
                dialogItself.close();                      
                }
            }]
        });  
    }
}

function updateTableItemSalesData()
{   
    $.ajax({
        url:siteurl+'transaction/updateTableItemSalesData',
        beforeSend:function(){

        },
        success:function(data){
            var data = JSON.parse(data);
            var totalsales = addCommas(data['totalsales'].toFixed(2));
            var totalqty = addCommas(data['totalqty'].toFixed(2));
            if(mode==1)
            {
                $('._loadtempsalesitems').load(siteurl+'transaction/loadtempsalesitems'); 
                $('.sbtotal').val(totalsales);
                $('.noitems').val(totalqty);
                $('._cashier_total').val(totalsales);   
            }
            else if(mode == 3)
            {
                $('._loadtempsrecitems').load(siteurl+'transaction/loadtempsalesitems'); 
                $('.noitemsrec').val(totalqty);
                $('._cashier_totalrec').val(totalsales); 
            }
        
        }
    });
}

function unsetTableItemsSales()
{
    $.ajax({
        url:siteurl+'transaction/unsetTableItemsSales',
        beforeSend:function(){

        },
        success:function(data){
            var data = JSON.parse(data);

            if(data['st'])
            {
                $('._loadtempsalesitems').load(siteurl+'transaction/loadtempsalesitems');  

                $('.sbtotal').val(0);
                $('.noitems').val(0);                
                $('._cashier_total').val(0);
                $('.msgsales').val("");                                
            }                                 
        }
    });
}

function unsetTableItemsRec()
{
    $.ajax({
        url:siteurl+'transaction/unsetTableItemsSales',
        beforeSend:function(){

        },
        success:function(data){
            var data = JSON.parse(data);

            if(data['st'])
            {
                $('._loadtempsrecitems').load(siteurl+'transaction/loadtempsalesitems');  

                $('.sbtotalrec').val(0);
                $('.noitemsrec').val(0);                
                $('._cashier_totalrec').val(0);    
                          
            }                                 
        }
    });
}

function paymentCash()
{
    $.ajax({
        url:siteurl+'transaction/checkItemTableSales',
        beforeSend:function(){

        },
        success:function(data){
            console.log(data);
            var data = JSON.parse(data);

            if(data['st'])
            {
                if(flag==0)
                {
                    flag = 1;
                    BootstrapDialog.show({
                        title: 'Cash Payment',
                        closable: true,
                        closeByBackdrop: false,
                        closeByKeyboard: true,
                        message: function(dialog) {
                            var $message = $("<div><img src='../assets/img/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
                            var pageToLoad = dialog.getData('pageToLoad');
                            setTimeout(function(){
                                $message.load(pageToLoad); 
                            },1000);
                          return $message;
                        },
                        data: {
                            'pageToLoad': siteurl+'transaction/cashpayment',
                        },
                        cssClass: 'paymentcash',             
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


                                var formURL = $('form#_additem').attr('action'), formData = $('form#_additem').serialize();
                                var amttender = $('#amttender').val();

                                dialogItself.enableButtons(false);

                                dialogItself.setClosable(false);

                                $('.response-dial').html('');

                                // check session first
                                $.ajax({
                                    url:siteurl+'transaction/checktotalpaymenttotalsales',
                                    type:'POST',
                                    data:{amttender:amttender},
                                    beforeSend:function(){

                                    },
                                    success:function(data){
                                        console.log(data);
                                        var data = JSON.parse(data);

                                        if(data['st'])
                                        {

                                            $('.cashier-main').show();
                                            $('.cashier-paymentmode').hide();
                                            $('.content-main').show();
                                            $('.content-eloadsales').hide();

                                            mode = 0;

                                            unsetTableItemsSales();                                            

                                            $('#change').val(addCommas(data['change'].toFixed(2)));

                                            $('.response-dialog').html('<div class="alert alert-success">Saving Data..<div>');
                                            
                                            
                                            
                                            setTimeout(function(){
                                                dialogItself.close();
                                            }, 5000);

                                            // $('._loadtempsalesitems').load(siteurl+'transaction/loadtempsalesitems');

                                            // var totalsales = addCommas(data['totalsales'].toFixed(2));
                                            // var totalqty = addCommas(data['totalqty'].toFixed(2));
                                            // $('.sbtotal').val(totalsales);
                                            // $('.noitems').val(totalqty);
                                            // $('._cashier_total').val(totalsales);
                                            // alert('Item Successfully Added.');
                                            // dialogItself.close();
                                        }
                                        else 
                                        {
                                            dialogItself.enableButtons(true);

                                            dialogItself.setClosable(true);
                                            $('.response-dialog').html('<div class="alert alert-danger">'+data['msg']+'<div>');
                                        }

                                    }
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


            }
            else
            {
                alert('Table is empty.');
            }

        }
    });
}

function loadtransfer()
{
    flag = 1;
    BootstrapDialog.show({
        title: 'Load Transfer',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        message: function(dialog) {
            var $message = $("<div><img src='"+siteurl+"assets/img/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
                $message.load(pageToLoad); 
            },1000);
          return $message;
        },
        data: {
            'pageToLoad': siteurl+'transaction/loadtransferdialog',
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

                var formURL = $('form#_loadtransfer').attr('action'), formData = $('form#_loadtransfer').serialize();

                var $button = this;
                $button.disable();

                $('.response-dial').html('');
                if($('#simfrom').val()==undefined)
                {
                    $button.enable();
                    return false;
                }

                var simfrom = $('#simfrom').val();
                var simto = $('#simto').val();
                var loadamt = $('#loadamt').val();

                if(simfrom.trim()=='' || simto.trim()=='')
                {
                    $('.response-dial').html('<div class="alert alert-danger">Please select From Sim Card and To Sim Card.<div>');
                    $button.enable();
                    return false;
                }

                if(loadamt.trim()=="" || loadamt.trim()=="0" || loadamt.trim()=="0.00")
                {
                    $('.response-dial').html('<div class="alert alert-danger">Invalid load amount.<div>');
                    $button.enable();
                    return false;   
                }

                if(simfrom==simto)
                {
                    $('.response-dial').html('<div class="alert alert-danger">From Sim Card must not be the same as To Sim Card<div>');
                    $button.enable();
                    return false;
                }

                BootstrapDialog.show({
                  title: 'Confirmation',
                    message: 'Transfer Load?',
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: true,
                    onshow: function(dialog) {
                        // dialog.getButton('button-c').disable();
                    },
                    onhidden: function(dialogRef){

                    },
                    buttons: [{
                        icon: 'glyphicon glyphicon-ok-sign',
                        label: 'Yes',
                        cssClass: 'btn-success',
                        hotkey: 13,
                        action:function(dialogItself1){     
                            var $button1 = this;
                            $button1.disable();

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
                                        dialogItself.close();
                                        dialogItself1.close();
                                        var dialog = new BootstrapDialog({
                                        message: function(dialogRef){
                                        var $message = $('<div>Load Successfully Transfer.</div>');                  
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
                                            dialog.close();
                                        }, 1500);
                                    }
                                    else 
                                    {
                                        alert("Something went wrong.");
                                        //$('.response-dial').html('<div class="alert alert-danger">'+data['msg']+'<div>');
                                        dialogItself1.close();
                                        dialogItself.close();
                                        $button.enable();
                                    }
                                }
                            });

                        }
                    }, {
                      icon: 'glyphicon glyphicon-remove-sign',
                        label: 'No',
                        action: function(dialogItself){
                            dialogItself.close();
                            $button.enable();
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

function itemSalesAddItem()
{
    flag = 1;
    BootstrapDialog.show({
        title: 'Item Selection (Sales)',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        message: function(dialog) {
            var $message = $("<div><img src='"+siteurl+"assets/img/ajax-loader.gif'> <small class='text-danger'>please wait...</small></div>");
            var pageToLoad = dialog.getData('pageToLoad');
            setTimeout(function(){
                $message.load(pageToLoad); 
            },1000);
          return $message;
        },
        data: {
            'pageToLoad': siteurl+'transaction/itemSelection',
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

                var formURL = $('form#_additem').attr('action'), formData = $('form#_additem').serialize();

                var $button = this;
                $button.disable();

                $('.response-dial').html('');
                if($('#itemtype').val()==undefined)
                {
                    $button.enable();
                    return false;
                }

                var itemtype = $('#itemtype').val();
                var item = $('#item').val();
                var loadamt = $('#loadamt').val();
                var mobnum = $('#mobnum').val();
                var loadref = $('#loadref').val();
                var loaddeduct = $('#loaddeduct').val();
                var nitems = $('#nitems').val();

                if(itemtype.trim()=='' || item.trim()=='')
                {
                    $('.response-dial').html('<div class="alert alert-danger">Please select item type and item name.<div>');
                    $button.enable();
                    return false;
                }

                if(itemtype.trim()==1)
                {
                    if(loadamt=='' || loadamt==0 || mobnum=='' || mobnum==0 || loadref=='' || loaddeduct=='' || loaddeduct=='0.00')
                    {
                        $('.response-dial').html('<div class="alert alert-danger">Please input all required fields.<div>');
                        $button.enable();
                        return false;
                    }
                }
                else 
                {
                    if(nitems=='' || nitems==0)
                    {
                        $('.response-dial').html('<div class="alert alert-danger">Please input all required fields.<div>');
                        $button.enable();
                        return false;                       
                    }
                }

                $.ajax({
                    url:siteurl+'transaction/addItemToCartSales',
                    type:'POST',
                    data:formData,
                    beforeSend:function(){

                    },
                    success:function(data){
                        console.log(data);
                        var data = JSON.parse(data);

                        if(data['st'])
                        {
                            $('._loadtempsalesitems').load(siteurl+'transaction/loadtempsalesitems');


                            var totalsales = addCommas(data['totalsales'].toFixed(2));
                            var totalqty = addCommas(data['totalqty'].toFixed(2));
                            $('.sbtotal').val(totalsales);
                            $('.noitems').val(totalqty);
                            $('._cashier_total').val(totalsales);
                            alert('Item Successfully Added.');
                            dialogItself.close();
                        }
                        else 
                        {
                            $('.response-dial').html('<div class="alert alert-danger">'+data['msg']+'<div>');
                            $button.enable();
                        }
                    }
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

function logoutuser()
{
  if(flag==0){
    flag=1;
    BootstrapDialog.show({
      title: 'Confirmation',
        message: 'Are you sure you want to logout?',
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: true,
        onshow: function(dialog) {
            // dialog.getButton('button-c').disable();
        },
        onhidden: function(dialogRef){
          $('#numOnly').focus();
          flag=0;
        },
        buttons: [{
            icon: 'glyphicon glyphicon-ok-sign',
            label: 'Yes',
            cssClass: 'btn-success',
            hotkey: 13,
            action:function(dialogItself){     
                $.ajax({
                    url:siteurl+'user/logoutuser',
                    beforeSend:function(){

                    },
                    success:function(data){
                        dialogItself.close();
                        window.location=siteurl;            
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
}

function updateTime() {
var today=new Date();
    var hh=today.getHours();
    var mm=today.getMinutes();
    var ss=today.getSeconds();
    // h = h % 12;
    // h= h ? h : 12; // the hour '0' should be '12'
    // var ampm=h >= 12 ? 'pm' : 'am';
    // m = m < 10 ? '0'+m : m;
    // s = s <10 ? '0'+s: s;
        var ampm;
    // This line gives you 12-hour (not 24) time
    if (hh >= 12) { hh = hh - 12; ampm = "PM"; } else { ampm = "AM"; }
    // These lines ensure you have two-digits
    if (hh < 10) { hh = "0" + hh; }
    if (mm < 10) { mm = "0" + mm; }
    if (ss < 10) { ss = "0" + ss; }
    //if (ss < 10) {ss = "0"+ss;}
    // This formats your string to HH:MM:SS
    (hh == "00") ? hh = "12" : hh;
    var time = hh + ":" + mm + ":" + ss + " " + ampm;
// add a zero in front of numbers<10

setTimeout("updateTime()",1000);
document.getElementById('time').innerHTML= time;
}
updateTime();

function removecomma(nStr){
  return nStr.replace(/,/g , "");
} 

function dateToday()
{
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();

    if(dd < 10) {
      dd = '0' + dd
    } 

    if(mm < 10) {
      mm = '0' + mm
    } 

    today = mm + '/' + dd + '/' + yyyy;
    return today;

}

function checksession()
{
    setInterval(function() {
        $.ajax({
            url:'../ajax-cashier.php?request=checksession',
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
                        window.location.href ='login.php';
                    }, 1500);    
                    //$('.responsechangepass').html('<div class="alert alert-danger alert-no-bot alertpad8">'+data['msg']+'</div>');
                }
            }
        });

    },40000); // 60000 milliseconds = one minute

    
}

function changeItemsItemSelection(id)
{
    $('.eloadiv').hide();
    $('.noteload').hide();

    $('#loadamt').val("");
    $('#loadref').val("");

    $('#nitems').val("");
    $('#srp').val("");

    var itemtype = $('#itemtype').val();

    if(itemtype.trim=='')
    {
        $('.eloadiv').hide();
        $('.noteload').hide();
    }
    else if(itemtype.trim()=='1') 
    {
        $('.eloadiv').show();
    }
    else 
    {
        // get srp
        $.ajax({
            url:siteurl+'transaction/getNetPrice',
            type:'POST',
            data:{id:id},
            beforeSend:function(){

            },
            success:function(data){
                console.log(data);
                var data = JSON.parse(data);
                $('#srp').val(data['srp']);
            }
        });

        $('.noteload').show();
    }
}

function changeItemsItemSelectionSales(id)
{
    $('.eloadiv').hide();
    $('.noteload').hide();

    $('#loadamt').val("");
    $('#loadref').val("");

    $('#nitems').val("");
    $('#srp').val("");

    $('#loaddeduct').val('0.00');


    var itemtype = $('#itemtype').val();
    var item = $('#item').val();

    if(itemtype.trim=='')
    {
        $('.eloadiv').hide();
        $('.noteload').hide();
    }
    else if(itemtype.trim()=='1') 
    {
        $('.eloadiv').show();

        if(item!='1' && item!='25')
        {
            $('#loaddeduct').prop('readonly',true);
        }
        else 
        {
            $('#loaddeduct').prop('readonly', false);
        }
    }
    else 
    {
        // get srp
        $.ajax({
            url:siteurl+'transaction/getItemSRPNETPRICE',
            type:'POST',
            data:{id:id},
            beforeSend:function(){

            },
            success:function(data){
                console.log(data);
                var data = JSON.parse(data);
                $('#srp').val(data['srp']);
                $('#netprice').val(data['netprice']);
            }
        });

        $('.noteload').show();
    }

}

function changeItemType(id)
{
    $('.eloadiv').hide();
    $('.noteload').hide();
    if(id.trim()!='')
    {
        $('#item').prop('disabled',false);
        // get items
        $.ajax({
            url:siteurl+'transaction/getAllItems',
            type:'POST',
            data:{id:id},
            beforeSend:function(){

            },
            success:function(data){
                console.log(data);
                var data = JSON.parse(data);   
                $('#item').empty();
                $('#item').append('<option value="">-Select-</option>');
                for (var i = 0; i < data['items'].length; i++) 
                {                    
                    $('#item').append('<option value="'+data['items'][i]['it_id']+'">'+data['items'][i]['it_name']+'</option>');
                };
            }
        });
    }
    else 
    {
        $('#item').prop('disabled',true);
        $('#item').empty();
        $('#item').append('<option value="">-Select-</option>');
    }
}


function nItemsSales(qty)
{
    qty = qty.replace(/,/g , "");
    qty  = isNaN(qty) ? 0 : qty;

    var srp = $('#srp').val();
    srp = srp.replace(/,/g , "");
    srp = isNaN(srp) ? 0 : srp;

    var total = qty * srp;

    total = addCommas(total.toFixed(2));

    $('#totalsrp').val(total);
}

function nLoadAmt()
{
    if($('#item').val().trim()==2)
    {
        var itemLoad = $('#loadamt').val();   
        itemLoad = addCommas(itemLoad);
        $('#loaddeduct').val(itemLoad);
    }
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

function checksession()
{
    setInterval(function() {
        $.ajax({
            url: siteurl+'transaction/checksession',
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
                        window.location.href =siteurl;
                    }, 1500);    
                    //$('.responsechangepass').html('<div class="alert alert-danger alert-no-bot alertpad8">'+data['msg']+'</div>');
                }
            }
        });
    },40000); // 60000 milliseconds = one minute
    
}

function validDate1(dValue)
{
    dValue = dValue.split('/');
    // if(!isNaN(dValue[0]) && !isNaN(dValue[1]) && !isNaN(dValue[2]))
    // {
    //   return true;
    // }
    // else 
    // {

    // }
    if(isNaN(dValue[0]) || isNaN(dValue[1]) || isNaN(dValue[2]))
    {
        return true;
    }
    else 
    {
        return false;
    }
}

function changeSimCardTransferFrom(itemid)
{
    alert(itemid);
    if(itemid.trim()!='')
    {
        $.ajax({
            url:siteurl+'transaction/getSimCardNumberBySimID',
            type:'POST',
            data:{itemid:itemid},
            beforeSend:function(){

            },
            success:function(data){
                console.log(data);
                var data = JSON.parse(data);   
                if(data['st'])
                {
                    $('span.ssimfrom').html(' - '+data['simcard']);
                }
                else 
                {
                    $('input#haserror').val(1);
                    $('.response-dial').html('<div class="alert alert-danger alert-med">Something went wrong.</div>')
                }
            }
        });
    }
    else 
    {
        $('span.ssimfrom').html('');
    }
}

function changeSimCardTransferTo(itemid)
{
    if(itemid.trim()!='')
    {
        $.ajax({
            url:siteurl+'transaction/getSimCardNumberBySimID',
            type:'POST',
            data:{itemid:itemid},
            beforeSend:function(){

            },
            success:function(data){
                console.log(data);
                var data = JSON.parse(data);   
                if(data['st'])
                {
                    $('span.ssimto').html(' - '+data['simcard']);
                }
                else 
                {
                    $('input#haserror').val(1);
                    $('.response-dial').html('<div class="alert alert-danger alert-med">Something went wrong.</div>')
                }
            }
        });
    }
    else 
    {
        $('span.ssimto').html('');
    }
}



function convertToSqlDate(dValue)
{
    //m - d y
    dValue = dValue.split('/');
    return dValue[2]+'-'+dValue[0]+'-'+dValue[1];
}

function validDate(dToday,dValue) {
    var result = true;
    console.log(dToday);
    dValue = dValue.split('/');
    dToday = dToday.split('/');

    if(dValue[2]<dToday[2])
    {
        return false;
    }

    if(dValue[2]==dToday[2])
    {
        if(dValue[0]<dToday[0])
        {
            return false;
        }
    }
    else 
    {
        return true;
    }

    if(dValue[0]==dToday[0])
    {
        if(dValue[1]<dToday[1])
        {
            return false;
        }
    }

    // if(dValue[1]<dToday[1])
    // {
    //   return false;
    // }

    return result;

    // var pattern = /^\d{2}$/;

    // if (dValue[0] < 1 || dValue[0] > 12)
    //     result = true;

    // if (!pattern.test(dValue[0]) || !pattern.test(dValue[1]))
    //     result = true;

    // if (dValue[2])
    //     result = true;  
}


