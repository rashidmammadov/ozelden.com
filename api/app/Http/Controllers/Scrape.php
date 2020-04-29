<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class Scrape extends ApiController {

    private $dataKey = '';

    public function get() {
        Log::info('Scrape started');
//        $this->getGroups('/ders-verenler');
        $this->distribute('GET', '/arama', 771, 'profile');
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
        $query = $path . '?bulundugusehir=all' . ($index > 1 ? ('&page=' . $index) : '');
        $url = 'https://www.ogretmenburada.com/' . $query;
        if (is_callable('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                ':authority: www.ogretmenburada.com',
                ':method: GET',
                ':path: ' . $query,
                ':scheme: https',
                'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'accept-encoding: gzip, deflate, br',
                'accept-language: en-US,en;q=0.9,tr;q=0.8,az;q=0.7',
                'cache-control: no-cach',
                'cookie: _ga=GA1.2.775342426.1588085636; _gid=GA1.2.1759355638.1588085636; XSRF-TOKEN=eyJpdiI6IkJ2YkJrK0tQXC9XcFdHK2NyQlNNYmVBPT0iLCJ2YWx1ZSI6ImlwVFZkNHZFR3ROT2FzeTJuYUhJaDBoOXBRZ0tPMUxNZzBoaDhORUZtYUltVzExOTltaDkzekhBd1pyemlNYnYiLCJtYWMiOiJlZWIxZWE0MDNiNGVmYmUyNTE2YjRhZjQzZmI4NjcwMGUwYmJkNzU4NzNhMWUxY2Q2YzE0MmUzYzFjZDYxMzA1In0%3D; laravel_session=eyJpdiI6InRGcmcreWVTZzVkMjJzSWtKdm5idEE9PSIsInZhbHVlIjoiTGhDQ2tyOEhHcDVBWmlKTkVcL1lNNWNkWXJvYWxxUkRGK1RsQ0FKOG9FMG5vdXlKdkUwbHJ0cVJIRE90dElVXC80IiwibWFjIjoiMWFiNzcwYjM0OTk5NTg0NGJkNzk0ZTlmYmUwYzc3MTQyMjI5ZmM3OWUyZDhhZDI3MzU3NmFjN2I0YTk1YTlmOCJ9',
                'pragma: no-cache',
                'referer: https://www.ogretmenburada.com/arama',
                'sec-fetch-dest: document',
                'sec-fetch-mode: navigate',
                'sec-fetch-site: same-origin',
                'sec-fetch-user: ?1',
                'upgrade-insecure-requests: 1',
                'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36',
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
                'Cookie: _ufpx8576=; _ga=GA1.2.1184402087.1581196763; __zlcmid=weitmhkdPURx1G; _gid=GA1.2.1143066787.1587841421; _utdx4123=izmir; ASP.NET_SessionId=m40burd0b1wtdzr31tllebgw; ARRAffinity=3899a4c246ab409ce24157990cab89a709e4705f2b410d297e106a90486cd1f4; ARRAffinity=994c9a1290f56e3419907c0d851b75a5fb9d4b08761051d88f004f0533341f7b; _utfx3876=1BC73456A49E48034E7A5CEB17F5542F2CE6314968D289A888B49642F8E63213ECA4CD7F64B3CDAEA13AD53D8728529D2DBA4C71EAC83D2B97CB712298D1C6F14E17FCC4F7A13F663A4FAAACF3F31CA7F6C8888CEDC4D07306A35B1DBAA142A024B2ACDEBBFE00EADA6C8B764E795A8871EA5195D4E70F05C7E79720730BA8AB95AD04E79393E30AA09FAF95C8A20C1E; _gat=',
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
//        preg_match_all('/<div[^>]+data-key="?([^"\s]+)"?\s*/', $page, $matches);
        preg_match_all('/data-tel="(.*?)"/', $page, $matches);
        if ($matches && $matches[1]) {
            $result = $path;
            foreach ($matches[1] as $phone) {
                if ($phone && $phone !== '0545 220 3344') {
                    $result = array('path' => $path, 'phone' => $phone);
                }
            }
            Log::info(json_encode($result));
//            $key = strval($matches[1][0]);
//            $pageContent = $this->sendPOSTRequest($path, $key);
//            if ($pageContent && strpos($pageContent, 'request error: ') === false) {
//                $phone = $pageContent;
//                $result = array('path' => $path, 'phone' => $phone);
//                Log::info(json_encode($result));
//            } else {
//                Log::error(json_encode($pageContent));
//            }
        }
    }

    private function getProfiles($page, $path, $index) {
//        preg_match_all('/<div[^>]+data-url="?([^"\s]+)"?\s*/', $page, $matches);
        preg_match_all('/<a[^>]+href="?([^"\s]+)"?\s*/', $page, $matches);
        if ($matches && $matches[1]) {
            foreach ($matches[1] as $match) {
                if (strpos($match, '/ogretmen/') !== false) {
                    $profileUrl = $match;
                    $this->distribute('GET', $profileUrl, 0, 'key');
                    sleep(2);
                }
            }
        }
        if ($index < 900) {
            $next = $index + 1;
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
