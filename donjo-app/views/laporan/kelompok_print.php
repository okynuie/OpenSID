<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Cetak Laporan Kelompok Rentan</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="<?= asset('css/report.css') ?>" rel="stylesheet" type="text/css">
</head>

<body>
	<div id="container">
		<!-- Print Body -->
		<div id="body">
			<table width="100%">
				<tbody>
					<tr align="center">
						<td width="100%">
							<h3>PEMERINTAH KABUPATEN/KOTA <?= strtoupper($config['nama_kabupaten']) ?></h3>
						</td>
					</tr>
					<tr align="center">
						<td width="100%">
							<h4>DATA PILAH KEPENDUDUKAN MENURUT UMUR DAN FAKTOR KERENTANAN (LAMPIRAN A - 9)</h4>
						</td>
					</tr>
				</tbody>
			</table>
			<br>
			<table>
				<tbody>
					<tr>
						<td><?= ucwords($this->setting->sebutan_desa) ?>/Kelurahan</td>
						<td width="3%">:</td>
						<td width="38.5%"><?= $config['nama_desa'] ?></h4>
						</td>
						<td></td>
					</tr>
					<tr>
						<td><?= ucwords($this->setting->sebutan_kecamatan) ?></td>
						<td width="3%">:</td>
						<td width="38.5%"><?= $config['nama_kecamatan'] ?></td>
						<td></td>
					</tr>
					<tr>
						<td>Laporan Bulan</td>
						<td width="3%">:</td>
						<td><?= getBulan(date('m')) ?></td>
						<td width="40%"></td>
					</tr>
					<?php if ($dusun) : ?>
						<tr>
							<td>Dusun</td>
							<td width="3%">:</td>
							<td>
								<?= $dusun ?>
							</td>
							<td width="40%"></td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
			<br>
			<table class="border thick">
				<thead>
					<?php if ($_SESSION['dusun'] != '') : ?>
						<tr>
							<h3>DATA PILAH <?= strtoupper($this->setting->sebutan_dusun) ?> <?= $_SESSION['dusun'] ?></h3>
						</tr>
					</thead>
					<tbody>
						<?php
                            $bayi = 0;
        $balita                   = 0;
        $sd                       = 0;
        $smp                      = 0;
        $sma                      = 0;
        $lansia                   = 0;
        $cacat                    = 0;
        $sakit_L                  = 0;
        $sakit_P                  = 0;
        $hamil                    = 0;
        ?>
						<?php foreach ($main as $data): ?>
							<tr>
								<td align="right"><?= $data['dusunnya']?></td>
								<td align="right"><?= $data['rw']?></td>
								<td align="right"><?= $data['rt']?></td>
								<td align="right"><?= $data['L']?></td>
								<td align="right"><?= $data['P']?></td>
								<td width="13%" align="right"><?= $data['bayi']?></td>
								<td width="14%" align="right"><?= $data['balita']?></td>
								<td width="13%" align="right"><?= $data['sd']?></td>
								<td width="15%" align="right"><?= $data['smp']?></td>
								<td width="15%" align="right"><?= $data['sma']?></td>
								<td width="13%" align="right"><?= $data['lansia']?></td>
								<td align="right"><?= $data['cacat']?></td>
								<td align="right"><?= $data['sakit_L']?></td>
								<td align="right"><?= $data['sakit_P']?></td>
								<td align="right"><?= $data['hamil']?></td>
								<?php
                    $bayi += $data['bayi'];
                            $balita += $data['balita'];
                            $sd += $data['sd'];
                            $smp += $data['smp'];
                            $sma += $data['sma'];
                            $lansia += $data['lansia'];
                            $cacat += $data['cacat'];
                            $sakit_L += $data['sakit_L'];
                            $sakit_P += $data['sakit_P'];
                            $hamil += $data['hamil'];
                            ?>
							</tr>
						<?php endforeach; ?>
					</tbody>

				<thead>
					<tr>
						<th colspan="5" align="center">
							<div align="center">Total</div>
						</th>
						<th>
							<div align="right"><?= $bayi; ?></div>
						</th>
						<th>
							<div align="right"><?= $balita; ?></div>
						</th>
						<th>
							<div align="right"><?= $sd; ?></div>
						</th>
						<th>
							<div align="right"><?= $smp; ?></div>
						</th>
						<th>
							<div align="right"><?= $sma; ?></div>
						</th>
						<th>
							<div align="right"><?= $lansia; ?></div>
						</th>
						<th>
							<div align="right"><?= $cacat; ?></div>
						</th>
						<th>
							<div align="right"><?= $sakit_L; ?></div>
						</th>
						<th>
							<div align="right"><?= $sakit_P; ?></div>
						</th>
						<th>
							<div align="right"><?= $hamil; ?></div>
						</th>
					</tr>
				</thead>
			</table>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
		</div>
	</div>
	<label>Tanggal cetak : &nbsp; </label><?= tgl_indo(date('Y m d')) ?>
	</div>

</body>

</html>