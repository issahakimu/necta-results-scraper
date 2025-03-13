<?php

namespace NectaResultScraper;

require_once 'vendor/autoload.php';

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Throwable;

/**
 * Necta Result Scraper
 *
 * @author Alex Leo <loealex175@gmail.com>
 */
class NectaResultScraper
{
    public static function results(string $indexNumber)
    {
        $result = new NectaResultScraper();
        return $result->scrape($indexNumber);
    }

    public function scrape(string $indexNumber): array
    {
        try {
            if (!$this->isIndexNumberValid($indexNumber)) {
                return ['error' => 'Invalid index number'];
            } else {
                if (strpos($indexNumber, '.') !== false) {
                    $substrings = explode('.', $indexNumber);
                } elseif (strpos($indexNumber, '/') !== false) {
                    $substrings = explode('/', $indexNumber);
                }
                $year = $substrings[2];
                $schoolNumber = strtolower($substrings[0]);
                $studentNumber = $substrings[1];

                $url = $this->getUrl($year, $schoolNumber);
                $index = ($year > 2018) ? 2 : 0;
                $found = false;
                $result = [];
                $browser = new HttpBrowser(HttpClient::create(['timeout' => 30]));
                $crawler = $browser->request('GET', $url);
                $tables = $crawler->filter("table")->eq($index);
                $examinationNumber = $schoolNumber . "/" . $studentNumber;

                $tables->filter('tr')->each(function ($tr) use ($examinationNumber, &$found, &$result, $url) {
                    $row = [];
                    $tr->filter('td')->each(function ($td) use (&$row) {
                        $row[] = trim($td->text());
                    });

                    if (strtolower($row[0]) == $examinationNumber) {
                        $found = true;
                        $gender = $row[1];
                        $division = $row[3];
                        $points = $row[2];
                        $subjects = $row[4];
                        preg_match_all("/([A-Z]+)\s-\s'([A-Z])'/", $subjects, $matches);
                        $subjects = $matches[1];
                        $grades = $matches[2];
                        $subjectsGrades = array_combine($subjects, $grades);
                        $result = [
                            'gender' => $gender,
                            'division' => $division,
                            'points' => $points,
                            'subjects' => $subjects,
                            'subjects_grades' => $subjectsGrades,
                            'source' => $url,
                        ];
                    }
                });

                if (!$found) {
                    return [
                        'error' => 'Result not found',
                        'status' => 404,
                        'source' => $url,
                        'examination_number' => $examinationNumber,
                    ];
                }
                return $result;
            }
        } catch (Throwable $th) {
            return ['error' => $th->getMessage()];
        }
    }

    private function getUrl(string $year, string $schoolNumber)
    {
        $supportedYears = ['2015', '2017', '2018', '2019', '2020', '2021', '2022', '2023'];
        if (in_array($year, $supportedYears)) {
            return "https://onlinesys.necta.go.tz/results/{$year}/csee/results/{$schoolNumber}.htm";
        }
        if ($year == 2024) {
            return "https://matokeo.necta.go.tz/results/{$year}/csee/CSEE2024/CSEE2024/results/{$schoolNumber}.htm";
        }
        if ($year == 2016) {
            return "https://onlinesys.necta.go.tz/results/2016/csee/results/{$schoolNumber}.htm";
        }
    }

    private function isIndexNumberValid(string $indexNumber): bool
    {
        $regex = '/^S\d{4}\.\d{4}\.\d{4}$|^S\d{4}\/\d{4}\/\d{4}$/';
        return preg_match($regex, $indexNumber) === 1;
    }
}
