<?php
require_once('vendor/autoload.php');

use NectaResultScraper\NectaResultScraper;
// Pass the index number string and the level as the arguments.
$result = NectaResultScraper::results('S0310/0501/2023', 'acsee');
// Output the result.
echo json_encode($result);
