<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NaturalTTS - Browser TTS Engine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        light: {
                            900: '#F9FAFB',
                            800: '#F3F4F6',
                            700: '#E5E7EB',
                            600: '#D1D5DB',
                            500: '#9CA3AF',
                            400: '#6B7280',
                            300: '#4B5563',
                            200: '#374151',
                            100: '#1F2937',
                        },
                        primary: {
                            900: '#4c0d95',
                            800: '#5b16a3',
                            700: '#6a1fb1',
                            600: '#7928ca',
                            500: '#8a3ffc',
                            400: '#9e5cff',
                            300: '#b57bff',
                            200: '#d0a9ff',
                            100: '#ebd6ff',
                        },
                        success: '#10B981',
                        warning: '#FBBF24',
                        error: '#EF4444',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .terminal {
            font-family: 'JetBrains Mono', monospace;
            background-color: #333;
            border: 1px solid #444;
            border-radius: 6px;
            padding: 16px;
            color: #f0f0f0;
            overflow-x: auto;
        }
        .log-system {
            color: #b57bff;
        }
        .log-info {
            color: #63b3ed;
        }
        .log-success {
            color: #10B981;
        }
        .log-warning {
            color: #FBBF24;
        }
        .log-error {
            color: #EF4444;
        }
    </style>
</head>
<body class="bg-white text-light-200 min-h-screen flex flex-col">