import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Sarafi/**/*.php',
        './resources/views/filament/sarafi/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
