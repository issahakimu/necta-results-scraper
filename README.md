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

Use the static `results` method of the `NectaResultScraper` class, passing the student's index number as the first argument.

The second argument specifies the examination level and is optional. It defaults to `csee` (O-Level). Use `acsee` for A-Level results.

Example usage:

```php
<?php
require_once('vendor/autoload.php');

use NectaResultScraper\NectaResultScraper;

// Retrieve O-Level (CSEE) results:
$result = NectaResultScraper::results('S1832/0036/2024'); // Defaults to 'csee'

// Or explicitly specify O-Level:
$result = NectaResultScraper::results('S1832/0036/2024', 'csee');

// Retrieve A-Level (ACSEE) results:
$result = NectaResultScraper::results('S0310/0501/2023', 'acsee');

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

## Index Number Format

The package supports index numbers in slash-separated (e.g., `S1187/0142/2022`) and dot-separated (e.g., `S1187.0142.2022`) formats. Input validation is handled internally.

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

## Inspiration

This package is inspired by the [NECTA-API](https://github.com/vincent-laizer/NECTA-API) Python package by [vincent laizer](https://github.com/vincent-laizer).

## Contributing

Contributions are welcome! Fork the repository, create a feature branch, and submit a pull request. For personal support or inquiries, please contact 0748333586.

## License

This package is released under the MIT License. See `LICENSE` for details.
