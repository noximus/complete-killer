function fb_relay_login(){
	FB.login(handleSession,{perms:"publish_stream,user_photos,friends_photos"});
}

function fb_relay_logout(){
	FB.logout();
}

function handleSession(response){
	if(response.session){
		fb_relay_success(response.session.uid);
	} else {
		fb_relay_cancel();
	}
}


function fb_relay_cancel(){
	getFlashMovieObject('flea_killah_flash').fbCancel('cancel');
}

function fb_relay_success(uid){
	getFlashMovieObject('flea_killah_flash').fbInfo(uid);
	getFlashMovieObject('flea_killah_flash').fbUpdate('success');
}

function fb_relay_publish(uniq){

	FB.ui(
	{
		method:'stream.publish',
		attachment: {
		"media": [{"type":"image","src":"http://www.completekiller.com/assets/template/fleakilla_icon.jpg","href":"http://www.completekiller.com//time-killers/flea-killah/?share="+uniq}],
			  "name":"Killer Video",
			  "description":"From now on, call me \"The Slayer.\"  Watch this video to find out why.  http://www.completekiller.com/time-killers/flea-killah/?share="+uniq,
			  "href":"http://www.completekiller.com//time-killers/flea-killah/?share="+uniq
		},
		user_message_prompt:'Share FRONTLINE&reg; Plus with your Friends'
	});
}

function twitter_relay(url){
	window.location.href = url;
}

function tw_relay_publish(code){
	var LeftPosition = (screen.width) ? (screen.width-800)/2 : 0;
	var TopPosition = (screen.height) ? (screen.height-400)/2 : 0;
	var settings = 'height=400,width=800,top='+TopPosition+',left='+LeftPosition+',scrollbars=no,resizable=no,menubar=no,directories=no,location=no';
	win = window.open("http://www.completekiller.com/index_ci.php/ent/share_with_twitter/fleakillah/0/"+code,"twitter",settings);
}

function getFlashMovieObject(movieName){
if (window.document[movieName]) return window.document[movieName];
if (navigator.appName.indexOf("Microsoft Internet")==-1){
if (document.embeds && document.embeds[movieName]) return document.embeds[movieName];
else return document.getElementById(movieName);}
}