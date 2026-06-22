/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    50: '#EFF6FF',
                    100: '#DBEAFE',
                    200: '#BFDBFE',
                    300: '#93C5FD',
                    400: '#60A5FA',
                    500: '#3B82F6',
                    600: '#2563EB',
                    700: '#1D4ED8',
                    800: '#1E40AF',
                    900: '#1E3A8A',
                },
                ink: {
                    50:  '#F8FAFC',
                    100: '#F1F5F9',
                    200: '#E2E8F0',
                    300: '#CBD5E1',
                    400: '#94A3B8',
                    500: '#64748B',
                    600: '#475569',
                    700: '#334155',
                    800: '#1E293B',
                    900: '#0F172A',
                },
            },
            fontFamily: {
                sans:  ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                display: ['Fraunces', 'ui-serif', 'Georgia', 'serif'],
            },
            boxShadow: {
                'brand-sm': '0 4px 16px rgba(37,99,235,.12)',
                'brand-md': '0 8px 28px rgba(37,99,235,.14)',
                'brand-lg': '0 24px 60px rgba(37,99,235,.16)',
            },
            keyframes: {
                float:   { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-6px)' } },
                popIn:   { from: { transform: 'scale(0)', opacity: '0' }, to: { transform: 'scale(1)', opacity: '1' } },
                fadeUp:  { from: { opacity: '0', transform: 'translateY(20px)' }, to: { opacity: '1', transform: 'translateY(0)' } },
                fadeLeft:{ from: { opacity: '0', transform: 'translateX(-20px)' }, to: { opacity: '1', transform: 'translateX(0)' } },
                spin:    { to: { transform: 'rotate(360deg)' } },
            },
            animation: {
                float:   'float 3s ease-in-out infinite',
                popIn:   'popIn .4s cubic-bezier(.175,.885,.32,1.275) forwards',
                fadeUp:  'fadeUp .6s ease forwards',
                fadeLeft:'fadeLeft .6s ease forwards',
                spin:    'spin .7s linear infinite',
            },
        },
    },
    plugins: [],
};
