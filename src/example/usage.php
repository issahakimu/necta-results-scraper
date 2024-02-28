<?php
require_once('vendor/autoload.php');

use NectaResultScraper\NectaResultScraper;

// Call the static result method of the NectaResultScraper class with the index number string as the argument.
$result = NectaResultScraper::results('S0596/0001/2022');

// Output the result.
echo json_encode($result);

