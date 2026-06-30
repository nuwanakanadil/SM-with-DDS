const VOLUMETRIC_DIVISOR = 6000;
const CBM_DIVISOR = 1000000;

export const calculateVolumetricWeight = (
    length: number,
    width: number,
    height: number,
    numberOfPackages: number,
): number => {
    const volumetricWeight = ((length * width * height) / VOLUMETRIC_DIVISOR) * numberOfPackages;
    return volumetricWeight;
};

export const calculateCBM = (
    length: number,
    width: number,
    height: number,
    numberOfPackages: number,
): number => {
    const cbm = ((length * width * height) / CBM_DIVISOR) * numberOfPackages;
    return cbm;
};

export const findChargeableWeight = (grossWeight: number, volumetricWeight: number): number => {
    return Math.max(grossWeight, volumetricWeight);
};

export const calculateVolumetricWeightFromVolume = (volume: number): number => {
    const volumetricWeight = (volume / VOLUMETRIC_DIVISOR) * CBM_DIVISOR;
    return volumetricWeight;
};
