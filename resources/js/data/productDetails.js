import { productsByBrand } from './products.js';

const A = '/assets/WTC Cell - Official Storeee_files/';

// GANTI dengan nomor WhatsApp toko yang asli
export const WA_NUMBER = '6281234567890';

export const slugify = (name) =>
    name.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');

export const formatRupiah = (n) => 'Rp ' + Number(n).toLocaleString('id-ID');

const parsePrice = (s) => Number(String(s).replace(/\D/g, ''));

/* ---------- Simulasi cicilan (estimasi, bunga flat 2,6%/bulan) ---------- */
export function buildInstallments(price) {
    const flat = (n) => {
        const total = Math.round(price * (1 + 0.026 * n));
        return { tenure: n, name: `Cicilan ${n} bulan`, note: 'Bunga 2,6% / bulan', monthly: Math.round(total / n / 10) * 10, total };
    };
    const feeTotal = Math.round(price * 1.03);
    return [
        { tenure: 3, name: 'Cicilan 3 bulan', note: 'Biaya layanan 3%', monthly: Math.round(feeTotal / 3 / 10) * 10, total: feeTotal },
        flat(6), flat(12), flat(18), flat(24),
    ];
}

/* ---------- Data detail lengkap (statis) ---------- */
export const productDetails = {
    'iphone-air': {
        id: 154,
        name: 'IPHONE AIR',
        brand: 'APPLE',
        image: `${A}KG9Ap3uxXINh1UiWdRn9G6v2rQSOsWQtHA4Lhinw.jpg`,
        variants: [
            { ram: 12, storage: 256,  color: 'SKY BLUE',    price: 16599000, stock: 10 },
            { ram: 12, storage: 256,  color: 'SPACE BLACK', price: 16599000, stock: 6 },
            { ram: 12, storage: 512,  color: 'SKY BLUE',    price: 20599000, stock: 8 },
            { ram: 12, storage: 1024, color: 'SKY BLUE',    price: 25599000, stock: 10 },
        ],
        specs: [
            { label: 'Chipset',      value: 'Apple A19 Pro (3 nm)' },
            { label: 'Display',      value: '6.5 inches, LTPO Super Retina XDR OLED, 120Hz' },
            { label: 'Battery',      value: '3149 mAh' },
            { label: 'OS',           value: 'iOS 26, upgradable to iOS 26.2' },
            { label: 'Resolution',   value: '1260 x 2736 pixels' },
            { label: 'Main Camera',  value: '48 MP' },
            { label: 'Front Camera', value: '18 MP' },
            { label: 'Weight',       value: '165 g' },
        ],
        gallery: [],
        reviews: [
            { name: 'Budi S.', rating: 5, date: '12 Sep 2026', comment: 'Barang original, segel rapi, pengiriman cepat. Mantap!' },
            { name: 'Rina A.', rating: 4, date: '3 Sep 2026',  comment: 'Pelayanan ramah, dibantu setup cicilan lewat WhatsApp.' },
        ],
    },
};

/* ---------- Fallback untuk produk lain di beranda ---------- */
const fallbackSpecs = ['Chipset', 'Display', 'Battery', 'OS', 'Resolution', 'Main Camera', 'Front Camera', 'Weight']
    .map((label) => ({ label, value: '-' }));

export function getProductDetail(slug) {
    if (productDetails[slug]) return productDetails[slug];

    for (const [brand, list] of Object.entries(productsByBrand)) {
        const p = list.find((x) => slugify(x.name) === slug);
        if (p) {
            return {
                id: p.id,
                name: p.name,
                brand,
                image: p.image,
                variants: [{ ram: null, storage: null, color: null, price: parsePrice(p.price), stock: 10 }],
                specs: fallbackSpecs,
                gallery: [],
                reviews: [],
            };
        }
    }
    return null;
}