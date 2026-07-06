#!/bin/bash

# Script untuk menjalankan server Laravel GANJ'S Absensi
# Mendukung path dengan spasi

PROJECT_DIR="/Users/mac/Documents/Penelitian Ilmiah/web_absensi_warung"
PORT=8000
PID_FILE="${PROJECT_DIR}/storage/server.pid"
LOG_FILE="${PROJECT_DIR}/storage/logs/server.log"

is_running() {
  [ -f "${PID_FILE}" ] && kill -0 "$(cat "${PID_FILE}")" 2>/dev/null
}

case "$1" in
  start)
    if is_running; then
      echo "⚠️  Server sudah berjalan (PID: $(cat "${PID_FILE}"))"
      echo "   Akses di: http://127.0.0.1:${PORT}"
    else
      echo "🚀 Menjalankan server GANJ'S Absensi..."
      cd "${PROJECT_DIR}" || exit 1
      nohup php artisan serve --host=0.0.0.0 --port=${PORT} > "${LOG_FILE}" 2>&1 &
      echo $! > "${PID_FILE}"
      sleep 2
      if is_running; then
        echo "✅ Server berjalan!"
        echo "   PID  : $(cat "${PID_FILE}")"
        echo "   URL  : http://127.0.0.1:${PORT}"
        echo "   Log  : ${LOG_FILE}"
      else
        echo "❌ Server gagal dijalankan. Cek log:"
        tail -5 "${LOG_FILE}" 2>/dev/null
        rm -f "${PID_FILE}"
      fi
    fi
    ;;

  stop)
    if is_running; then
      PID=$(cat "${PID_FILE}")
      kill "${PID}"
      rm -f "${PID_FILE}"
      echo "🛑 Server dihentikan (PID: ${PID})"
    else
      echo "⚠️  Server tidak sedang berjalan"
      rm -f "${PID_FILE}"
    fi
    ;;

  restart)
    "$0" stop
    sleep 1
    "$0" start
    ;;

  status)
    if is_running; then
      echo "✅ Server BERJALAN (PID: $(cat "${PID_FILE}"))"
      echo "   URL: http://127.0.0.1:${PORT}"
    else
      echo "🔴 Server TIDAK berjalan"
    fi
    ;;

  log)
    echo "📋 Log server (Ctrl+C untuk keluar):"
    tail -f "${LOG_FILE}"
    ;;

  *)
    echo ""
    echo "🍗 GANJ'S Absensi — Server Manager"
    echo "───────────────────────────────────"
    echo "Penggunaan: ./server.sh {start|stop|restart|status|log}"
    echo ""
    echo "  start    → Jalankan server di background"
    echo "  stop     → Hentikan server"
    echo "  restart  → Restart server"
    echo "  status   → Cek apakah server berjalan"
    echo "  log      → Lihat log server secara live"
    echo ""
    echo "URL Aplikasi: http://127.0.0.1:${PORT}"
    echo ""
    exit 1
    ;;
esac
