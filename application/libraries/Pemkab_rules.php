<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pemkab_rules
{
    public static function uuid() {
        $b = random_bytes(16); $b[6] = chr((ord($b[6]) & 15) | 64); $b[8] = chr((ord($b[8]) & 63) | 128);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($b), 4));
    }
    public static function text($v, $max, $required = true) {
        $v = trim((string)$v);
        if (($required && $v === '') || mb_strlen($v) > $max) throw new InvalidArgumentException('Teks wajib diisi dan maksimal '.$max.' karakter.');
        return $v;
    }
    public static function integer($v, $min, $max) {
        if (filter_var($v, FILTER_VALIDATE_INT) === false || $v < $min || $v > $max) throw new InvalidArgumentException('Angka di luar rentang '.$min.'–'.$max.'.');
        return (int)$v;
    }
    public static function money($v) {
        // Canonical decimal; UI explicitly explains no thousands separators.
        if (!preg_match('/^\d{1,15}(?:\.\d{1,2})?$/D', (string)$v)) throw new InvalidArgumentException('Nilai rupiah harus angka positif tanpa pemisah ribuan; desimal memakai titik.');
        $s = explode('.', (string)$v); return $s[0].'.'.str_pad(isset($s[1]) ? $s[1] : '', 2, '0');
    }
    public static function cents($v) {
        $parts=explode('.',self::money($v)); return (int)$parts[0]*100+(int)$parts[1];
    }
    public static function date($v, $tahun = null, $nullable = false) {
        if ($nullable && !$v) return null;
        $d = DateTimeImmutable::createFromFormat('!Y-m-d', (string)$v);
        if (!$d || $d->format('Y-m-d') !== $v || ($tahun !== null && (int)$d->format('Y') !== (int)$tahun)) throw new InvalidArgumentException('Tanggal harus valid dan berada dalam tahun kegiatan.');
        return $v;
    }
    public static function json($v) {
        $s = json_encode($v, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR); return $s;
    }
    public static function rekap($rows) {
        $r = ['jumlah_kegiatan'=>count($rows),'total_pagu'=>0,'total_nilai'=>0,'total_realisasi'=>0,'total_pagu_berkontrak'=>0,'persen_fisik'=>0,'persen_keuangan'=>0,'belum_mulai'=>0,'dikerjakan'=>0,'selesai_dikerjakan'=>0];
        foreach ($rows as $k) {
            $r['total_pagu'] += (float)$k['pagu']; $r['total_nilai'] += (float)$k['nilai'];
            $r['total_realisasi'] += min((float)$k['realisasi_periode'], max(0,(float)$k['nilai']));
            if ((float)$k['nilai']>0) $r['total_pagu_berkontrak'] += (float)$k['pagu'];
            $r[$k['st_kontrak']==='B' ? 'belum_mulai' : ($k['st_kontrak']==='P' ? 'dikerjakan' : 'selesai_dikerjakan')]++;
        }
        if ($r['total_pagu']>0) {
            foreach ($rows as $k) $r['persen_fisik'] += (float)$k['pagu']/$r['total_pagu'] * (float)$k['fisik_periode'];
            if ($r['total_nilai']>0) $r['persen_keuangan'] = min(100,$r['total_realisasi']/$r['total_nilai']*100)*$r['total_pagu_berkontrak']/$r['total_pagu'];
        }
        $r['persen_fisik']=round(min(100,$r['persen_fisik']),2); $r['persen_keuangan']=round(min(100,$r['persen_keuangan']),2); return $r;
    }
}
