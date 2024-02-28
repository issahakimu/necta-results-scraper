# Necta Results Scraper

This package provides a PHP script that allows you to scrape students' results from the National Examinations Council of Tanzania (NECTA) website.

## Installation

You can install this package using Composer:

`composer require alexleotz/necta-results-scraper`

## Usage

To use this package, simply call the static result method of the NectaResultScraper class with the student form four index number string as the argument:

```<?php
require_once('vendor/autoload.php');

use NectaResultScraper\NectaResultScraper;

// Call the static result method of the NectaResultScraper class with the index number string as the argument.
$result = NectaResultScraper::results('S1187/0142/2022');

// Output the result.
echo json_encode($result);
```

## Output

The output is student's result when converted to JSON

```{
  "gender": "F",
  "division": "IV",
  "points": "31",
  "subjects": ["CIV", "HIST", "GEO", "KIISLAMU", "KISW", "ENGL", "BIO", "MATH"],
  "subjects_grades": {
    "CIV": "F",
    "HIST": "D",
    "GEO": "F",
    "KIISLAMU": "F",
    "KISW": "C",
    "ENGL": "F",
    "BIO": "D",
    "MATH": "F"
  },
  "source": "https://onlinesys.necta.go.tz/results/2022/csee/results/s0596.htm"
}
```

## Validation
The supported examination formats are slash-separated and comma-separated formats, e.g., S1187/0142/2022 or S1187.0142.2022. The package handles validation for you.

## Supported years
Currently, we support all years between 2015 and 2023, except for 2016.

## Error
If the student is not found, 404 status code will be returned. For any other error, a code of 500 will be returned.

## Contributing

If you’d like to contribute to this project, please fork the repository and use a feature branch. Pull requests are welcome!

## License

This package is released under the MIT license. See LICENSE for details.
