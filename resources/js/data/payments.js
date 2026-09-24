const A = '/assets/WTC Cell - Official Storeee_files/';

const list = [
    ['bca', 'Bank BCA'], ['bankbjb', 'Bank BJB'], ['bni', 'Bank BNI'], ['bri', 'Bank BRI'],
    ['bsi', 'Bank BSI'], ['cimb', 'CIMB Niaga'], ['danamon', 'Bank Danamon'], ['digibank', 'Digibank'],
    ['hsbcc', 'Bank HSBC'], ['jenius', 'Jenius'], ['mandiri', 'Bank Mandiri'], ['maybankt', 'Maybank'],
    ['neobankc', 'Neobank'], ['ocbc', 'OCBC NISP'], ['permatac', 'Bank Permata'],
    ['banksahabatsampoerna', 'Bank Sahabat Sampoerna'], ['uob', 'Bank UOB'], ['shopeepay', 'ShopeePay'],
    ['dana', 'DANA'], ['akulaku', 'Akulaku'], ['kredivoc', 'Kredivo'], ['indodana', 'Indodana'],
];

export const paymentMethods = list.map(([file, alt]) => ({ src: `${A}${file}.png`, alt }));