<?php
function kirim_email($attachment, $to, $title, $message)
{
    $email = \Config\Services::email();
    $email_pengirim = EMAIL_ALAMAT;
    $email_nama = EMAIL_NAMA;

    $config['protocol'] = 'smtp';
    $config['SMTPHost'] = 'smtp.gmail.com';
    $config['SMTPUser'] = $email_pengirim;
    $config['SMTPPass'] = EMAIL_PASSWORD;
    $config['SMTPPort'] = 465;
    $config['SMTPCrypto'] = 'ssl';
    $config['mailType'] = 'html';

    $email->initialize($config);
    $email->setFrom($email_pengirim, $email_nama);
    $email->setTo($to);

    if ($attachment) {
        $email->attach($attachment);
    }

    $email->setSubject($title);
    $email->setMessage($message);


    if (!$email->send()) {
        $data = $email->printDebugger(['headers']);
        print_r($data);
        return false;
    } else {
        return true;
    }

    /**
     * Summary of nomor
     * @param mixed $currentPage
     * @param mixed $jumlahBaris
     * @return float|int
     */

}
function nomor($currentPage, $jumlahBaris)
{
    if (is_null($currentPage)) {
        $nomor = 1;
    } else {
        $nomor = 1 + ($jumlahBaris * ($currentPage - 1));
    }
    return $nomor;
}
function notif_swal($icon, $title)
{
    session()->setFlashdata('swal_icon', $icon);
    session()->setFlashdata('swal_title', $title);
}


function notif_swal_dua($icon, $text)
{
    session()->setFlashdata('swal_icon2', $icon);
    session()->setFlashdata('swal_text2', $text);
}

/** Buat berhasil absen */
function notif_swal_tiga($icon, $text, $title)
{
    session()->setFlashdata('swal_icon3', $icon);
    session()->setFlashdata('swal_text3', $text);
    session()->setFlashdata('swal_title3', $title);
}
function notif_role($icon, $title)
{
    session()->setFlashdata('role_icon', $icon);
    session()->setFlashdata('role_title', $title);
}

function tanggal_indo($date)
{
    // Array nama bulan
    $bulan = [
        '1' => 'Januari',
        '2' => 'Februari',
        '3' => 'Maret',
        '4' => 'April',
        '5' => 'Mei',
        '6' => 'Juni',
        '7' => 'Juli',
        '8' => 'Agustus',
        '9' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];

    // // Array nama hari
    // $hari = [
    //     '1' => 'Senin',
    //     '2' => 'Selasa',
    //     '3' => 'Rabu',
    //     '4' => 'Kamis',
    //     '5' => 'Jumat',
    //     '6' => 'Sabtu',
    //     '7' => 'Minggu',
    // ];

    // Konversi tanggal ke format yang diinginkan
    $timestamp = strtotime($date);
    $day = date('d', $timestamp);
    $month = date('n', $timestamp);
    $year = date('Y', $timestamp);
    // $dayOfWeek = date('N', $timestamp);

    // Format tanggal menjadi string
    // $formatted_date = $hari[$dayOfWeek] . ', ' . sprintf('%02d', $day) . ' ' . $bulan[$month] . ' ' . $year;
    $formatted_date = sprintf('%02d', $day) . ' ' . $bulan[$month] . ' ' . $year;

    return $formatted_date;
}