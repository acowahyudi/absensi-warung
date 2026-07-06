#!/bin/bash

# Script untuk membuat tunnel HTTPS menggunakan localtunnel (npx)
# Ini diperlukan karena browser HP memblokir lokasi (GPS) pada koneksi HTTP biasa.

PORT=8000

echo "🔌 Memulai tunnel untuk port $PORT..."
echo "📱 Setelah terhubung, buka URL HTTPS yang dihasilkan pada browser HP Anda."
echo "------------------------------------------------------------------"

npx localtunnel --port $PORT
