<html lang='en'>

<body>
	<?php

	require_once "functions.php";
	require_once "user.php";
	require_once "database.php";

	$tables = array(
		USER_TABLE => $user_table_definition,
		TRIP_TABLE => $trip_table_definition,
		PLACE_TABLE => $place_table_definition,
		TRIP_REQUEST_TABLE => $trip_request_table_definition
	);

	foreach ($tables as $name => $definition) {
		global $connection;
		if (!mysqli_query($connection, "CREATE TABLE IF NOT EXISTS $definition"))
			die("Failed to create table: $name.: " . mysqli_error() . "<br>");
		else
			echo "Created table $name if it didn't exist.<br>";
	}

	mysqli_close($connection);

	?>
</body>

</html>