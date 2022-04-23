<?php
class SynoDLMSearchSubsPlease
{
  public function __construct()
  {
  }

  public function prepare($curl, $query)
  {
    $url = "https://subsplease.org/api/?f=search&tz=" . date_default_timezone_get() . "&s=" . urlencode($query);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($curl);
    return $response;
  }

  public function parse($plugin, $response)
  {
    $count = 0;
    $response = json_decode($response, true);
    foreach ($response as $key => $value) {
      $datetime = date("Y-m-d H:i:s", strtotime($value["release_date"]));
      $page = "https://subsplease.org/shows/" . $value["page"];

      foreach ($value["downloads"] as $download) {
        $title = $key . " (" . $download["res"] . "p)";
        $plugin->addResult($title, $download["magnet"], 0, $datetime, $page, "", 0, 0, $value["show"]);
        $count++;
      }
    }
    return $count;
  }
}
?>
