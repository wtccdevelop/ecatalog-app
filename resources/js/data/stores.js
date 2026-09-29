const A = '/assets/images/';

export const storeImages = [
    { image_url: `${A}wtc.jpg`,  text: 'WTC Cell Jajag',   link: 'https://maps.app.goo.gl/fbkZp636M2WE1Pa37' },
    // { image_url: `${A}syihab-martapura.jpg`,   text: 'WTC Cell Martapura',    link: 'https://maps.app.goo.gl/1XD8fdaa2JcgpYyW8' },
    // { image_url: `${A}syihab-premium.jpg`,     text: 'Syihab Premium',        link: 'https://maps.app.goo.gl/wNRMLq97FsYKjGuj8' },
    // { image_url: `${A}syihab-sultan-adam.jpg`, text: 'WTC Cell Sultan Adam',  link: 'https://maps.app.goo.gl/QJScLZCcqs6LfPXx5' },
    // { image_url: `${A}syihab-veteran.jpg`,     text: 'WTC Cell Veteran',      link: 'https://maps.app.goo.gl/U9kREX3rPdqC6SFy8' },
];

// dipakai section "Temukan Toko Kami"
export const storeAddresses = [
    { title: 'WTC Cell Jajag',         link: storeImages[0].link, address: 'Jl. PB Sudirman No.65, Dusun Kp. Baru, Jajag, Kec. Gambiran, Kabupaten Banyuwangi, Jawa Timur 68486' },
    // { title: 'Syihab Banjarbaru Premium', link: storeImages[2].link, address: 'Guntung Payung, Landasan Ulin, Banjarbaru City, South Kalimantan 70714' },
    // { title: 'Syihab Martapura',          link: storeImages[1].link, address: 'Jl. A. Yani No.38, Cindai Alus, Kec. Martapura, Kabupaten Banjar, Kalimantan Selatan' },
    // { title: 'Syihab Veteran',            link: storeImages[4].link, address: 'Jl. Veteran No.287, Sungai Bilu, Kec. Banjarmasin Tim., Kota Banjarmasin, Kalimantan Selatan 70236' },
    // { title: 'Syihab Sultan Adam',        link: storeImages[3].link, address: 'Jl. Sultan Adam, Sungai Miai, Kec. Banjarmasin Utara, Kota Banjarmasin, Kalimantan Selatan 70123' },
];

// dipakai AppAbout (thumbnail toko)
export const storeLocations = storeAddresses.map((s, i) => ({
    title: s.title,
    description: s.address,
    image: [storeImages[0], storeImages[2], storeImages[1], storeImages[4], storeImages[3]][i].image_url,
}));

export const instagramAccounts = [
    { handle: '@syihab_banjarbaru', label: 'Syihab Banjarbaru',              url: 'https://www.instagram.com/syihab_banjarbaru/' },
    // { handle: '@syihabpremium',     label: 'Syihab Premium Banjarbaru',      url: 'https://www.instagram.com/syihabpremium/' },
    // { handle: '@syihab_martapura',  label: 'Syihab Martapura',               url: 'https://www.instagram.com/syihab__martapura/' },
    // { handle: '@syihab_veteran',    label: 'Syihab Veteran Banjarmasin',     url: 'https://www.instagram.com/syihab_veteran/' },
    // { handle: '@syihab_sultanadam', label: 'Syihab Sultan Adam Banjarmasin', url: 'https://www.instagram.com/syihab_sultanadam/' },
];

export const socials = [
    { name: 'Facebook', url: 'https://www.facebook.com/people/Syihab-Store-Banjarbaru/100092506301237/', viewBox: '0 0 50 50',
      path: 'M32,11h5c0.552,0,1-0.448,1-1V3.263c0-0.524-0.403-0.96-0.925-0.997C35.484,2.153,32.376,2,30.141,2C24,2,20,5.68,20,12.368V19h-7c-0.552,0-1,0.448-1,1v7c0,0.552,0.448,1,1,1h7v19c0,0.552,0.448,1,1,1h7c0.552,0,1-0.448,1-1V28h7.222c0.51,0,0.938-0.383,0.994-0.89l0.778-7C38.06,19.518,37.596,19,37,19h-8v-5C29,12.343,30.343,11,32,11z' },
    { name: 'TikTok', url: 'https://www.tiktok.com/@syihabstore_', viewBox: '0 0 25 25',
      path: 'M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z' },
];