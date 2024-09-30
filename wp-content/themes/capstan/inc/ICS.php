<?php
class ICS {
    public $data   = [];
    public $name   = "";
    public $start  = [
        "BEGIN:VCALENDAR",
        "VERSION:2.0",
        "PRODID:-//iuslaboris.com//IUS Laboris",
        "X-WR-CALNAME:IUS Laboris Webinars",
        "CALSCALE:GREGORIAN"
    ];
    public $end    = ["END:VCALENDAR"];

    public function __construct($name) {
        $this->name = $name;
    }
    public function add($start, $end, $name, $description, $url = "") {
        // $tz = date_default_timezone_get();
        // date_default_timezone_set('CET');
        date_default_timezone_set("UTC");

        $event = [];
        $event[] = "BEGIN:VEVENT";
        $event[] = "DTSTART:".date("Ymd\THis\Z",$start);
        $event[] = "DTEND:".date("Ymd\THis\Z",$end);
        $event[] = "DTSTAMP:".date("Ymd\THis\Z");
        $event[] = "SEQUENCE:0";
        $event[] = "STATUS:CONFIRMED";
        $event[] = "UID:".bin2hex(random_bytes(20));
        $event[] = "ORGANIZER;CN=IUS Laboris:mailto:info@iuslaboris.com";
        $event[] = "LOCATION:".implode("\r\n ", str_split($url, 75));
        $event[] = "SUMMARY:".implode("\r\n ", str_split($name, 75));
        $event[] = "DESCRIPTION:".implode("\r\n ", str_split($description, 75));
        if (!empty($url)) $event[] = "URL:".implode("\r\n ", str_split($url, 75));
        $event[] = "TRANSP:OPAQUE";
        $event[] = "END:VEVENT";

        $this->data = array_merge($this->data, $event);

        // date_default_timezone_set($tz);
    }
    public function save($dir="") {
        $filename = $dir."/".$this->name.".ics";
        file_put_contents($filename, $this->getData());
        return $filename;
    }
    public function getData() {
        return implode("\r\n", array_merge($this->start, $this->data, $this->end))."\r\n";
    }
}
?>