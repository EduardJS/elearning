/* CHAT CONTROLLER */
var chat =
{
	channels : {},
	replies_limit : 5,
	online : [],
	init : function ( )
	{

		// storing channels
		this.channels = {
			presence : 'presence-global'
		};

		chatTPL = new Ractive({
			el : 'wrapper',
			append : true,
			template : templates.chat,
			data : {
				me : user,
				friends : friends,
				online : [],
				replies : [],
				replies_limit : chat.replies_limit
			}
		});

		// chat actions
		chatTPL.on({
			reply : function( e, message )
			{
				// remove message
				chatTPL.set( 'replyMessage', null );

				// send message
				chat.reply({
					user: user.id,
					message : message
				}, true );

			},
			checkEnter : function ( e , message )
			{
				if ( e.original.which == 13 )
				{
					if ( message.trim().length )
						chatTPL.fire( 'reply', {}, message );

					e.original.preventDefault();
				}
			}
		});

		// connect
		this.connect();

	},
	connect : function ( )
	{
		if ( typeof Pusher !== 'undefined' )
		{

			// open connection to Pusher
			this.pusher = new Pusher( 'a906e3112e21c93de22b' , { /* encrypted : true, */ authEndpoint : '/chat.php' } );
			Pusher.log = function( data ) { return null }

			// loop through channels
			for ( channel_type in chat.channels ) {

				var channel_name = chat.channels[ channel_type ];

				// subscribe to channel
				chat.pusher.subscribe( channel_name );

				// bind other events
				if ( chat.events[ channel_type ] )
					for ( event_name in chat.events[ channel_type ] )
						chat.pusher.channels.channels[ channel_name ].bind( event_name , chat.events[ channel_type ][ event_name ] );

			}

		} else
			console.log( 'Pusher not loaded !' );
	},
	events : {
		presence : {
			"pusher:subscription_error" : function ( data )
			{
				console.log ( 'subscribing to presence channel failed' , data );
			},
			"pusher:subscription_succeeded" : function ( friends )
			{
				friends.each( function ( friend ) {
					if ( friend.id != user.id )
						chat.online.unshift( friend.id )
				})
			},
			"pusher:member_added" : function ( friend )
			{
				if ( friend.id != user.id )
					chat.online.unshift( friend.id )
			},
			"pusher:member_removed" : function ( friend )
			{
				if ( friend.id != user.id )
					chat.online.splice( chat.online.indexOf( friend.id ) , 1 );
			},
			"client-reply" : function ( data )
			{
				chat.reply( data );
			}
		}
	},
	reply : function( data , selfReply )
	{

		var replies = chatTPL.get('replies');
		replies.unshift( data );

		if ( replies.length > chat.replies_limit + 1 )
			replies.pop();

		if ( selfReply )
			chat.pusher.channels.channels[ chat.channels.presence ].trigger( 'client-reply', data )
	}
}