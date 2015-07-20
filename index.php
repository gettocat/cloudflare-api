<?php

require_once './cloudflareapi.class.php';
require_once './cloudflare.class.php';

$result = CloudFlare::get()->addDomain("google.ru");
/*
  array(16) {
    ["id"]=>	 string(32) "6f6f91d90253579b03454f279699171b"
    ["name"]=>	 string(17) "google.ru"
    ["status"]=>	 string(7) "pending"
    ["paused"]=>	 bool(false)
    ["type"]=>	 string(4) "full"
    ["development_mode"]=>	 int(0)
    ["name_servers"]=>	 array(2) {
      [0]=>	 string(23) "chris.ns.cloudflare.com"
      [1]=>	 string(21) "mia.ns.cloudflare.com"
    }
    ["original_name_servers"]=>	 array(2) {
      [0]=>	 string(21) "mia.ns.cloudflare.com"
      [1]=>	 string(23) "chris.ns.cloudflare.com"
    }
    ["original_registrar"]=>	 NULL
    ["original_dnshost"]=>	 NULL
    ["modified_on"]=>	 string(27) "2015-07-18T15:02:17.249004Z"
    ["created_on"]=>	 string(27) "2015-07-18T15:02:17.205609Z"
    ["meta"]=>	 array(6) {
      ["step"]=>	 int(4)
      ["wildcard_proxiable"]=>	 bool(false)
      ["custom_certificate_quota"]=>	 int(0)
      ["page_rule_quota"]=>	 string(1) "3"
      ["phishing_detected"]=>	 bool(false)
      ["multiple_railguns_allowed"]=>	 bool(false)
    }
    ["owner"]=>	 array(3) {
      ["type"]=>	 string(4) "user"
      ["id"]=>	 string(32) "id user"
      ["email"]=>	 string(14) "email"
    }
    ["permissions"]=>	 array(16) {
      [0]=>	 string(15) "#analytics:read"
      [1]=>	 string(13) "#billing:edit"
      [2]=>	 string(13) "#billing:read"
      [3]=>	 string(17) "#cache_purge:edit"
      [4]=>	 string(17) "#dns_records:edit"
      [5]=>	 string(17) "#dns_records:read"
      [6]=>	 string(18) "#organization:edit"
      [7]=>	 string(18) "#organization:read"
      [8]=>	 string(9) "#ssl:edit"
      [9]=>	 string(9) "#ssl:read"
      [10]=>	 string(9) "#waf:edit"
      [11]=>	 string(9) "#waf:read"
      [12]=>	 string(10) "#zone:edit"
      [13]=>	 string(10) "#zone:read"
      [14]=>	 string(19) "#zone_settings:edit"
      [15]=>	 string(19) "#zone_settings:read"
    }
    ["plan"]=>	 array(9) {
      ["id"]=>	 string(32) "0feeeeeeeeeeeeeeeeeeeeeeeeeeeeee"
      ["name"]=>	 string(12) "Free Website"
      ["price"]=>	 int(0)
      ["currency"]=>	 string(3) "USD"
      ["frequency"]=>	 string(0) ""
      ["legacy_id"]=>	 string(4) "free"
      ["is_subscribed"]=>	 bool(true)
      ["can_subscribe"]=>	 bool(true)
      ["externally_managed"]=>	 bool(false)
    }
  
}*/

//добавит запись @google.ru A 127.0.0.1
$res = CloudFlare::get()->addDomainZone("6f6f91d90253579b03454f279699171b", "A", "@", "127.0.0.1");
// то же самое для MX, A, AAAA, LOC, TXT и других типов записей.

/**
  array(13) {
    ["id"]=>	 string(32) "e16128aaafe54fdbe8aa55beecadd994"
    ["type"]=>	 string(1) "A"
    ["name"]=>	 string(14) "google.ru"
    ["content"]=>	 string(14) "127.0.0.1"
    ["proxiable"]=>	 bool(true)
    ["proxied"]=>	 bool(false)
    ["ttl"]=>	 int(1)
    ["locked"]=>	 bool(false)
    ["zone_id"]=>	 string(32) "6f6f91d90253579b03454f279699171b"
    ["zone_name"]=>	 string(14) "google.ru"
    ["modified_on"]=>	 string(27) "2015-07-18T08:28:01.921209Z"
    ["created_on"]=>	 string(27) "2015-07-18T08:28:01.921209Z"
    ["meta"]=>	 array(1) {
      ["auto_added"]=>	 bool(false)
    }
 }
 */

//как и предыдущий метод
$res = CloudFlare::get()->editDomainZone("6f6f91d90253579b03454f279699171b", "e16128aaafe54fdbe8aa55beecadd994", "CNAME", "@", "yandex.ru");

/**
  array(13) {
    ["id"]=>	 string(32) "e16128aaafe54fdbe8aa55beecadd994"
    ["type"]=>	 string(1) "A"
    ["name"]=>	 string(14) "google.ru"
    ["content"]=>	 string(14) "127.0.0.1"
    ["proxiable"]=>	 bool(true)
    ["proxied"]=>	 bool(false)
    ["ttl"]=>	 int(1)
    ["locked"]=>	 bool(false)
    ["zone_id"]=>	 string(32) "6f6f91d90253579b03454f279699171b"
    ["zone_name"]=>	 string(14) "google.ru"
    ["modified_on"]=>	 string(27) "2015-07-18T08:28:01.921209Z"
    ["created_on"]=>	 string(27) "2015-07-18T08:28:01.921209Z"
    ["meta"]=>	 array(1) {
      ["auto_added"]=>	 bool(false)
    }
 }
 */

//удалит предыдущую запись. Возвращает просто id зоны
$res = Cloudflare::get()->removeDomainZone("6f6f91d90253579b03454f279699171b", "e16128aaafe54fdbe8aa55beecadd994");

/**
  array(1) {
    ["id"]=>	 string(32) "e16128aaafe54fdbe8aa55beecadd994"
 }
 */

//удалит домен из cloudflare.com. Вернет его id.
$res = CloudFlare::get()->removeDomain("6f6f91d90253579b03454f279699171b");
/**
  array(1) {
    ["id"]=>	 string(32) "6f6f91d90253579b03454f279699171b"
 }
 */

//получает список доменов
$list = CloudFlare::get()->getDomainList(1, 50, 'active', 'status', 'desc');

//получает информацию о домене
$list = CloudFlare::get()->getDomainInfo("6f6f91d90253579b03454f279699171b");

//получает  список зон определенного домена
$list = CloudFlare::get()->getDomainZones("6f6f91d90253579b03454f279699171b", 1, 20, 'name', 'desc');
