<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lensy Studio</title>
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Configure Tailwind to use our CSS variables -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        background: 'oklch(var(--background) / <alpha-value>)',
                        foreground: 'oklch(var(--foreground) / <alpha-value>)',
                        card: 'oklch(var(--card) / <alpha-value>)',
                        'card-foreground': 'oklch(var(--card-foreground) / <alpha-value>)',
                        popover: 'oklch(var(--popover) / <alpha-value>)',
                        'popover-foreground': 'oklch(var(--popover-foreground) / <alpha-value>)',
                        primary: {
                            DEFAULT: 'oklch(var(--primary) / <alpha-value>)',
                            foreground: 'oklch(var(--primary-foreground) / <alpha-value>)'
                        },
                        secondary: {
                            DEFAULT: 'oklch(var(--secondary) / <alpha-value>)',
                            foreground: 'oklch(var(--secondary-foreground) / <alpha-value>)'
                        },
                        muted: {
                            DEFAULT: 'oklch(var(--muted) / <alpha-value>)',
                            foreground: 'oklch(var(--muted-foreground) / <alpha-value>)'
                        },
                        accent: {
                            DEFAULT: 'oklch(var(--accent) / <alpha-value>)',
                            foreground: 'oklch(var(--accent-foreground) / <alpha-value>)'
                        },
                        destructive: {
                            DEFAULT: 'oklch(var(--destructive) / <alpha-value>)',
                            foreground: 'oklch(var(--destructive-foreground) / <alpha-value>)'
                        },
                        border: 'oklch(var(--border) / <alpha-value>)',
                        input: 'oklch(var(--input) / <alpha-value>)',
                        ring: 'oklch(var(--ring) / <alpha-value>)',
                        sidebar: {
                            DEFAULT: 'oklch(var(--sidebar) / <alpha-value>)',
                            foreground: 'oklch(var(--sidebar-foreground) / <alpha-value>)',
                            primary: 'oklch(var(--sidebar-primary) / <alpha-value>)',
                            'primary-foreground': 'oklch(var(--sidebar-primary-foreground) / <alpha-value>)',
                            accent: 'oklch(var(--sidebar-accent) / <alpha-value>)',
                            'accent-foreground': 'oklch(var(--sidebar-accent-foreground) / <alpha-value>)',
                            border: 'oklch(var(--sidebar-border) / <alpha-value>)',
                            ring: 'oklch(var(--sidebar-ring) / <alpha-value>)'
                        }
                    },
                    borderRadius: {
                        DEFAULT: 'var(--radius)',
                        sm: 'calc(var(--radius) - 4px)',
                        md: 'calc(var(--radius) - 2px)',
                        lg: 'var(--radius)',
                        xl: 'calc(var(--radius) + 4px)'
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-foreground antialiased min-h-screen flex flex-col">
