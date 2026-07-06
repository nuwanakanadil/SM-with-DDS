const PASSWORD_CHARSET = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';

export const generatePassword = (length = 12) => {
    const cryptoObject = globalThis.crypto;

    if (!cryptoObject?.getRandomValues) {
        return Array.from({ length }, () => PASSWORD_CHARSET[Math.floor(Math.random() * PASSWORD_CHARSET.length)]).join('');
    }

    const values = new Uint32Array(length);
    cryptoObject.getRandomValues(values);

    return Array.from(values, (value) => PASSWORD_CHARSET[value % PASSWORD_CHARSET.length]).join('');
};
