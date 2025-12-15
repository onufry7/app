<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="css/style.css">
		<title>App</title>
	</head>
	<body>
		<div class="container">
			<nav>
				<a href="index.php?action=users">Users list</a>
				<a href="index.php?action=groups">Groups list</a>
			</nav>

            <?php if(isset($alert)): ?>
                <div class="alert"><?= $alert ?></div>
            <?php endif ?>

			<main>
				<?=  $main ?? '' ?>
			</main>
			<footer>Stopka serwisu</footer>
		</div>
	</body>
</html>