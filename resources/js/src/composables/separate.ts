import { ref } from 'vue';

function separateNominal(nominal: string | number): string {
    // Convert number to string, remove non-digit, then format
    const num = typeof nominal === 'number' ? nominal : parseInt(nominal.replace(/\D/g, ''), 10);
    // handle if NaN
    
    console.log('Parsed number:', num);
    if (isNaN(num) || num === 0) {
        const num = 0;
        console.log('Returning formatted zero:', num.toLocaleString('id-ID'));
        return num.toLocaleString('id-ID');
    }
    return num.toLocaleString('id-ID');
}

function noSeparateNominal(nominal: string): string {
    // Remove all non-digit characters
    return nominal.replace(/\D/g, '');
}

// Contoh penggunaan saat change nominal
const nominal = ref('');

function onNominalChange(value: string) {
    nominal.value = separateNominal(value);
}

function onNoSeparate() {
    nominal.value = noSeparateNominal(nominal.value);
}

export { separateNominal, noSeparateNominal, onNominalChange, onNoSeparate };
