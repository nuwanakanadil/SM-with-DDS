export const dimensionUnitOptions = [
    { value: 'CM', label: 'CM' },
    { value: 'INCH', label: 'Inch' },
    { value: 'MM', label: 'MM' },
    { value: 'M', label: 'M' },
] as const;

export type DimensionUnit = (typeof dimensionUnitOptions)[number]['value'];
export interface NormalizedDimensions {
    length: number;
    width: number;
    height: number;
}

const centimeterMultipliers: Record<DimensionUnit, number> = {
    CM: 1,
    INCH: 2.54,
    MM: 0.1,
    M: 100,
};

const dimensionUnitLabels: Record<DimensionUnit, string> = dimensionUnitOptions.reduce(
    (labels, option) => {
        labels[option.value] = option.label;

        return labels;
    },
    {} as Record<DimensionUnit, string>,
);

export const normalizeDimensionUnit = (value: unknown): DimensionUnit => {
    const normalizedValue = String(value ?? 'CM').trim().toUpperCase();

    if (normalizedValue in centimeterMultipliers) {
        return normalizedValue as DimensionUnit;
    }

    return 'CM';
};

export const getDimensionUnitLabel = (value: unknown): string => {
    return dimensionUnitLabels[normalizeDimensionUnit(value)];
};

export const convertDimensionValue = (
    value: number,
    fromUnit: DimensionUnit,
    toUnit: DimensionUnit,
): number => {
    const valueInCentimeters = value * centimeterMultipliers[fromUnit];

    return valueInCentimeters / centimeterMultipliers[toUnit];
};

export const parseDimensionString = (dimensions: string | null | undefined): number[] => {
    if (!dimensions) {
        return [];
    }

    return dimensions
        .split(/[xX]/)
        .map((part) => Number(part.trim()))
        .filter((part) => Number.isFinite(part));
};

export const formatDimensionInput = (value: number): string => {
    if (!Number.isFinite(value)) {
        return '';
    }

    return Number.parseFloat(value.toFixed(3)).toString();
};

export const formatDimensionsForDisplay = (
    dimensions: string | null | undefined,
    unit: unknown,
): string => {
    const parts = parseDimensionString(dimensions);

    if (parts.length !== 3) {
        return dimensions?.trim() || 'N/A';
    }

    const normalizedUnit = normalizeDimensionUnit(unit);
    const displayValue = parts
        .map((part) => formatDimensionInput(convertDimensionValue(part, 'CM', normalizedUnit)))
        .join(' x ');

    return `${displayValue} ${getDimensionUnitLabel(normalizedUnit)}`;
};

export const normalizeDimensionsForCalculation = (
    dimensions: Array<string | number | null | undefined>,
    unit: unknown,
): NormalizedDimensions | null => {
    if (dimensions.length !== 3) {
        return null;
    }

    const dimensionValues = dimensions.map((value) => Number(value));

    if (dimensionValues.some((value) => !Number.isFinite(value) || value <= 0)) {
        return null;
    }

    const normalizedUnit = normalizeDimensionUnit(unit);

    return {
        length: convertDimensionValue(dimensionValues[0], normalizedUnit, 'CM'),
        width: convertDimensionValue(dimensionValues[1], normalizedUnit, 'CM'),
        height: convertDimensionValue(dimensionValues[2], normalizedUnit, 'CM'),
    };
};

export const canAutoCalculateVolume = (
    numberOfPackages: unknown,
    normalizedDimensions: NormalizedDimensions | null,
): boolean => {
    return Number(numberOfPackages) > 0 && normalizedDimensions !== null;
};
