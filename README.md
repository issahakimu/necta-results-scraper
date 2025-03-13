# Necta Results Scraper

This package provides a PHP script to scrape student results from the National Examinations Council of Tanzania (NECTA) website.

**Requirements:**

- PHP 7.0 or higher
- Composer

## Installation

Install the package using Composer:

```bash
composer require alexleotz/necta-results-scraper
```

## Usage

Use the static `results` method of the `NectaResultScraper` class, passing the student's index number as argument.

Example usage:

```php
<?php
require_once('vendor/autoload.php');

use NectaResultScraper\NectaResultScraper;

// Retrieve O-Level (CSEE) results:
$result = NectaResultScraper::results('S1832/0036/2024') // only csee candidate is supported;

// Output the results as JSON:
echo json_encode($result);
```

Run the script from your terminal:

```bash
php index.php
```

## Output

The script returns the student's results in JSON format:

```json
{
  "gender": "M",
  "division": "II",
  "points": "19",
  "subjects": [
    "CIV",
    "HIST",
    "GEO",
    "KISW",
    "ENGL",
    "ENG",
    "PHY",
    "CHEM",
    "BIO",
    "MATH"
  ],
  "subjects_grades": {
    "CIV": "C",
    "HIST": "B",
    "GEO": "C",
    "KISW": "C",
    "ENGL": "C",
    "ENG": "C",
    "PHY": "D",
    "CHEM": "C",
    "BIO": "B",
    "MATH": "C"
  },
  "source": "https://matokeo.necta.go.tz/results/2024/csee/CSEE2024/CSEE2024/results/s1832.htm"
}
```

## Index Number Format

The package supports index numbers in slash-separated (e.g., `S1832/0036/2024`) and dot-separated (e.g., `S1832.0036.2024`) formats. Input validation is handled internally.

## Supported Years

This package supports result scraping for years from 2015 to the present.

## Error Handling

- **Result Not Found:** If the student's results are not found, the script returns a JSON response with an `error` message and a `404` status code.
- **Other Errors:** For other errors, such as network issues or parsing errors, a JSON response with an `error` message is returned.

## Composer Stability

If you encounter the "Could not find a version" error, ensure your `composer.json` file includes:

```json
{
  "minimum-stability": "stable"
}
```

Then, run `composer update` again.

## Contributing

Contributions are welcome! Fork the repository, create a feature branch, and submit a pull request. For personal support or inquiries, please contact 0748333586.

## License

This package is released under the MIT License. See `LICENSE` for details.
