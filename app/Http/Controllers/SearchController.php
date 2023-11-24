<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calendar\Event;
use App\Models\Confuse;
use Illuminate\Support\Str;
use App\Helper\Traits\Search;

class SearchController extends Controller
{
  use Search;
  
  public function search(Request $request) {
    return inertia('Result', [
      'events' => [],
      'query' => [],
    ]);
  }
  
  public function searchResult(Request $request) {
    return response()->json($this->getSearchResults())
    ->header('Content-Type', 'application/json; charset=utf-8');
  }
  
  private function identifyArgument($arg) {
    if (is_string($arg)) {
        echo "The argument is a string.";
    } elseif (is_numeric($arg) && filter_var($arg, FILTER_VALIDATE_INT) !== false) {
        echo "The argument is a number.";
    } elseif (preg_match('/^[০-৯]+$/u', $arg)) {
        echo "The argument is a Bengali numeral.";
    } else {
        echo "Unable to identify the argument.";
    }
}

  private function processDetailMatches(array $events) {
    $result = [];
    $allowed_tags = '<h1><h2><h4><li>';
    foreach ($events as $event) {
      //$text = str_replace('<li>', '/n', str_replace('</li>', '. ', strip_tags($event->detail, $allowed_tags)));
      $text = $event->detail;
      $result[] = $this->getPrevious('', request()->search, $text);
    }
    return $result;
  }

  
  private function getPrevious($tags, $matching, $data) {
    $textPosition = mb_stripos($data, $matching);
    $previousText = mb_substr($data, 0, $textPosition);
    
    preg_match_all('/<(h1|h2|h4)[^>]*>(.*?)<\/\1>/is', $previousText, $matches);
    $previousHeading = end($matches[2]);
    if (!empty($matches[2])) {
        return end($matches[2]);
    }
    return null;
  }
  
  
  private function identifyBirthplace($text) {
    $keywords = array("জন্মস্থান", "জন্ম স্থান", "জন্মতালিকা", "জেলা", "অঞ্চল");

    foreach ($keywords as $keyword) {
        $keyword = html_entity_decode($keyword, ENT_QUOTES, 'UTF-8'); // Decode HTML entities
        if (mb_strpos($text, $keyword) !== false) {
            // Extract the birthplace from the text
            $birthplace = mb_substr($text, mb_strpos($text, $keyword) + mb_strlen($keyword));
            // Remove leading and trailing spaces
            $birthplace = trim($birthplace, " \t\n\r\0\x0B।,।");
            return $birthplace;
        }
    }

    // Return null if birthplace not found
    return null;
}

  
}
