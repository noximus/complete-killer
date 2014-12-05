var flexId = ""; 
function fbInit( apikey, crossdomain, id )
{
	//alert('fbinit called');
	FB.init( apikey, crossdomain );
	flexId = id;
}

function fbLogin( isAuto )
{
	//alert('fbLogin invoked'); 
	if ( isAuto == false ){
		//alert("isAuto == false");
		FB.Connect.requireSession( onLogin );
	}
	else
	{
		//alert("isAuto == true");
		FB.Facebook.get_sessionState().waitUntilReady(function(session) 
				{
					if(session)
						onLogin();
				});
	}
}
function onLogin()
{
	//alert('onLogin invoked');
	flexDispatcher( "onLoggedIn", FB.Facebook.apiClient._session );
}

function fbUserInfo( uid, params )
{
	FB.Facebook.apiClient.users_getInfo( uid, params, onUserInfo );
}
function onUserInfo( result, ex )
{
	flexDispatcher( "onUserInfo", result[ 0 ] );
}

function fbLogout()
{
	FB.Connect.logout( onLogout );
}
function onLogout()
{
	flexDispatcher( "onLoggedOut" );
}

function inviteFriends()
{

  FB.ensureInit(function()
  {
        var dialog = new FB.UI.FBMLPopupDialog( 'Invite your friends to try out Mod Your Bod', '' );
        var fbml = '<fb:request-form type="Tuaca Mod Your Bod" action="http://www.tuacabodyartball.com/modyourbod" invite="true" content="'+'&lt;fb:req-choice url=&quot;http://www.tuacabodyartball.com/modyourbod&quot; label=&quot;Add This Application&quot;' +'"><fb:multi-friend-selector showborder="true" actiontext="Invite your friends to try out Mod Your Bod"></fb:request-form>'; 
        dialog.setFBMLContent(fbml);
        dialog.setContentWidth(760);
        dialog.setContentHeight(650);
        
        dialog.show();
    });
}

//publish to the wall
function fbPublish( template, commentToken, imagePath, imageLink, name )
{
	//alert('posting:' + imagePath);
	var attachment = {'media': [{'type':'image',
                             'src':imagePath,
                             'href':imageLink
                             }],'description':commentToken, 'href':'http://completekiller.com/f&d', 'name':name};//'Fleas & Desist'
	FB.Connect.streamPublish('', attachment);
	
	//FB.Connect.showFeedDialog( template , comment_data, null, null, null, FB.RequireConnect.promptConnect);
}

function flexDispatcher( func )
{
	//alert('flexDispatcher called '+func+'()');
	if( arguments.length > 1 )
		swfobject.getObjectById( flexId )[func]( Array.prototype.slice.call(arguments).slice(1)[0]);
	else
		swfobject.getObjectById( flexId )[func]();
}

//Twitter @anywhere

$(document).ready(function(){
	
	alert("loading...");
	
	//$("a[rel^='prettyPhoto']").prettyPhoto({theme:'facebook'});
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

function postTweet(msg){

	//Get the screen height and width
	var maskHeight = $(document).height();
	var maskWidth = $(window).width();

	//Set heigth and width to mask to fill up the whole screen
	$('#mask').css({'width':maskWidth,'height':maskHeight});

	//transition effect		
	$('#mask').fadeIn(200);	
	$('#mask').fadeTo("slow",0.6);	

	//Get the window height and width
	var winH = $(window).height();
	var winW = $(window).width();

	//Set the popup window to center
	$("#twittermodal").css('top',  winH/2-$("#twittermodal").height()/2);
	$("#twittermodal").css('left', winW/2-$("#twittermodal").width()/2);

	//transition effect
	$("#twittermodal").fadeIn(2000);
	
	twttr.anywhere(function(twitter) {
           twitter("#tweetbox").tweetBox({
              height: 100,
              width: 400,
              defaultContent: msg
            });
      });
}
