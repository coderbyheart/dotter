window.addEvent( 'domready', function() {
	var wHeight = window.getHeight();
	var wWidth = window.getWidth();
	var menuHeight = 20;
	var resultWidth = ( wWidth / 3 ) * 2;
	var resultHeight = wHeight - menuHeight * 2;
	var zoom = 120;
	$( 'sourcediv' ).setStyles( { 'top': menuHeight, 'left': 0 } );
	$( 'source' ).setStyles( { 'height': wHeight - menuHeight, 'width': wWidth / 3 } );
	$( 'resultdiv' ).setStyles( { 'height': resultHeight, 'width': resultWidth, 'top': menuHeight, 'left': wWidth / 3 } );
	$( 'console' ).setStyles( { 'height': menuHeight, 'width': ( wWidth / 3 ) * 2, 'bottom': 0, 'left': wWidth / 3 } );
	$( 'menu' ).setStyles( { 'width': wWidth } );
	$( 'source' ).addEvent( 'keyup', function ( ev ) {
		if ( ev.code === 13 && ev.control ) {
			ev.stopPropagation();
			DotRequest.POST( { dot: ev.target.value, width: Math.round( resultWidth / zoom ), height: Math.round( resultHeight / zoom ) } );
		}
	} );
	$( 'uploadlink' ).addEvent( 'click', function() {
		$( 'uploadform' ).setStyle( 'display', 'block' );
		var mySize = $( 'uploadform' ).getSize();
		$( 'uploadform' ).setStyles( { 'left': ( wWidth / 2 ) - ( mySize.x / 2 ), 'top': ( wHeight / 2 ) - ( mySize.y / 2 ) } );
	} );
	$( 'uploadcancel' ).addEvent( 'click', function() {
		$( 'uploadform' ).setStyle( 'display', 'none' );
		return false;
	} );

	var DotRequest = new Request.JSON( {
		url: 'dotserver.php',
		autoCancel: true,
		onComplete: function( result ) {
			if ( result.success ) {
				consoleMessage( 'OK' );
				$( 'resultdiv' ).setStyle( 'background-image', 'url( graphs/' + result.image + ')' );
// 				console.log( result.cmd );
			} else {
				consoleMessage( result.dotresult[ 'msg' ] );
			}
		},
		onStart: function () {
			consoleMessage( 'Loading ...' );
		}
	} );

	function consoleMessage( msg )
	{
		$( 'console' ).empty();
		$( 'console' ).appendText( ' > ' + msg );
	}
} );