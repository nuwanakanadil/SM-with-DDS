<script setup lang="ts">
const formatEquation = (equation: string) =>
    equation
        .replace(/\^2/g, '<sup>2</sup>')
        .replace(/\^3/g, '<sup>3</sup>')
        .replace(/->/g, '&rarr;');

type Variant = 'hero' | 'card' | 'compact';

type Props = {
    equations?: string[];
    variant?: Variant;
};

withDefaults(defineProps<Props>(), {
    equations: () => ['x^2 + y^2 = r^2', 'f(x) = x + 3', 'sin A', 'pi'],
    variant: 'hero',
});
</script>

<template>
    <div
        aria-hidden="true"
        class="math-motion-panel"
        :class="[`math-motion-panel--${variant}`]"
    >
        <div
            v-if="variant === 'hero'"
            class="math-motion-panel__ambient"
        ></div>
        <div
            v-if="variant === 'hero'"
            class="math-motion-panel__ambient math-motion-panel__ambient--secondary"
        ></div>
        <div class="math-motion-panel__grid"></div>
        <div class="math-motion-panel__glow math-motion-panel__glow--one"></div>
        <div class="math-motion-panel__glow math-motion-panel__glow--two"></div>

        <svg
            class="math-motion-panel__wave"
            viewBox="0 0 320 120"
            fill="none"
            preserveAspectRatio="none"
        >
            <path
                d="M0 72C20 72 20 48 40 48C60 48 60 96 80 96C100 96 100 24 120 24C140 24 140 84 160 84C180 84 180 36 200 36C220 36 220 72 240 72C260 72 260 54 280 54C300 54 300 72 320 72"
                pathLength="1"
            />
        </svg>

        <div
            v-if="variant === 'hero'"
            class="math-motion-panel__math-totem"
        >
            <div class="math-motion-panel__calculator">
                <div class="math-motion-panel__calculator-screen">y = mx + c</div>
                <div class="math-motion-panel__calculator-keys">
                    <span>7</span>
                    <span>8</span>
                    <span>9</span>
                    <span>+</span>
                    <span>4</span>
                    <span>5</span>
                    <span>6</span>
                    <span>-</span>
                    <span>1</span>
                    <span>2</span>
                    <span>3</span>
                    <span>x</span>
                </div>
            </div>
            <div class="math-motion-panel__geometry">
                <div class="math-motion-panel__geometry-ring"></div>
                <div class="math-motion-panel__geometry-triangle"></div>
                <div class="math-motion-panel__geometry-plus"></div>
            </div>
        </div>

        <div class="math-motion-panel__orbit math-motion-panel__orbit--one"></div>
        <div class="math-motion-panel__orbit math-motion-panel__orbit--two"></div>

        <span
            v-for="(equation, index) in equations"
            :key="`${equation}-${index}`"
            class="math-motion-panel__formula"
            :style="{ '--math-index': index }"
            v-html="formatEquation(equation)"
        ></span>
    </div>
</template>
