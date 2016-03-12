/**
 * Show message
 * 
 * @param {string} selector
 * @param {string} msg (message)
 * @param {integer} time
 * @returns {undefined}
 */
function msgShow(selector, msg, time){
    if(!time){
        time = 3000;
    }
    var msg_html = '';
    for(var i=0; i<msg.length; i++){
        msg_html += msg[i]+'<br/>';
    }
    $(selector).html(msg_html);
    setTimeout(function(){
        $(selector).fadeOut(1000, function(){
            $(selector).html('');
            $(selector).show();
        });
    }, time);
}

/**
 * 
 * @param {json-string} data
 * @returns {Array|Object}
 */
function checkData(data){
    try{
        data = $.parseJSON(data);
    }catch(e){
        alert('reload');
        $(window).unload();
    }
    return data;
}

/**
 * Returns index of element of array, that have field 'id' as need
 * 
 * @param {integer} id
 * @param {array} arr
 * @returns {integer} (element index)
 */
function getIndexById(id, arr){
    for(var i=0; i<arr.length; i++){
        if(arr[i]['id'] == id){
            return i;
        }
    }
}

/**
 * Returns word with first letter in uppercase
 * 
 * @param {string} string
 * @returns {string}
 */
function ucfirst(string){
    return string[0].toUpperCase() + string.slice(1);
}