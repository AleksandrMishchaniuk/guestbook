//-----------------------------------------------------------------------
//---- List Actions --------------------------------------------------
//-----------------------------------------------------------------------

function actionGetList(data){
    data = checkData(data);
    if(+data['ok']){
        actionUpdateList(data);
    }
}

function actionUpdateList(data){
    
}

//-----------------------------------------------------------------------
//---- Massage Actions --------------------------------------------------
//-----------------------------------------------------------------------

function actionMessageCreate(data){
//    alert(data);
    data = $.parseJSON(data);
    if(+data['ok']){
        $('#editMessageModal').modal('hide');
        $('#edit_message_form [name="user_name"]').val('').parent().parent().removeClass('has-error');
        $('#edit_message_form [name="email"]').val('').parent().parent().removeClass('has-error');
        $('#edit_message_form [name="homepage"]').val('').parent().parent().removeClass('has-error');
        $('#edit_message_form [name="text"]').val('').parent().parent().removeClass('has-error');
        actionUpdateList(data)
    }else{
        for(var key in data['msg']){
            $('#edit_message_form [name="'+key+'"]').parent().parent().addClass('has-error');
        }
    }
}