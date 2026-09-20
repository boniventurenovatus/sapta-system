const fs = require('fs');
const tz = require('tz-locations');

// Mikoa unayotaka (tumia slug - herufi ndogo, hyphen badala ya space)
const REGIONS = ['morogoro', 'ruvuma', 'arusha', 'dodoma'];

async function main() {
    const allData = {};
    let totalWards = 0;

    for (const regionSlug of REGIONS) {
        console.log('\n=== ' + regionSlug.toUpperCase() + ' ===');
        allData[regionSlug] = { districts: {} };

        const districts = tz.getDistrictsByRegion(regionSlug);
        console.log('Districts: ' + districts.length);

        for (const district of districts) {
            const wards = tz.getWardsByDistrict(regionSlug, district.slug);
            allData[regionSlug].districts[district.name] = wards.map(w => w.name);
            totalWards += wards.length;
            console.log('  ' + district.name + ': ' + wards.length + ' wards');
        }
    }

    // Hifadhi JSON
    fs.writeFileSync('tanzania-locations.json', JSON.stringify(allData, null, 2));
    console.log('\n? Created: tanzania-locations.json');

    // Unda CSV
    const csvLines = ['region,district,ward'];
    for (const [region, rData] of Object.entries(allData)) {
        for (const [district, wards] of Object.entries(rData.districts)) {
            for (const ward of wards) {
                csvLines.push('"' + region + '","' + district + '","' + ward + '"');
            }
        }
    }
    fs.writeFileSync('wards.csv', csvLines.join('\n'));
    console.log('? Created: wards.csv');
    console.log('?? Total wards: ' + totalWards);
}

main().catch(function(e) { console.error('FATAL:', e); });
