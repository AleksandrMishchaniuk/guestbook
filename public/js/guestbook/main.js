PAGES_IN_LIST = 25;
$().ready(function(){
    
    getData(getSort(), 0, PAGES_IN_LIST);

    $('#edit_message_form').submit(function(){
        var action = $(this).attr('action');
        ajaxAction('messageSave', action, this);
        return false;
    });
    
    
//-----------------------------------------------------------------------
//---- Edit Message Form Validation --------------------------------------------------
//-----------------------------------------------------------------------
    
    $('#edit_message_form [name="user_name"]').change(function(){
        var pattern = /^[ 0-9a-zA-Z]{2,50}$/;
        validateField(pattern, this);
    });
    
    $('#edit_message_form [name="email"]').change(function(){
        var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
        validateField(pattern, this);
    });
    
    $('#edit_message_form [name="homepage"]').change(function(){
        var pattern = /(^(?:http:\/\/)?[-0-9a-z._]*.\w{2,4}[:0-9]*$)|^$/;
        validateField(pattern, this);
    });
    
 //-----------------------------------------------------------------------
//---- Modal Edit Message --> then Hidden --> reset form --------------------------------------------------
//-----------------------------------------------------------------------

    $('#editMessageModal').on('hidden.bs.modal', function (e) {
        $('[name="id"]', this).val(0);
        $('[name="user_name"]', this).val('').parent().parent().removeClass('has-error');
        $('[name="email"]', this).val('').parent().parent().removeClass('has-error');
        $('[name="homepage"]', this).val('').parent().parent().removeClass('has-error');
        $('[name="text"]', this).val('').parent().parent().removeClass('has-error');
        $('#editModalLabel', this).html('Create New Message');
        $('[name="submit"]', this).val('Create');
        $('#edit_message_form', this).attr('action', '/guestbook/'+role+'/create');
    });
    
    $('#sort_message_form').change(function(){
        getData(getSort(), 0, PAGES_IN_LIST);
    });
 
});


function validateField(pattern, field){
    if(pattern.test($(field).val())){
        $(field).parent().parent().removeClass('has-error');
    }else{
        $(field).parent().parent().addClass('has-error');
    }
}


function getData(sort, offset, limit){
    var form = $('<form>');
    var user_name = $('<input>').attr({
        type: 'hidden',
        name: 'user_name',
        value: sort['user_name']
    });
    var email = $('<input>').attr({
        type: 'hidden',
        name: 'email',
        value: sort['email']
    });
    var offset_input = $('<input>').attr({
        type: 'hidden',
        name: 'offset',
        value: offset
    });
    var limit_input = $('<input>').attr({
        type: 'hidden',
        name: 'limit',
        value: limit
    });
    form.append(user_name).append(email).append(offset_input).append(limit_input);
    ajaxAction('updateList', '/guestbook/'+role+'/getList', form);
    ajaxAction('updateSort', '/guestbook/'+role+'/getSort');
}

function getSort(){
    return {
        user_name: $("#sort_by_user_name_select").val(),
        email: $("#sort_by_email_select").val()
    }
}

function showMessenger(class_name, msgs){
    var div = $('.messanger_panel');
    for(var i=0; i<msgs.length; i++){
        var msg = $('<div>').addClass('alert').addClass('alert-'+class_name)
                .html(msgs[i]).appendTo(div);
        $('<button>').addClass('close')
                .attr({
                    type: 'button',
                    'data-dismiss': 'alert'
                })
                .html('&times;')
                .prependTo(msg);
    }
}