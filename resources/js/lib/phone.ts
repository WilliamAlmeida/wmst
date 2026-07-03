/**
 * Máscara de telefone brasileiro: +55 (DD) XXXXX-XXXX.
 * Recebe qualquer valor digitado e devolve a string formatada.
 */
export function maskBrazilPhone(value: string): string {
    let digits = value.replace(/\D/g, '');

    if (!digits.startsWith('55')) {
        digits = '55' + digits;
    }

    digits = digits.slice(0, 13); // 55 + até 11 dígitos

    const ddd = digits.slice(2, 4);
    const rest = digits.slice(4);

    let out = '+55';

    if (ddd) {
        out += ` (${ddd}`;
        if (ddd.length === 2) {
            out += ')';
        }
    }

    if (rest) {
        out += ' ';
        if (rest.length <= 4) {
            out += rest;
        } else if (rest.length <= 8) {
            out += `${rest.slice(0, 4)}-${rest.slice(4)}`;
        } else {
            out += `${rest.slice(0, 5)}-${rest.slice(5, 9)}`;
        }
    }

    return out;
}
