<?php

namespace App\Core;

use RuntimeException;

/**
 * Unggahan ditolak karena aturan validasi, bukan karena error server.
 * Controller menangkapnya dan menampilkan pesan ramah ke pengguna.
 */
final class BerkasTidakValid extends RuntimeException
{
}
