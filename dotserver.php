<?php

	error_reporting( E_ALL );
	ini_set( 'display_errors', 1 );
	ini_set( 'session.save_path', './sessions' );

	require_once './fb/lib/FirePHPCore/fb.php';
	
	session_start();
	
	$sid = session_id();
	$dotfile = dirname( __FILE__ ) . '/dots/' . $sid . '.dot';

	$dot = ( !isset( $_POST[ 'dot' ] ) || empty( $_POST[ 'dot' ] ) ) ? false : $_POST[ 'dot' ];
	if ( !$dot ) {
		$dot = 'digraph test { a -> b; }';
		if ( file_exists( $dotfile ) ) $dot = file_get_contents( $dotfile );
	}

	$formats = array('pdf', 'png');
	$defaultFormat = 'png';
	$format = isset( $_GET['format'] ) ? in_array( $_GET['format'], $formats ) ? $_GET[ 'format' ] : $defaultFormat : $defaultFormat;

	$width = isset( $_POST[ 'width' ] ) ? $_POST[ 'width' ] : false;
	$height = isset( $_POST[ 'height' ] ) ? $_POST[ 'height' ] : false;

	$download = isset( $_GET[ 'download' ] ) ? $_GET[ 'download' ] : false;

	$graphsdir = dirname( __FILE__ ) . '/graphs/';
	$outfile = $graphsdir . '/' . $sid . '.' . $format;
	file_put_contents( $dotfile, $dot );

	// Welchen renderer benutzen
	$renderer = 'dot';
	if ( stristr( $dot, 'neato' ) ) $renderer = 'neato';

	$descriptorspec = array(
		0 => array('pipe', 'r'),
		2 => array('pipe', 'w'),
	);
	$cmd = '/usr/bin/' . $renderer . ' -T' . $format;
	if ( $width && $height ) $cmd .= ' -Gsize=' . $height . ',' . $width;
	$cmd .= ' -o ' . $outfile;
	$process = proc_open( $cmd, $descriptorspec, $pipes, $graphsdir );

    fwrite( $pipes[0], $dot );
    fclose( $pipes[0] );
    $dotErrors = '';
    while ( !feof( $pipes[ 2 ] ) ) {
		$dotErrors .= fgets( $pipes[ 2 ] );
	}
    fclose( $pipes[2] );

    $dotCode = proc_close( $process );

    if ( $download ) {
		if ( $download === 'dot' ) {
			header( 'Content-Type: text/plain' );
			header( 'Content-Disposition: attachment; filename=' . $sid . '.dot' );
			echo $dot;
		} else {
			switch($format) {
			case 'png':
				header( 'Content-Type: image/png' );
				break;
			case 'pdf':
				header( 'Content-Type: application/pdf' );
				break;
			default:
				header( 'Content-Type: application/octet-stream' );
			}
			header( 'Content-Disposition: attachment; filename=' . $sid . '.' . $format );
			echo file_get_contents( $outfile );
		}
		return;
    }

	$return = new stdClass();
	$return->image = basename( $outfile ) . '?' . time();
	$return->dotresult = array( 'code' => $dotCode, 'msg' => $dotErrors );
	$return->success = ( $dotCode === 0 );
	$return->cmd = $cmd;
	echo json_encode( $return );
