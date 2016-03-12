
$().ready(function(){
    
//    ajaxAction('getList', '/guestbook/index/getList');

    $('#edit_message_form').submit(function(){
        ajaxAction('MessageCreate', '/guestbook/'+role+'/create', this);
        return false;
    });
    
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
    
});

function validateField(pattern, field){
    if(pattern.test($(field).val())){
        $(field).parent().parent().removeClass('has-error');
    }else{
        $(field).parent().parent().addClass('has-error');
    }
}