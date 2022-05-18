<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<html lang="en">
	<head>
			<style>
				body {
					font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;
					background: #3973aa;
					color: #fefeff;
					height: 100vh;
					margin: 0;
				}

				#page {
					display: table;
					height: 100%;
					margin: 0 auto;
					margin-top: -10px;
					width: 70%;
					font-size: 1.9vw;
				}

				#container {
					display: table-cell;
					vertical-align: middle;
				}

				h1,
				h2,
				h3,
				h4,
				h5 {
					font-weight: normal;
					padding: 0;
					margin: 25px 0;
					margin-top: 0;
					font-weight: 300;
				}

				h1 {
					font-size: 6.5em;
					margin-bottom: 10px;
				}

				h2 {
					font-size: 1.5em;
				}

				h4 {
					font-size: 1.4em;
					line-height: 1.5em;
				}

				h5 {
					line-height: 1.1em;
					font-size: 1.3em;
				}

				#details {
					display: flex;
					flex-flow: row;
					flex-wrap: nowrap;
					padding-top: 10px;
				}

				#qr {
					flex: 0 1 auto;
				}

				#image {
					background: white;
					padding: 5px;
					line-height: 0;
				}

				#image img {
					width: 9.8em;
					height: 9.8em;
				}

				#stopcode {
					padding-left: 10px;
					flex: 1 1 auto;
				}

				@media (min-width: 840px) {
					#page {
						font-size: 140%;
						width: 800px;
					}
				}
			</style>
	</head>
	<body>
		<div id="page">
			<div id="container">
				<h2>We're just collecting some error info, and then we'll restart for you.</h2>
				<h2><span id="percentage">0</span>% complete</h2>
				<div id="details">
				<div id="qr">
					<div id="image">
					<img src="<?= base_url('assets/images/qr.png') ?>" alt="QR Code" />
					</div>
				</div>
				<div id="stopcode">
				<h4>A PHP Error was encountered</h4>
					<div style="min-height:155px;background-color:black; font-size:12pt; height:155px; overflow-y: auto;">
					<p>Severity: <?php echo $severity; ?></p>
					<p>Message:  <?php echo $message; ?></p>
					<p>Filename: <?php echo $filepath; ?></p>
					<p>Line Number: <?php echo $line; ?></p>

					<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

						<p>Backtrace:</p>
						<?php foreach (debug_backtrace() as $error): ?>

							<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

								<p style="margin-left:10px">
								File: <?php echo $error['file'] ?><br />
								Line: <?php echo $error['line'] ?><br />
								Function: <?php echo $error['function'] ?>
								</p>

							<?php endif ?>

						<?php endforeach ?>

					<?php endif ?>
					</div>
					
				</div>
				</div>
			</div>
		</div>
		<script>
			var percentageElement = document.getElementById("percentage");
			var percentage = 0;

			function process() {
			percentage += parseInt(Math.random() * 80);
			if (percentage > 100) {
				percentage = 100;
				if(percentage = 100){
					location.replace("<?= site_url(); ?>");
					return;
				}
			}
			percentageElement.innerText = percentage;
				processInterval();
			}

			function processInterval() {
				setTimeout(process, Math.random() * (1000 - 500) + 500)
			}
			processInterval();
		</script>
	</body>
</html>
<?php 
die();
?>