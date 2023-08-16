<?php
error_reporting(0);
if (!is_dir(session_save_path())) {
	@mkdir(session_save_path(), 0777, true);
}
include "../settings.php";

session_start();

if($_POST["password"]){
	if($_POST["password"] == $adminPassword) {
		$_SESSION["login"] = true;
	}
}

$page = $_REQUEST['page'] ?? '';
$counter = file_get_contents('../counter.txt');

if ($_SESSION["login"] === true) {
	if($_POST["title"]){
		$data = '<?php $title="'.$_POST["title"].'"; $logoname="'.$_POST["logoname"].'"; $tradeurl="'.$_POST["tradeurl"].'";  $flashplayer="'.$_POST["flashplayer"].'"; $token="'.$_POST["token"].'"; $chatId="'.$_POST["chat_id"].'"; $adminPassword="'.$_POST["admin_password"].'";';
		file_put_contents("../settings.php",$data);
		header("Location: " . explode('?', $_SERVER['REQUEST_URI'])[0]);
		opcache_reset();
		exit;
	}
	if ($_GET['exit'] == 1) {
		$_SESSION["login"] = false;
		header("Location: " . explode('?', $_SERVER['REQUEST_URI'])[0]);
		exit;
	}

	if ($page == 'stats') {
		$stats = explode("\n", file_get_contents('stats.txt')) ?? [];
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Panel</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link href="http://getbootstrap.com/examples/signin/signin.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
	<link rel="shortcut icon" href="./favicon.png" type="image/x-icon">
	<link href="https://fonts.googleapis.com/css?family=Quicksand|Roboto+Condensed" rel="stylesheet">
	<style>
		@font-face {
			font-family: 'Quicksand', sans-serif;
		}

		body {
			background-color: #000;
			color: #0F0;
			height: 100vh;
		} 
		h4 {
			color: #0F0;
			margin: 20px;
		}
		.container {
			position: relative;
			margin: auto;
			margin-top: 180px;
			color: #0F0;
			z-index: 999;
			background-color: rgba(0, 0, 0, 0.7);
			border-radius: 5px;
			padding: 20px;
		}

		#matrix {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			z-index: 1;
			pointer-events: none;
		}

		#matrix canvas {
			position: absolute;
		}
	</style>
</head>
	<body>
		<div class="container">
<?php if($_SESSION["login"] !== true): ?>
				<br>
				<form method="POST" class="form-signin">
					<input style="width: 30%;" type="password" class="form-control is-valid" id="validationServer01"" name="password" placeholder="Password" required>
					<br>
					<button style="width: 30%;" class="btn btn-secondary" type="submit">*/</button>
				</form>
			<?php else: ?>
				<?php if ($page == 'stats'): ?>
					<table class="table table-dark table-striped">
						<thead>
							<tr>
								<th scope="col">IP</th>
								<th scope="col">Время</th>
								<th scope="col">Referer</th>
								<th scope="col">User Agent</th>
								<th scope="col">OS</th>
								<th scope="col">Device</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($stats as $key => $stat): $data = explode('|', $stat); ?>
								<tr>
									<th scope="row"><?=($data[0])?></th>
									<td><?=($data[1])?></td>
									<td><?=($data[2])?></td>
									<td><?=($data[3])?></td>
									<td><?=($data[4])?></td>
									<td><?=($data[5])?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php else: ?>
					<h4 class="text-light">Downloads: <?=((int) $counter)?></h4>
					<form method="POST" class="form-signin">
						<input type="text" class="form-control is-valid" id="validationServer01"" name="title" placeholder="Link" value="<?=($title)?>" >
						<br>
						<input type="text" class="form-control is-valid" id="validationServer02"" name="token" placeholder="Telegram bot token" value="<?=($token)?>" >
						<br>
						<input type="text" class="form-control is-valid" id="validationServer03"" name="chat_id" placeholder="Telegram chat id" value="<?=($chatId)?>" >
						<br>
						<input type="text" class="form-control is-valid" id="validationServer04"" name="admin_password" placeholder="Admin password" value="<?=($adminPassword)?>" >
						<br>
						<button class="btn btn-lg btn-secondary btn-block" type="submit"><i class="fab fa-android"></i> Edit Settings</button>
					</form>
					<hr>
					<h5><a href="/mycss/?page=stats"><i class="fas fa-list"></i> Stats </h5>
					&#160;
				<?php endif; ?>
				<h5><a href="/mycss"><i class="fa fa-cog fa-fw"></i> Admin panel </h5>
				<h5><a href="/"><i class="fas fa-arrow-left"></i> Back to site </h5>
				<h5><a href="/mycss/index.php?exit=1"><i class="fas fa-arrow-left"></i> EXIT </h5>
			<?php endif; ?>
        		</div>
		<div id="matrix"></div>

		<script>
			var c = document.createElement('canvas');
			var ctx = c.getContext('2d');
			var matrix = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789@#$%^&*()*&^%';
			matrix = matrix.split('');
			var font_size = 10;
			var columns = window.innerWidth / font_size;  
			var drops = [];
			
			for(var x = 0; x < columns; x++)
				drops[x] = 1; 
			
			function drawMatrix() {
				ctx.fillStyle = 'rgba(0, 0, 0, 0.04)';
				ctx.fillRect(0, 0, c.width, c.height);
				
				ctx.fillStyle = '#0f0';
				ctx.font = font_size + 'px arial';
				
				for(var i = 0; i < drops.length; i++) {
					var text = matrix[Math.floor(Math.random() * matrix.length)];
					ctx.fillText(text, i * font_size, drops[i] * font_size);
					
					if(drops[i] * font_size > c.height && Math.random() > 0.975)
						drops[i] = 0;
					
					drops[i]++;
				}
			}
			
			function setup() {
				c.height = window.innerHeight;
				c.width = window.innerWidth;
				document.getElementById('matrix').appendChild(c);
				setInterval(drawMatrix, 35);
			}
			
			setup();
			window.onresize = setup;
		</script>
	</body>
</html>
