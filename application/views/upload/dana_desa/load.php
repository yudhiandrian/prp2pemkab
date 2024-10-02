<div class="table-responsive">
    <table id="load-content" class="table-rekap" style="width: 100%;">
        <thead>
            <tr style="background-color: #1572EB; color: white;">
                <th class="text-center" width="5px">NO</th>
                <th class="text-center">BULAN</th>
                <th class="text-center">ALOKASI</th>
                <th class="text-center">RELAISASI DD</th>
                <th class="text-center">%</th>
                <th class="text-center">REALISASI BLT</th>
                <th class="text-center">REALOKASI</th>
                <th class="text-center">TOTAL PENYALURAN</th>
                <th class="text-center">% TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total_alokasi = 0;
            $total_realisasi_total = 0;
            $total_realisasi_total_blt = 0;
            $total_relokasi_jumlah = 0;
            $total_realisasi_total_salur = 0;
            foreach ($result_realisasi as $r) :
                $total_alokasi += $r['alokasi'];
                $total_realisasi_total += $r['realisasi_total'];
                $total_realisasi_total_blt += $r['realisasi_total_blt'];
                $total_relokasi_jumlah += $r['relokasi_jumlah'];
                $total_realisasi_total_salur += $r['realisasi_total_salur'];

            ?>
                <tr>
                    <td class="center"><?= $no++; ?></td>
                    <td><?= $r['bulan']; ?></td>
                    <td class="text-right"><?= format_rupiah($r['alokasi']); ?></td>
                    <td class="text-right"><?= format_rupiah($r['realisasi_total']); ?></td>
                    <td class="text-right"><?= format_rupiah($r['persen_realisasi']); ?></td>
                    <td class="text-right"><?= format_rupiah($r['realisasi_total_blt']); ?></td>
                    <td class="text-right"><?= format_rupiah($r['relokasi_jumlah']); ?></td>
                    <td class="text-right"><?= format_rupiah($r['realisasi_total_salur']); ?></td>
                    <td class="text-center"><?= number_format($r['persen_total_salur'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>