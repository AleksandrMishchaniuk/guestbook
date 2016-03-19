//-------------------------------------------------------------------
//---------- AJAX FUNCTIONS -----------------------------------------
//-------------------------------------------------------------------

/**
 * Sends query to back-end
 * Gets data from back-end
 * Launchs action
 * 
 * @param {string} action_name (action name in file /js/actions.js)
 * @param {string} path (URL for backend routing (in file /config/routes.php))
 * @param {object} obj (form object)
 * @returns {undefined}
 */
function ajaxAction(action_name, path, obj){
    var params = {};
    if(obj){
        var arr = $(obj).serializeArray();
        for(var i=0; i<arr.length; i++){
            params[arr[i].name] = arr[i].value;
        }
    }
    $.post(path, params, 
            function(data){
//                alert(data);
//                $('#test_div').html(data);
                try{
                    $.parseJSON(data);
                }catch(e){
                    location.reload();
                }
                var action = 'action'+ucfirst(action_name);
                new Function(action+'(\''+data+'\')')();
            }); 
}