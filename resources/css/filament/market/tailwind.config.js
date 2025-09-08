import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Market/**/*.php',
        './resources/views/filament/market/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
