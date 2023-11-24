<?php
namespace App\Helper\Traits;

use App\Models\Calendar\Event;
use App\Models\Confuse;
use Illuminate\Support\Str;
use DOMDocument;

trait Search{
  
  public function getSearchResults(){
    $events = $this->parseEvents();
    $confuse = $this->parseConfuses();
    return [
      ...$events,
      ...$confuse,
    ];
  }
  
  private function parseConfuses() {
    $search = request()->search;
    $result = [];
    $confuses = Confuse::where('name', 'like', "%$search%")
                        ->orWhere('detail', 'like', "%$search%")
                        ->select('name', 'catagory', 'detail')
                        ->get();
    foreach ($confuses as $confuse) {
      $title = $confuse->name; 
      if($confuse->catagory) $title .= ' ('.$confuse->catagory.') ';
      $title .= $confuse->detail;
      $result[] = [
        'title' => $title,
        'description' => null,
        'catagory' => 'Confuse',
      ];
    }
    return $result;
  }
  
  private function parseEvents() {
    $text = request()->search;
    $result = [];
    $shouldProcess = [];
    $events = Event::where('event', 'like', "%$text%")
              ->orWhere('detail', 'like', "%$text%")
              ->select('event', 'detail', 'date', 'year')
              ->get();
    foreach ($events as $event) {
      $date = $event->year;
      if($event->date) $date = date('d M, Y', strtotime($event->date));
      if(Str::contains($event->event, $text)) {
        $result[] = [
          'title' => $event->event .' '. $date,
          'description' => null,
          'catagory' => 'event',
        ];
        continue;
      }
      $shouldProcess[] = $event;
    }
    
    foreach ($shouldProcess as $data) {
      $delemiter = false;
      foreach ($this->getKeywordDelimiter() as $keyword){
        
        if(strpos($data->event, $keyword)){
          $delemiter = $keyword;
          break;
        }
      }
      if($delemiter) {
        $title = explode($keyword, $data->event)[0];
      }else {
        $title = $data->event;
      }
      $arrayEvents = $this->searchAndExtract($data->detail);
      foreach ($this->addTitleToEvent($title, $arrayEvents) as $row) {
        $result[] = [
          'title' => $row,
          'detail' => 'deep',
          'catagory' => 'event',
        ];
      }
    }
    return $result;
  }
  
  private function addTitleToEvent($title, array $events){
    $result = [];
    foreach ($events as $event){
      $result[] = $title .' '. $event;
    }
    return $result;
  }
  
  private function getKeywordDelimiter(){
    return [
      'জন্ম',
      'জন্মগ্রহণ ',
      'জন্ম গ্রহণ',
      'মৃত্যু',
      'মৃত্যুবরণ',
      'মৃত্যু বরণ',
      'শহীদ',
      'হত্যা',
      'নিহত',
      'প্রতিষ্ঠিত',
      'অনুষ্ঠিত',
      'গঠিত',
      'গঠন',
      'প্রকাশিত', 
      'উপস্থাপন',
      'নোবেল',
      'চালু',
      'শুরু',
      'পাস',
      'করা',
      'লাভ',
      'আবিষ্কার',
      'নির্বাচত',
      'প্রত্যাহার',
      'জারি',
    ];
  }

public function searchAndExtract($html){
  $search = request()->search;
  $result = [];
  $dom = new DOMDocument();
  $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
  @$dom->loadHTML($html);
  $xpath = new \DOMXPath($dom);
  $query = "//text()[contains(., '$search')]/..";
  $matchingElements = $xpath->query($query);
  
  
  foreach ($matchingElements as $match) {
    $nodeValues = [];
    $parent = join(' ', $this->getDeeperParentNode($match));
    $previousHeadline = $xpath->query("preceding::h1|preceding::h2|preceding::h3|preceding::h4|preceding::h5|preceding::h6[1]", $match)[0];
    if ($previousHeadline) {
        $parentContent = trim($previousHeadline->textContent.' '. $parent .' '.$match->childNodes->item(0)->textContent);
        $result[] = $parentContent;
    }else {
        $result[] = trim($parent.' '.$match->childNodes->item(0)->textContent);
    }
  }
  
  return $result;
}
private function getDeeperParentNode($node){
  $result = [];
  $nodeData = $node;
  for($i = 0; $i < 10; $i++){
    $nodeData = $this->getParentNode($nodeData);
    if(!$nodeData)  break;
    $result[] = $nodeData->childNodes->item(0)->textContent;
  }
  return array_reverse($result);
}

private function getParentNode($node){
  $allowed_tags = ['ul', 'ol'];
  $forbidden_tags = ['div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'body', 'html'];
  $parentNode = $node->parentNode;
  if(!in_array($parentNode->nodeName, $allowed_tags) && in_array($parentNode->nodeName, $forbidden_tags)) return false;
  $parent = $parentNode->parentNode;
  if(!in_array($parent->nodeName, $allowed_tags) && in_array($parent->nodeName, $forbidden_tags)) return false;
  return $parent;
}

  
  
}