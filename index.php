<?php

	session_start();
	$session_id = session_id();
	$dotfile = file_exists( getSessionDotFile() ) ? getSessionDotFile() : './dots/default.dot';

	if ( isset( $_FILES[ 'dotfile' ] ) && $_FILES[ 'dotfile' ][ 'size' ] > 0 && is_uploaded_file( $_FILES[ 'dotfile' ][ 'tmp_name' ] ) ) {
		move_uploaded_file( $_FILES[ 'dotfile' ][ 'tmp_name' ], './dots/' . $session_id . '.dot' );
		$dotfile = getSessionDotFile();
	}

	function getSessionDotFile()
	{
		return './dots/' . session_id() . '.dot';
	}
	
	
	echo '<?xml version="1.0" encoding="utf-8"?>';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Ajax dotter</title>
  <meta name="GENERATOR" content="Quanta Plus" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="reset-min.css" />
  <script type="text/javascript" src="js/mootools/mootools-1.2-core.js"></script>
  <script type="text/javascript" src="js/dotter.js"></script>
  <style type="text/css">
	/* <![CDATA[ */
		form, textarea, div, body { margin: 0; padding: 0; }
		#resultdiv { background: #ffffff no-repeat 50% 50%; }
		#resultdiv, #console, #sourcediv, #menu, #uploadform { position: absolute; margin: 0; padding: 0; }
		#sourcediv textarea { background-color: #ffffc6; font-size: 0.8125em; }
		textarea { width: 100%; border: 0; font-family: monospace; }
		#console { font-family: Monospace; font-size: 0.75em; background-color: #ccc; line-height: 20px; }
		#menu { background-color: #f2f2f2; border-top: 1px solid #ffffff; border-bottom: 1px solid #a4a09d; font-size: 0.8125em; }
		#menu li { padding: 0 5px 0 5px; float: left; cursor: pointer; }
		#menu li a { text-decoration: none; color: #000; }
		#uploadform { background-color: #fcfcfc; border: 1px solid #000; display: none; }
		#uploadform fieldset { margin: 10px; }
		#uploadform legend { font-weight: bold; }
		#uploadform p { margin: 10px 0 0 0; }
	/* ]]> */
  </style>
</head>
<body>
<ul id="menu">
	<li id="uploadlink">Datei hochladen</li>
	<li><a href="dotserver.php?download=dot">Datei herunterladen</a></li>
	<li><a href="dotserver.php?download=image">Bild herunterladen</a></li>
</ul>
<div id="sourcediv">
	<form method="post" action="">
		<textarea id="source" name="source"><?php echo file_get_contents( $dotfile ); ?></textarea>
	</form>
</div>
<div id="resultdiv"></div>
<div id="console"></div>
<form method="post" enctype="multipart/form-data" action="" id="uploadform">
	<fieldset>
		<legend>Datei hochladen</legend>
		<p>
			<input type="file" name="dotfile" />
		</p>
		<p>
			<button type="submit">hochladen</button>
			<button id="uploadcancel">abbrechen</button>
		</p>
	</fieldset>
</form>
</body>
</html>