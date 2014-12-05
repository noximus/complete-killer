var flexId = ""; 
function fbInit( apikey, crossdomain, id )
{
	//alert('fbinit called');
	FB.init( apikey,"http://www.completekiller.com/xd_receiver.htm" );
	flexId = id;
}

function fbLogin( isAuto )
{
	//alert('fbLogin');
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

//publish to the wall
function fbPublish( template, commentToken, imagePath, imageLink )
{
	//alert('posting:' + imagePath + ', link: ' + imageLink);
	if(imageLink == "") imageLink = "http://www.completekiller.com";
	var attachment = {'media': [{'type':'image','src':imagePath,'href':imageLink}],'description':commentToken, 'href':'http://www.completekiller.com/entertainment/fleas_and_desist', 'name':'Fleas & Desist'};
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