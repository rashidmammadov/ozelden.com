const https = require('https');
const fs = require('fs');

const options = {
    hostname: 'api.ozelden.com',
    port: 443,
    path: '/api/v1/data/lectures',
};

let siteMapXMLList = [];

console.log(`(${new Date()}) sitemap starts to crawl `);

const req = https.get(options, res => {
    console.log(`(${new Date()}) sitemap fetch request with status code: ${res.statusCode}`);
    let response = '';

    res.on('data', d => {
        response += d;
    });

    res.on('end', () => {
      const data = JSON.parse(response);
        if (data && data.data && data.data.length) {
            siteMapXMLList = [];
            siteMapXMLList.push('<?xml version="1.0" encoding="UTF-8"?>\n' +
              '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"> ');
            data.data.forEach((parent) => {
                const xml = JSON2XML(parent.lecture_area);
                siteMapXMLList.push(xml);
                parent.lecture_themes.forEach((child) => {
                    const xml = JSON2XML(parent.lecture_area + '/' + child.lecture_theme);
                    siteMapXMLList.push(xml);
                });
            });
            siteMapXMLList.push('\n</urlset>');
        }

        fs.writeFile('./src/sitemap.xml', siteMapXMLList.join(''), (err) => {
            if (err) throw err;
            console.log(`(${new Date()}) updated sitemap.xml`);
        });
    })
});

req.on('error', error => {
    console.error(`(${new Date()}) sitemap crawl ${error}`);
});

req.end();


const millisecondsToString = () => {
    const date = new Date();
    const year = date.getFullYear();
    const month = (date.getMonth() + 1) >= 10 ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1));
    const day = date.getDate() >= 10 ? date.getDate() : ('0' + date.getDate());
    return `${year}-${month}-${day}`;
};

const JSON2XML = (loc) => {
    return '\n<url>' +
          '\n<loc>https://ozelden.com/' + loc + '</loc>' +
          '\n<lastmod>' + millisecondsToString() + '</lastmod>' +
          '\n<changefreq>daily</changefreq>' +
      '\n</url>'
};
