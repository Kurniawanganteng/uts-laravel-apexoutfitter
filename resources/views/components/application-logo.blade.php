<svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <defs>
        <linearGradient id="apexGrad" x1="0" y1="0" x2="100" y2="100" gradientUnits="userSpaceOnUse">
            <stop offset="0%" stop-color="#064e3b"/>
            <stop offset="60%" stop-color="#065f46"/>
            <stop offset="100%" stop-color="#0f172a"/>
        </linearGradient>
        <linearGradient id="peakAccent" x1="50" y1="12" x2="50" y2="85" gradientUnits="userSpaceOnUse">
            <stop offset="0%" stop-color="#fbbf24"/>
            <stop offset="100%" stop-color="#d97706"/>
        </linearGradient>
    </defs>
    <!-- Background Squircle -->
    <rect x="6" y="6" width="88" height="88" rx="22" fill="url(#apexGrad)"/>
    <!-- Outer Contour Lines -->
    <circle cx="50" cy="50" r="36" stroke="white" stroke-opacity="0.08" stroke-width="1.5" stroke-dasharray="3 3"/>
    <!-- Left Major Ridge -->
    <path d="M50 22L22 72H50V22Z" fill="#ffffff" fill-opacity="0.95"/>
    <!-- Right Major Ridge (Shaded) -->
    <path d="M50 22L78 72H50V22Z" fill="#ffffff" fill-opacity="0.3"/>
    <!-- Secondary Peak Behind -->
    <path d="M64 42L82 72H60L64 42Z" fill="#10b981" fill-opacity="0.6"/>
    <!-- Central Ridge Line Accent -->
    <path d="M50 22V72" stroke="#047857" stroke-width="2"/>
    <!-- Snow Summit Cap -->
    <path d="M50 22L58 36L50 33L42 36L50 22Z" fill="#f8fafc"/>
    <!-- Summit Golden Star/Compass Point -->
    <circle cx="50" cy="16" r="3.5" fill="url(#peakAccent)"/>
</svg>
