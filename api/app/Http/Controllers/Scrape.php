<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class Scrape extends ApiController {

    private $dataKey = '';

    public function get() {
        Log::info('Scrape started');
//        $this->distribute('GET','/ders-veren/odtu-mezunu-elek-elektronik-muhendisi-mustafa-y-173722', 0, 'key');
//        $this->distribute('GET','/ders-veren/akademisyen-bilgisayar-muhendisligi-zahid-g-8861', 0, 'key');
        $this->getGroups('/ders-verenler');
    }

    private function distribute($method, $path, $index, $operation) {
        if ($method == 'GET') {
            $pageContent = $this->sendGETRequest($path, $index);
            if ($pageContent && strpos($pageContent, 'request error: ') === false) {
                $page = $pageContent;
                if ($operation === 'key') {
                    $this->getKey($page, $path);
                } else {
                    $this->getProfiles($page, $path, $index);
                }
            } else {
                Log::error(json_encode($pageContent));
            }
        }
    }

    private function sendGETRequest($path, $index) {
        $url = 'https://www.ozelders.com' . $path . ($index > 0 ? ('/' . $index) : '');
        if (is_callable('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'Accept-Encoding: gzip, deflate, br',
                'Accept-Language: en-US,en;q=0.9,tr;q=0.8,az;q=0.7',
                'Cache-Control: no-cache',
                'Connection: keep-alive',
                'Cookie: _ufpx8576=; _ga=GA1.2.1184402087.1581196763; __zlcmid=weitmhkdPURx1G; _gid=GA1.2.1143066787.1587841421; _utdx4123=izmir; ASP.NET_SessionId=m40burd0b1wtdzr31tllebgw; _utfx3876=F409BE3718D185A7F0C54EED4EF695DC53AA61ACB391EBF88D19ABE534D1AE24D1F9EB3AB02D1F0A216507A1945BBEE90FE0F289B12C915C35691EEC70E3024BB59390ADE8B17DD6B6892B88C0AB8C2AC8D149087E5066F5A55C814672CCB5CD13FA93DA125CA0BE5F2B0A1F46A35AD9958ACEFAEB286BD0EA3BD66F0CDA2E11057E56BC33500E4E4A145EDDD639438B; ARRAffinity=3899a4c246ab409ce24157990cab89a709e4705f2b410d297e106a90486cd1f4; _gat=1',
                'Host: www.ozelders.com',
                'Pragma: no-cache',
                'Sec-Fetch-Dest: document',
                'Sec-Fetch-Mode: navigate',
                'Sec-Fetch-Site: none',
                'Sec-Fetch-User: ?1',
                'Upgrade-Insecure-Requests: 1',
                'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36'
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_ENCODING , "gzip");
            curl_setopt($ch, CURLOPT_URL, $url);
            $data = curl_exec($ch);
            curl_close($ch);
        }
        if (empty($data) || !is_callable('curl_init')) {
            $opts = array( 'https' => array ('header' => 'Connection: close') );
            $context = stream_context_create($opts);
            $headers = get_headers($url);
            $httpcode = substr($headers[0], 9, 3);
            if ($httpcode == '200')
                $data = file_get_contents($url, false, $context);
            else {
                $data = 'request error: ' . $httpcode;
            }
        }
        return $data;
    }

    private function sendPOSTRequest($path, $key) {
        $url = 'https://www.ozelders.com/ajax/telefon-goster/';
        $params = array('key' => $key);
        $contentLength = (strlen($key) + 4);
        if (is_callable('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'Accept-Encoding: gzip, deflate, br',
                'Accept-Language: en-US,en;q=0.9,tr;q=0.8,az;q=0.7',
                'Cache-Control: no-cache',
                'Connection: keep-alive',
                'Content-Length: ' . $contentLength,
                'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                'Cookie: _ufpx8576=; _ga=GA1.2.1184402087.1581196763; __zlcmid=weitmhkdPURx1G; _gid=GA1.2.1143066787.1587841421; _utdx4123=izmir; ASP.NET_SessionId=m40burd0b1wtdzr31tllebgw; _utfx3876=F409BE3718D185A7F0C54EED4EF695DC53AA61ACB391EBF88D19ABE534D1AE24D1F9EB3AB02D1F0A216507A1945BBEE90FE0F289B12C915C35691EEC70E3024BB59390ADE8B17DD6B6892B88C0AB8C2AC8D149087E5066F5A55C814672CCB5CD13FA93DA125CA0BE5F2B0A1F46A35AD9958ACEFAEB286BD0EA3BD66F0CDA2E11057E56BC33500E4E4A145EDDD639438B; ARRAffinity=3899a4c246ab409ce24157990cab89a709e4705f2b410d297e106a90486cd1f4; _gat=1',
                'Host: www.ozelders.com',
                'Pragma: no-cache',
                'Referer: https://www.ozelders.com' . $path,
                'Sec-Fetch-Dest: document',
                'Sec-Fetch-Mode: navigate',
                'Sec-Fetch-Site: none',
                'Sec-Fetch-User: ?1',
                'Upgrade-Insecure-Requests: 1',
                'Sec-Fetch-Dest: empty',
                'Sec-Fetch-Mode: cors',
                'Sec-Fetch-Site: same-origin',
                'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.113 Safari/537.36',
                'X-Requested-With: XMLHttpRequest'
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($params));
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($ch, CURLOPT_ENCODING , "gzip");
            $data = curl_exec($ch);
            curl_close($ch);
        }
        if (empty($data) || !is_callable('curl_init')) {
            $opts = array('https' => array());
            $context = stream_context_create($opts);
            $headers = get_headers($url);
            $httpcode = substr($headers[0], 9, 3);
            if ($httpcode == '200')
                $data = file_get_contents($url, false, $context);
            else {
                $data = 'request error: ' . $httpcode;
            }
        }
        return $data;
    }

    private function getKey($page, $path) {
        /** data key is different with fetched */
        preg_match_all('/<div[^>]+data-key="?([^"\s]+)"?\s*/', $page, $matches);
        if ($matches && $matches[1]) {
            $key = strval($matches[1][0]);
            Log::info($key);
            $pageContent = $this->sendPOSTRequest($path, $key);
            if ($pageContent && strpos($pageContent, 'request error: ') === false) {
                $phone = $pageContent;
                $result = array('path' => $path, 'phone' => $phone);
                Log::info(json_encode($result));
            } else {
                Log::error(json_encode($pageContent));
            }
        }
    }

    private function getProfiles($page, $path, $index) {
        preg_match_all('/<div[^>]+data-url="?([^"\s]+)"?\s*/', $page, $matches);
        if ($matches && $matches[1]) {
            $profileUrl = $matches[1][0];
            $this->distribute('GET', $profileUrl, 0, 'key');
            foreach ($matches[1] as $match) {
                $profileUrl = $match;
                $this->distribute('GET', $profileUrl, 0, 'key');
                sleep(3);
            }
        }
        if ($index < 1000) {
            $next = $index + 20;
            $this->distribute('GET', $path, $next, 'profile');
        }
    }

    private function getGroups($page) {
        $groups = array(
            '/ders-verenler/ilkogretim',
            '/ders-verenler/lise',
            '/ders-verenler/universite',
            '/ders-verenler/sinav-hazirlik',
            '/ders-verenler/yabanci-dil',
            '/ders-verenler/bilgisayar'
        );
        foreach ($groups as $group) {
            Log::info('Scraper get group: ' . $group);
            $this->distribute('GET', $group, 0, 'profile');
        }
//        preg_match_all('/<a[^>]*>([^<]+)<\/a>/', $page, $matches);
//        if ($matches && $matches[0]) {
//            foreach ($matches[0] as $match) {
//                preg_match('/^<a\s+(?:[^>]*?\s+)?href="(.*?)"[^>]+>(.*?)<\/a>/', $match, $href);
//                if ($href && $href[1] && strpos($href[1], '/ders-verenler') !== false) {
//                    $url = $href[1];
//                    $parsed = explode('/', $url);
//                    if ($parsed && count($parsed) == 3) {
//                        $group = $url;
//                        Log::info('Scraper get group: ' . $group);
//                        $this->distribute('GET', $group);
//                    }
//                }
//            }
//        }
    }

}
