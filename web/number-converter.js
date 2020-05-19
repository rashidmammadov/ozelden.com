const fs = require('fs');

let finalData = [];
let rawData = [];

fs.readFile( './phone-numbers.json', 'utf-8', function(err, data) {
    console.log(`(${new Date()}) open crawlered-numbers.json`);
    rawData = JSON.parse(data);
    const index = 40;
    const limit = 25;
    const result = rawData.slice((index * limit), (index * limit) + limit);
    result.forEach((d) => finalData.push(d.phone));

    fs.writeFile('./bucket-phone-numbers.txt', finalData.join('\n'), (err) => {
        if (err) throw err;
        console.log(`(${new Date()}) updated bucket-phone-numbers.txt`);
    });
});
// fs.readFile( './crawlered-numbers.json', 'utf-8', function(err, data) {
//     console.log(`(${new Date()}) open crawlered-numbers.json`);
//     rawData = JSON.parse(data);
//     rawData.forEach(d => {
//         let id;
//         let name;
//         let phone;
//         if (d && d.path && d.phone) {
//             id = prepareExternalId(d.path);
//             name = prepareUserName(d.path);
//             phone = preparePhoneNumber(d.phone);
//             if (phone) {
//                 finalData.push({
//                     id: id,
//                     name: name,
//                     phone: phone
//                 });
//             }
//         }
//     });
//
//     finalData.sort(sortByField('id', 'asc', 'number'));
//
//     fs.writeFile('./phone-numbers.json', JSON.stringify(finalData), (err) => {
//         if (err) throw err;
//         console.log(`(${new Date()}) updated phone-numbers.json`);
//     });
// });

prepareExternalId = (path) => {
    let id;
    let simply = path.replace('/ogretmen/', '');
    const parsed = simply.split('-');
    const l = parsed.length;
    if (l >= 1) {
        id = parsed[l - 1];
    }
    return id;
};

prepareUserName = (path) => {
    let name;
    let simply = path.replace('/ogretmen/', '');
    const parsed = simply.split('-');
    const l = parsed.length;
    if (l >= 3) {
        const fName = parsed[l - 3];
        const lName = parsed[l - 2];
        name = fName + ' ' + lName;
    }
    return name;
};

preparePhoneNumber = (phone) => {
    let result = phone;
    result = result.replace('(' , '');
    result = result.replace(')' , '');
    result = result.replace('-' , '');
    result = result.replace(' ' , '');
    if (result) {
        result = '0' + result;
    }
    return result;
};

const sortByField = (field, order, type) => {
    const valA = order === 'desc' ? 1 : -1;
    const valB = order === 'desc' ? -1 : 1;
    return function (a, b) {
        let aField = a[field];
        let bField = b[field];
        if (type === 'number') {
            aField = Number(a[field]);
            bField = Number(b[field]);
        }
        return aField < bField ? valA : aField === bField ? 0 : valB;
    }
};
