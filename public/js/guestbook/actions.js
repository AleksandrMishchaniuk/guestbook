//-----------------------------------------------------------------------
//---- List Actions --------------------------------------------------
//-----------------------------------------------------------------------

function actionUpdateList(data){
    data = data.replace(/\n/g, "\\n");
    data = data.replace(/\r/g, "\\r");
    data = checkData(data);
    if(+data['ok']){
        var table = $('.message_list');
        table.empty();
        var pattern = $('.msg_row_pattern');
        for(var i=0; i<data['data']['msgs'].length; i++){
            var msg = data['data']['msgs'][i];
            var row = pattern.clone();
            row.removeClass('msg_row_pattern').appendTo(table);
            $('.number', row.eq(0)).html(i+1);
            $('.user_name', row.eq(0)).html(msg['user_name']);
            $('.email', row.eq(0)).html(msg['email']);
            $('.short_text', row.eq(0)).html(msg['short_text']);
            $('.action_panel a', row.eq(0)).attr('data-id',msg['id']);
            $('.btn_show_msg', row.eq(0)).click(function(){
                var id = $(this).attr('data-id');
                ajaxAction('MessageShow', '/guestbook/'+role+'/show/'+id);
            });
            try{
                $('.btn_edit_msg', row.eq(0)).click(function(){
                    var id = $(this).attr('data-id');
                    var modal = $('#editMessageModal').get(0);
                    $('#editModalLabel', modal).html('Edit Message');
                    $('[name="submit"]', modal).val('Edit');
                    var action = '/guestbook/'+role+'/edit/'+id;
                    $('#edit_message_form', modal).attr('action', action);
                    ajaxAction('MessageEdit', action);
                });
                $('.btn_del_msg', row.eq(0)).click(function(){
                    var id = $(this).attr('data-id');
                    var conf = confirm('Do you really want to delete this message?');
                    if(conf){
                        ajaxAction('MessageDelete', '/guestbook/'+role+'/delete/'+id);
                    }
                    return false;
                });
            }catch(e){}
        }
        var count = (data['data']['paginator']['count'])? (data['data']['paginator']['count']): 0;
        var offset = (data['data']['paginator']['offset'])? (data['data']['paginator']['offset']): 0;
        if(offset > count){
            offset = floor(count/PAGES_IN_LIST)*PAGES_IN_LIST;
        }else if(offset == count){
            offset = count-PAGES_IN_LIST;
        }
        $('.simplePaginator').pagination({
            items: count,
            itemsOnPage: PAGES_IN_LIST,
            currentPage: offset/PAGES_IN_LIST+1,
            cssStyle: 'light-theme',
            onPageClick: function(num, e){
                getData(getSort(), (num-1)*PAGES_IN_LIST, PAGES_IN_LIST);
            }
        });
    }
}

//-----------------------------------------------------------------------
//---- Massage Actions --------------------------------------------------
//-----------------------------------------------------------------------

function actionMessageSave(data){
    data = data.replace(/\n/g, "\\n");
    data = data.replace(/\r/g, "\\r");
    data = $.parseJSON(data);
    if(+data['ok']){
        $('#editMessageModal').modal('hide');
        var page = $('.simplePaginator').pagination('getCurrentPage');
        getData(getSort(), (page-1)*PAGES_IN_LIST, PAGES_IN_LIST);
        showMessenger('success', data['msg']);
    }else{
        for(var key in data['msg']){
            $('#edit_message_form [name="'+key+'"]').parent().parent().addClass('has-error');
        }
    }
}

function actionMessageShow(data){
    
    data = data.replace(/\n/g, "\<br\/\>");
    data = data.replace(/\r/g, "\\r");
    data = $.parseJSON(data);
    
    if(+data['ok']){
        var modal = $('#showMessageModal').get(0);
        $('.user_name', modal).html(data['data']['user_name']);
        $('.email', modal).html(data['data']['email']);
        $('.homepage', modal).html(data['data']['homepage']);
        $('.text', modal).html(data['data']['text']);
        try{
            $('.user_ip', modal).html(data['data']['user_ip']);
            $('.user_agent', modal).html(data['data']['user_agent']);
        }catch(e){}
    }
}

function actionMessageEdit(data){
    
    data = data.replace(/\n/g, "\\n");
    data = data.replace(/\r/g, "\\r");
    data = $.parseJSON(data);
    
    if(+data['ok']){
        var modal = $('#editMessageModal').get(0);
        $('[name="id"]', modal).val(data['data']['id']);
        $('[name="user_name"]', modal).val(data['data']['user_name']);
        $('[name="email"]', modal).val(data['data']['email']);
        $('[name="homepage"]', modal).val(data['data']['homepage']);
        $('[name="text"]', modal).val(data['data']['text']);
        showMessenger('success', data['msg']);
    }
}

function actionMessageDelete(data){
    data = $.parseJSON(data);
    if(+data['ok']){
        var page = $('.simplePaginator').pagination('getCurrentPage');
        getData(getSort(), (page-1)*PAGES_IN_LIST, PAGES_IN_LIST);
        showMessenger('success', data['msg']);
    }
}

//-----------------------------------------------------------------------
//---- Sort Actions --------------------------------------------------
//-----------------------------------------------------------------------

function actionUpdateSort(data){
    data = data.replace(/\n/g, "\\n");
    data = data.replace(/\r/g, "\\r");
    data = $.parseJSON(data);
    if(+data['ok']){
        var user_name_sel = $('#sort_by_user_name_select');
        var email_sel = $('#sort_by_email_select');
        var selected_user_name = $('option:selected', user_name_sel.get(0)).val();
        var selected_email = $('option:selected', email_sel.get(0)).val();
        user_name_sel.empty();
        email_sel.empty();
        
        var defaults_options = {
            _none_: 'none',
            _ASC_: 'ascending',
            _DESC_: 'descending'
        };
        for(var val in defaults_options){
            var user_name_op = $('<option>').val(val).html(defaults_options[val]);
            if(val === selected_user_name){
                user_name_op.attr('selected', 'true');
            }
            user_name_op.appendTo(user_name_sel);
            var email_op = $('<option>').val(val).html(defaults_options[val]);
            if(val === selected_email){
                email_op.attr('selected', 'true');
            }
            email_op.appendTo(email_sel);
        }
        
        var user_names = data['data']['user_names'];
        var group = $('<optgroup>').attr('label', 'Names');
        group.appendTo(user_name_sel);
        for(var i=0; i<user_names.length; i++){
            var option = $('<option>').val(user_names[i]).html(user_names[i]);
            if(user_names[i] === selected_user_name){
                option.attr('selected', 'true');
            }
            option.appendTo(group);
        }
        
        var emails = data['data']['emails'];
        var group = $('<optgroup>').attr('label', 'E-mails');
        group.appendTo(email_sel);
        for(var i=0; i<emails.length; i++){
            var option = $('<option>').val(emails[i]).html(emails[i]);
            if(emails[i] === selected_email){
                option.attr('selected', 'true');
            }
            option.appendTo(group);
        }
    }
}