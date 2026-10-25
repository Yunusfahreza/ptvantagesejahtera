#!/usr/bin/env bash
# Pembantu uji black box lewat HTTP. Dipakai manual saat pengujian.
#
#   BASE=http://127.0.0.1:8099 ./tests/uji.sh
#
# Setiap fungsi memakai cookie jar terpisah supaya sesi antar peran tidak tercampur.

BASE="${BASE:-http://127.0.0.1:8099}"
JAR_DIR="${JAR_DIR:-/tmp/lms-jar}"
mkdir -p "$JAR_DIR"

# Ambil token CSRF dari meta tag halaman mana pun.
csrf() {
    local jar="$1" path="$2"
    curl -s -b "$jar" -c "$jar" "$BASE$path" \
        | grep -o 'name="csrf-token" content="[^"]*"' \
        | head -1 | sed 's/.*content="//; s/"$//'
}

# login <nama-sesi> <username> <password>
login() {
    local jar="$JAR_DIR/$1.txt" token
    rm -f "$jar"
    token=$(csrf "$jar" /login)
    curl -s -b "$jar" -c "$jar" -o /dev/null -w "%{http_code} %{redirect_url}\n" \
        -d "_token=$token" --data-urlencode "username=$2" --data-urlencode "password=$3" \
        "$BASE/login"
}

# status <nama-sesi> <path>   -> kode HTTP
status() {
    curl -s -b "$JAR_DIR/$1.txt" -o /dev/null -w "%{http_code}" "$BASE$2"
}

# isi <nama-sesi> <path>      -> body
isi() {
    curl -s -b "$JAR_DIR/$1.txt" "$BASE$2"
}

# kirim <nama-sesi> <path> <data...>  -> POST dengan token CSRF yang sah
kirim() {
    local sesi="$1" path="$2"; shift 2
    local jar="$JAR_DIR/$sesi.txt" token
    token=$(csrf "$jar" /profil)
    local args=(-d "_token=$token")
    for d in "$@"; do args+=(--data-urlencode "$d"); done
    curl -s -b "$jar" -c "$jar" -o /dev/null -w "%{http_code} %{redirect_url}\n" "${args[@]}" "$BASE$path"
}

# kirim_tanpa_csrf <nama-sesi> <path> <data...>
kirim_tanpa_csrf() {
    local sesi="$1" path="$2"; shift 2
    local args=()
    for d in "$@"; do args+=(--data-urlencode "$d"); done
    curl -s -b "$JAR_DIR/$sesi.txt" -o /dev/null -w "%{http_code}" "${args[@]}" "$BASE$path"
}

# unggah <nama-sesi> <path> <field=@file> <data...>
unggah() {
    local sesi="$1" path="$2" berkas="$3"; shift 3
    local jar="$JAR_DIR/$sesi.txt" token
    token=$(csrf "$jar" /profil)
    local args=(-F "_token=$token" -F "$berkas")
    for d in "$@"; do args+=(-F "$d"); done
    curl -s -b "$jar" -c "$jar" -o /dev/null -w "%{http_code} %{redirect_url}\n" "${args[@]}" "$BASE$path"
}

# Waktu dalam zona aplikasi (Asia/Jakarta), bukan zona php.ini CLI.
# Dipakai menyiapkan tenggat dan jendela kuis saat pengujian.
waktu() {
    # "--" wajib karena argumen bisa dimulai dengan tanda minus.
    php -r 'date_default_timezone_set("Asia/Jakarta"); echo date("Y-m-d\TH:i", strtotime($argv[1] ?: "now"));' -- "$1"
}
