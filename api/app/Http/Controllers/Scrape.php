<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class Scrape extends ApiController {

    private $dataKey = '6A7035736566737030317A6B3565677A676A7476337864727C3336313736307C3133363438377C307C30';

    public function get() {
        Log::info('Scrape started');
//        $this->distribute('GET','/ders-verenler' );
        $this->getGroups('/ders-verenler');
    }

    private function distribute($method, $path, $index) {
        if ($method == 'GET') {
            $pageContent = $this->sendGETRequest($path, $index);
            if ($pageContent && strpos($pageContent, 'request error: ') === false) {
                $page = gzdecode($pageContent);
                if (strpos($pageContent, 'row clickable narrow-gutter profile-list-item plain-item grid-item') !== false) {
                    $this->getProfiles($page, $path, $index);
                } else {
                    $this->getGroups($page);
                }
            } else {
                Log::error(json_encode($pageContent));
            }
        } else if ($method == 'POST') {
            $pageContent = $this->sendPOSTRequest($path);
            if ($pageContent && strpos($pageContent, 'request error: ') === false) {
                $phone = gzdecode($pageContent);
                $result = array('path' => $path, 'phone' => $phone);
                Log::info(json_encode($result));
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
                'Cookie: _ufpx8576=; _ga=GA1.2.1184402087.1581196763; __zlcmid=weitmhkdPURx1G; _gid=GA1.2.1143066787.1587841421; _utdx4123=izmir; ARRAffinity=3899a4c246ab409ce24157990cab89a709e4705f2b410d297e106a90486cd1f4; ARRAffinity=3899a4c246ab409ce24157990cab89a709e4705f2b410d297e106a90486cd1f4; ASP.NET_SessionId=jp5sefsp01zk5egzgjtv3xdr; _utfx3876=9C8527FA48FD17172576FF9559ECE15666D10CCDB613F35EA623276B835F42E998F1CF0AB5AB0CAEF3464D93C423FD9388EE0E8487D5DA36B2666041D6A68FB555E228BEF73792406B4ABACB16AEA576E98A84FB452BD188C682B9FFD12BBD3FB00B4F01060D2D9E67B85F426A0F97844CF9CC1A9CD0B7B5AC2DFBF5D5687A96ADEAB566566DCCE0805CDDC13200BD3A; _gat=1',
                'Host: www.ozelders.com',
                'Pragma: no-cache',
                'Sec-Fetch-Dest: document',
                'Sec-Fetch-Mode: navigate',
                'Sec-Fetch-Site: none',
                'Sec-Fetch-User: ?1',
                'Upgrade-Insecure-Requests: 1',
                'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.113 Safari/537.36'
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
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

    private function sendPOSTRequest($path) {
        $url = 'https://www.ozelders.com/ajax/telefon-goster/';
        $params = array('key' => $this->dataKey);
        if (is_callable('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'Accept-Encoding: gzip, deflate, br',
                'Accept-Language: en-US,en;q=0.9,tr;q=0.8,az;q=0.7',
                'Cache-Control: no-cache',
                'Connection: keep-alive',
                'Content-Length: 88',
                'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                'Cookie: _ufpx8576=; _ga=GA1.2.1184402087.1581196763; __zlcmid=weitmhkdPURx1G; _gid=GA1.2.1143066787.1587841421; _utdx4123=izmir; ARRAffinity=3899a4c246ab409ce24157990cab89a709e4705f2b410d297e106a90486cd1f4; ARRAffinity=3899a4c246ab409ce24157990cab89a709e4705f2b410d297e106a90486cd1f4; ASP.NET_SessionId=jp5sefsp01zk5egzgjtv3xdr; _utfx3876=9C8527FA48FD17172576FF9559ECE15666D10CCDB613F35EA623276B835F42E998F1CF0AB5AB0CAEF3464D93C423FD9388EE0E8487D5DA36B2666041D6A68FB555E228BEF73792406B4ABACB16AEA576E98A84FB452BD188C682B9FFD12BBD3FB00B4F01060D2D9E67B85F426A0F97844CF9CC1A9CD0B7B5AC2DFBF5D5687A96ADEAB566566DCCE0805CDDC13200BD3A; _gat=1',
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
            $data = curl_exec($ch);
            curl_close($ch);
        }
        if (empty($data) || !is_callable('curl_init')) {
            $opts = array( 'https' => array('header' => 'Connection: close'));
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

    private function getProfiles($page, $path, $index) {
        preg_match_all('/<div[^>]+data-url="?([^"\s]+)"?\s*/', $page, $matches);
        if ($matches && $matches[1]) {
            foreach ($matches[1] as $match) {
                $profileUrl = $match;
                $this->distribute('POST', $profileUrl, 0);
                sleep(2.5);
            }
        }
        if ($index < 1000) {
            $next = $index + 20;
            $this->distribute('GET', $path, $next);
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
            $this->distribute('GET', $group, 0);
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
