var me = {};
me.avatar = "https://lh6.googleusercontent.com/-lr2nyjhhjXw/AAAAAAAAAAI/AAAAAAAARmE/MdtfUmC0M4s/photo.jpg?sz=48";

var you = {};
you.avatar = "https://a11.t26.net/taringa/avatares/9/1/2/F/7/8/Demon_King1/48x48_5C5.jpg";

function formatAMPM(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;
    var strTime = hours + ':' + minutes + ' ' + ampm;
    return strTime;
}            

//-- No use time. It is a javaScript effect.
function insertChat(who, text, time = 0){
    var control = "";
    var date = formatAMPM(new Date());
    
    if (who == "me"){
        
        control = '<li style="width:100%">' +
                        '<div class="msj macro">' +
                        '<div class="avatar"><img class="img-circle" style="width:25%;" src="'+ me.avatar +'" /></div>' +
                            '<div class="text text-l">' +
                                '<p>'+ text +'</p>' +
                                '<p><small>'+date+'</small></p>' +
                            '</div>' +
                        '</div>' +
                    '</li>';                    
    }else{
        control = '<li style="width:100%;">' +
                        '<div class="msj-rta macro">' +
                            '<div class="text text-r">' +
                                '<p>'+text+'</p>' +
                                '<p><small>'+date+'</small></p>' +
                            '</div>' +
                        '<div class="avatar" style="padding:0px 0px 0px 10px !important"><img class="img-circle" style="width:25%;" src="'+you.avatar+'" /></div>' +                                
                  '</li>';
    }
    setTimeout(
        function(){                        
            $("#tchat").append(control);

        }, time);
    
}

function resetChat(){
    $("#tchat").empty();
}

$(".mytext").on("keyup", function(e){
    if (e.which == 13){
        var text = $(this).val();
        if (text !== ""){
            insertChat("me", text);
            addMessage(text,$("#id_tag")[0].href);              
            $(this).val('');
        }
    }
});

//-- Clear Chat
resetChat();

function getMessage(tag){

        $.ajax({
                url:$("#url_tchat")[0].href,
                type: "POST",
                dataType: "json",
                data: {
                    "tag": tag
                },
                async: true,
                success: function (data)
                {
                    console.info("success");
                    console.log(data);
                     resetChat();
                      if(data){

                            for (var i in data) {

                                console.info(data[i].user);
                                console.info($("#id_user")[0].value);

                                if($("#id_user")[0].value == data[i].user)
                                insertChat("me", data[i].message ,0);
                                else
                                insertChat("you", data[i].message ,0);
                            }
                    }
                },
                error: function (data) {
                    console.info("error");
                    console.log(data);
                }
            });
}




function addMessage(message,tag){
        $.ajax({
                url:$("#url_tchat")[0].href,
                type: "POST",
                dataType: "json",
                data: {
                    "tag": tag,
                    "message": message
                },
                async: true,
                success: function (data) {
                    console.info("success");
                    console.log(data); 
                },
                error: function (data) {
                    console.info("error");
                    console.log(data);
                }
            });
}


setInterval(function(){
if($("#id_tag")[0]){
   
    getMessage($("#id_tag")[0].href);
}

},2000);
