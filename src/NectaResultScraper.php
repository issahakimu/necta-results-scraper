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
    public static function results($index_number)
    {
        $result = new NectaResultScraper();
        return $result->scrape($index_number);
    }

    public function scrape($index_number)
    {
        try {
            if (!$this->is_index_number_valid($index_number)) {
                return json_encode(['error' => 'Invalid index number']);
            } else {
                if (strpos($index_number, '.') !== false) {
                    $substrings = explode('.', $index_number);
                } elseif (strpos($index_number, '/') !== false) {
                    $substrings = explode('/', $index_number);
                }
                $year = $substrings[2];
                $school_number = strtolower($substrings[0]);
                $student_number = $substrings[1];

                $url = $this->url($year, $school_number);
                $index = ($year > 2018) ? 2 : 0;
                $found = false;
                $result = [];
                $browser = new HttpBrowser(HttpClient::create(['timeout' => 30]));
                $crawler = $browser->request('GET', $url);
                $tables = $crawler->filter("table")->eq($index);
                $examination_number = $school_number . "/" . $student_number;

                $tables->filter('tr')->each(function ($tr) use ($examination_number, &$found, &$result, $url) {
                    $row = [];
                    $tr->filter('td')->each(function ($td) use (&$row) {
                        $row[] = trim($td->text());
                    });

                    if (strtolower($row[0]) == $examination_number) {
                        $found = true;
                        $gender = $row[1];
                        $division = $row[3];
                        $points = $row[2];
                        $subjects = $row[4];
                        preg_match_all("/([A-Z]+)\s-\s'([A-Z])'/", $subjects, $matches);
                        $subjects = $matches[1];
                        $grades = $matches[2];
                        $subjects_grades = array_combine($subjects, $grades);
                        $result = [
                            'gender' => $gender,
                            'division' => $division,
                            'points' => $points,
                            'subjects' => $subjects,
                            'subjects_grades' => $subjects_grades,
                            'source' => $url,
                        ];
                    }
                });

                if (!$found) {
                    return [
                        'error' => 'Result not found',
                        'status' => 404,
                        'source' => $url,
                        'examination_number' => $examination_number,
                    ];
                }
                return $result;
            }
        } catch (Throwable $th) {
            return ['error' => $th->getMessage()];
        }
    }

    private function url($year, $school_number)
    {
        $supported_years = ['2015', '2017', '2018', '2019', '2020', '2021', '2022', '2023'];
        if (in_array($year, $supported_years)) {
            return "https://onlinesys.necta.go.tz/results/{$year}/csee/results/{$school_number}.htm";
        }
        if ($year == 2024) {
            return "https://matokeo.necta.go.tz/results/{$year}/csee/CSEE2024/CSEE2024/results/{$school_number}.htm";
        }
        if ($year == 2016) {
            return "https://onlinesys.necta.go.tz/results/2016/csee/results/{$school_number}.htm";
        }
    }

    private function is_index_number_valid($index_number)
    {
        $regex = '/^S\d{4}\.\d{4}\.\d{4}$|^S\d{4}\/\d{4}\/\d{4}$/';
        return preg_match($regex, $index_number);
    }
}
