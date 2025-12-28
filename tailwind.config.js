import preset from './vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.ts',
        './resources/**/*.vue',
        './vendor/filament/**/*.blade.php',
        './vendor/filament/**/*.php',
    ],
    safelist: [
        {
            pattern: /(fi|filament)-.*/,
        },
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
        },
    },
}
