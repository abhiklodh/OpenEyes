<? include 'components/common.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<? include 'components/head.php'; ?>
</head>
<body class="open-eyes">
	<div class="container main" role="main">

		<? include 'components/header-logged-in-no-patient.php'; ?>

		<div class="container content">

			<h1 class="badge">Search</h1>

			<div class="row">
				<div class="large-8 large-centered column">
					<div class="panel search-examples">
						Find a patient by
						<strong>Hospital Number</strong>,
						<strong>NHS Number</strong>,
						<strong>Firstname Surname</strong> or
						<strong>Surname, Firstname</strong>.
					</div>
				</div>
			</div>

			<div class="row">
				<div class="large-8 large-centered column">
					<form class="form panel search">
						<div class="row">
							<div class="large-9 column">
								<input type="text" id="search" placeholder="Enter search..." class="large" />
							</div>
							<div class="large-3 column text-right">
								<button type="submit" class="button long">
									Search
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<? include 'components/footer.php'; ?>
	</div>
</body>
</html>