<?php
require_once('vendor/autoload.php');

use NectaResultScraper\NectaResultScraper;

// Retrieve results by providing the index number.
// The second argument, specifying the examination level, defaults to 'csee' (O-Level).
// Use 'acsee' for Advanced Certificate of Secondary Education Examination (A-Level).
$result = NectaResultScraper::results('S1832/0036/2024'); //Defaults to csee

// Output the results in JSON format.
echo json_encode($result);
