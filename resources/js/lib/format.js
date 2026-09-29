export const formatRupiah = (n) => 'Rp ' + Number(n).toLocaleString('id-ID');

/* Simulasi cicilan (estimasi, bunga flat 2,6%/bulan) */
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

export const monthly24 = (price) => buildInstallments(price).find((i) => i.tenure === 24).monthly;