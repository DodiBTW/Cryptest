<!DOCTYPE html>
<html>
   <head>
  	<meta charset="utf-8" />
  	<title>Cryptest</title>
  	<link href="style.css" rel="stylesheet" />
   </head>

   <body>
  	<h1>Le super blog de l'AVBN !</h1>

  	<div class="news">
		<?php
			echo number_format($currentPrice["price"], 1)
		?>
  	</div>
   </body>
</html>