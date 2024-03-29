var flexId = ""; 

function fbInit(api,crossdomain,id) {
	FB.init({ apiKey:api, status:true, cookie:true });
	flexId = id;
}

function fbLogin( isAuto ) {
	if( isAuto == false ){
		FB.login(handleSession,{perms:"publish_stream"});
	} else {	
		FB.getLoginStatus(handleSession);
	}
}

function handleSession(response){
	if(response.session){
		onLogin();
	} else {
		onLogout();
	}
}

function onLogin() {
	flexDispatcher( "onLoggedIn", FB.getSession() );
}

function fbUserInfo(userQuery)
{
	FB.api({ method:'fql.query', query:userQuery },
		function(response) {
			flexDispatcher( "onUserInfo", response );
		}
	);
}

function fbLogout()
{
	FB.logout(handleSession);
}

function onLogout()
{
	flexDispatcher( "onLoggedOut" );
}

//publish to the wall
function fbPublish( template, commentToken, imagePath, imageLink, postname )
{	
	 FB.ui(
	 {
		 method: 'stream.publish',
		 attachment: {
		   name: postname,
		   caption: commentToken,
		   description: '-',
		   href: 'http://completekiller.com/ent/fleas_and_desist',
		   media: [{"type":"image","src":imagePath,"href":imageLink}]
		 },
		 user_message_prompt: 'Share FRONTLINE&reg; Plus with your Friends'
	});
}

function flexDispatcher( func )
{
	if( arguments.length > 1 )
		swfobject.getObjectById( flexId )[func]( Array.prototype.slice.call(arguments).slice(1)[0]);
	else
		swfobject.getObjectById( flexId )[func]();
}

//Twitter @anywhere

$(document).ready(function(){
	
	$("#twitterBtn").click(function(e){
	    //Cancel the link behavior
        e.preventDefault();
		postTweet("my custom message");
	});
	
	//if close button is clicked
    $('.window .close').click(function (e) {
		//Cancel the link behavior
		e.preventDefault();
		$('#mask').hide();
		$('.window').hide();
	});		

	//if mask is clicked
	$('#mask').click(function () {
		$(this).hide();
		$('.window').hide();
	});
	
});

var msg = "";

function postTweet(type,score){
	if(type == "" || type == undefined || type == null){
		type = "share";
	}
	if(score == "" || score == undefined || score == null){
		score = 0;
	}
	var LeftPosition = (screen.width) ? (screen.width-800)/2 : 0;
	var TopPosition = (screen.height) ? (screen.height-400)/2 : 0;
	var settings = 'height=400,width=800,top='+TopPosition+',left='+LeftPosition+',scrollbars=no,resizable=no,menubar=no,directories=no,location=no';
	win = window.open("http://completekiller.coolfiredev.com/ent/share_with_twitter/"+type+"/"+score,"twitter",settings);
}
