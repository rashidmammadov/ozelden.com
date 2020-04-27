const querystring = require('querystring');
const https = require('https');
const zlib = require("zlib");

let buffer = [];
let globalDataKey = '6B74756233776373327366746471347572646364306C71787C3238383538327C3231373533367C307C30';
let result = [];

const setHeaders = (referer, params) => {
    let headers =  {
        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'Accept-Encoding': 'gzip, deflate, br',
        'Accept-Language': 'en-US,en;q=0.9,tr;q=0.8,az;q=0.7',
        'Cache-Control': 'no-cache',
        'Connection': 'keep-alive',
        'Cookie': '_ufpx8576=; _ga=GA1.2.1184402087.1581196763; __zlcmid=weitmhkdPURx1G; ASP.NET_SessionId=ktub3wcs2sftdq4urdcd0lqx; ARRAffinity=51bbf4aa8a1562d78e5ccc96b74f5c77962dd211ecad3b2a29451657ceb81075; _gid=GA1.2.1143066787.1587841421; _utfx3876=FDABEB39234765E8642405428C5406204D02281353DC76BB06A6A2EBB0A1CB27A77F28EE1B622A90422674D4435D2081EC73D8C5822602A50377B9CDDBCB925B4D08F12E9ED65E08386037B98A16455C9DA9BD5C2E927106F1C40698096996ABCC21569F96F1632C93F7AD3547B747B56B957E91F1716E3A7318407F3FA23805984C8B368E5B7066033F0770B56D3D82; _utdx4123=izmir; ARRAffinity=3899a4c246ab409ce24157990cab89a709e4705f2b410d297e106a90486cd1f4',
        'Host': 'www.ozelders.com',
        'Pragma': 'no-cache',
        'Sec-Fetch-Dest': 'document',
        'Sec-Fetch-Mode': 'navigate',
        'Sec-Fetch-Site': 'none',
        'Sec-Fetch-User': '?1',
        'Upgrade-Insecure-Requests': 1,
        'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.113 Safari/537.36'
    };

    if (referer) {
        headers['Referer'] = referer;
    }

    if (params) {
        headers['Content-Type'] = 'application/x-www-form-urlencoded';
        headers['Content-Length'] = params.length;
    }

    return headers;
};

const setOptions = (path, headers, method) => {
    let options = {
        hostname: 'www.ozelders.com',
        path: path,
        headers: headers,
        // port: 443,
        // gzip: true,
        method: method
    };

    // options['port'] = 443;
    // options['gzip'] = true;
    //
    // if (method !== null) {
    //     options['method'] = method;
    // }

    return options;
};

const getGzipped = (options, callback) => {
    https.get(options, function (res) {
        const gunzip = zlib.createGunzip();
        res.pipe(gunzip);

        gunzip.on('data', function (data) {
            buffer.push(data.toString())
        }).on("end", function () {
            callback(null, buffer.join(""));
        }).on("error", function (e) {
            callback(e);
        })
    }).on('error', function (e) {
        callback(e);
    });
};

const getPhone = (path, referer) => {
    const params = querystring.stringify({ 'key': globalDataKey });
    const headers = setHeaders('https://www.ozelders.com' + referer, params);
    const options = setOptions(path, headers, 'POST');
    console.log('start get phone ' + path);
    https.request(options, res => {
        console.log('statusCode:', res.statusCode);
        console.log('headers:', res.headers);

        res.on('data', d => {
            response += d;
        });

        res.on('end', () => {
            console.log(path + ' => phone: ' + response);
        });
    }).on("error", (err) => {
      console.log("Error: ", err.message);
    });
};

const sendRequest = (path, next) => {
    const headers = setHeaders(null, null);
    const options = setOptions(path, headers, null);
    buffer = [];
    if (next) {
        getGzipped(options, (err, data) => {
            if (data) {
                if (data.includes('phonenumberhidden')) {
                    // const matches = data.match(/<div[^>]+data-key="?([^"\s]+)"?\s*/gi);
                    // matches && matches.forEach((match) => {
                    //     const dataKey = /data-key="([^">]+)"/.exec(match);
                    //     if (dataKey && dataKey[1]) {
                    //         globalDataKey = dataKey[1];
                    //         console.log(path + ' key: ' + globalDataKey);
                    //     }
                    // });
                } else if (data.includes('row clickable narrow-gutter profile-list-item plain-item grid-item')) {
                    /** if page is user listing */
                    const matches = data.match(/<div[^>]+data-url="?([^"\s]+)"?\s*/gi);
                    matches && matches.forEach((match) => {
                        if (match.includes('/ders-veren')) {
                            const dataUrl = /data-url="([^">]+)"/.exec(match);
                            if (dataUrl && dataUrl[1]) {
                                const profileUrl = dataUrl[1];
                                // if (!globalDataKey) {
                                //     setTimeout(() => sendRequest(profileUrl, true), 2000);
                                // } else {
                                    console.log('try to get user phone of ' + profileUrl);
                                    setTimeout(() => getPhone('ajax/telefon-goster/', profileUrl), 2000);
                                // }
                            }
                        }
                    });
                } else {
                    const matches = data.match(/<a[^>]*>([^<]+)<\/a>/g);
                    matches && matches.forEach((match) => {
                        if (match.includes(path)) {
                            const href = /<a\s+(?:[^>]*?\s+)?href=(["'])(.*?)\1./.exec(match);
                            if (href && href[2] && href[2].includes('/')) {
                                const url = href[2];
                                setTimeout(() => sendRequest(url, true), 2000);
                            }
                        }
                    });
                }
            } else {
                console.log('request rejected' + err);
            }
        });
    }
};

getPhone('/ajax/telefon-goster/', '/ders-veren/salih-o-359445');
// sendRequest('/ders-verenler', true);
