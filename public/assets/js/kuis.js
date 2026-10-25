/*
 * Pengerjaan kuis: countdown dan autosave jawaban.
 *
 * Catatan penting: skrip ini hanya bantuan UX. Batas waktu dan nilai
 * ditegakkan di server. Mengubah jam komputer atau mematikan JavaScript
 * tidak memberi keuntungan apa pun.
 */

(function () {
    'use strict';

    var wadah = document.getElementById('daftarSoal');
    var jam = document.getElementById('countdown');
    var status = document.getElementById('statusSimpan');
    var form = document.getElementById('formSelesai');

    if (!wadah || !jam || !form) {
        return;
    }

    var kuisId = wadah.getAttribute('data-kuis');
    var urlJawab = wadah.getAttribute('data-url-jawab');

    /* Sisa detik berasal dari server, lalu dihitung turun secara lokal. */
    var sisa = parseInt(jam.getAttribute('data-sisa'), 10);
    if (isNaN(sisa) || sisa < 0) {
        sisa = 0;
    }

    function format(detik) {
        var m = Math.floor(detik / 60);
        var s = detik % 60;
        return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
    }

    function tampilkan() {
        jam.textContent = format(sisa);
        jam.classList.toggle('habis', sisa <= 60);
    }

    function pesan(teks, kelas) {
        if (!status) {
            return;
        }
        status.textContent = teks;
        status.className = kelas || 'text-body-secondary';
    }

    /* Kirim satu jawaban. Server yang memutuskan boleh atau tidak. */
    function simpan(soalId, nilai, label) {
        var data = new URLSearchParams();
        data.set('_token', window.csrfToken());
        data.set('kuis_id', kuisId);
        data.set('soal_id', soalId);
        data.set('jawaban', nilai);

        pesan('menyimpan...', 'text-body-secondary');

        fetch(urlJawab, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-Token': window.csrfToken(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: data.toString(),
            credentials: 'same-origin'
        })
            .then(function (res) {
                return res.json().catch(function () {
                    return { ok: false, pesan: 'Gagal membaca balasan server.' };
                });
            })
            .then(function (hasil) {
                if (hasil.ok) {
                    pesan('tersimpan ' + (label || ''), 'text-success');

                    /* Selaraskan ulang dengan sisa waktu menurut server. */
                    if (typeof hasil.sisa === 'number') {
                        sisa = hasil.sisa;
                        tampilkan();
                    }
                    return;
                }

                if (hasil.selesai) {
                    pesan(hasil.pesan || 'Waktu habis.', 'text-danger');
                    akhiri();
                    return;
                }

                pesan(hasil.pesan || 'Gagal menyimpan jawaban.', 'text-danger');
            })
            .catch(function () {
                pesan('koneksi terganggu, jawaban belum tersimpan', 'text-danger');
            });
    }

    var sudahDiakhiri = false;

    /* Waktu habis: kirim form supaya server memfinalisasi dari jawaban tersimpan. */
    function akhiri() {
        if (sudahDiakhiri) {
            return;
        }
        sudahDiakhiri = true;
        form.removeAttribute('data-konfirmasi');
        form.submit();
    }

    wadah.addEventListener('change', function (ev) {
        var input = ev.target;
        if (!(input instanceof HTMLInputElement) || input.type !== 'radio') {
            return;
        }

        var kartu = input.closest('[data-soal]');
        if (!kartu) {
            return;
        }

        simpan(kartu.getAttribute('data-soal'), input.value, 'opsi ' + input.value.toUpperCase());
    });

    tampilkan();

    var timer = setInterval(function () {
        sisa -= 1;

        if (sisa <= 0) {
            sisa = 0;
            tampilkan();
            clearInterval(timer);
            pesan('waktu habis, mengumpulkan jawaban...', 'text-danger');
            akhiri();
            return;
        }

        tampilkan();
    }, 1000);
}());
