// Importez ou copiez la fonction convertCatAgeHuman ici
function convertCatAgeHuman(catAge) {
    if (catAge === 2) {
        return 2; // Le deuxième mois équivaut à 2 ans humains.
    } else if (catAge === 4) {
        return 6;
    } else if (catAge === 6) {
        return 10;
    } else if (catAge === 12) {
        return 15;
    } else if (catAge === 24) {
        return 24; // Le deuxième an équivaut à 9 ans humains de plus, soit 15 + 9.
    } else {
        return 24 + ((catAge / 12) - 2) * 4; // Chaque année supplémentaire équivaut à 4 ans humains.
    }
}

describe('convertCatAgeHuman', () => {
    test('Retourne 2 pour 2 mois', () => {
        expect(convertCatAgeHuman(2)).toBe(2);
    });

    test('Retourne 6 pour 4 mois', () => {
        expect(convertCatAgeHuman(4)).toBe(6);
    });

    test('Retourne 10 pour 6 mois', () => {
        expect(convertCatAgeHuman(6)).toBe(10);
    });

    test('Retourne 15 pour 12 mois', () => {
        expect(convertCatAgeHuman(12)).toBe(15);
    });

    test('Retourne 24 pour 24 mois', () => {
        expect(convertCatAgeHuman(24)).toBe(24);
    });

    test('Calcule correctement pour 36 mois', () => {
        expect(convertCatAgeHuman(36)).toBe(28); // 24 + (36/12 - 2)*4 = 28
    });

    test('Calcule correctement pour 48 mois', () => {
        expect(convertCatAgeHuman(48)).toBe(32); // 24 + (48/12 - 2)*4 = 32
    });
});


// commande : npx jest