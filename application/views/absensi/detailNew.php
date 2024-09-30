<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .modal.show .modal-dialog{
        -webkit-transform:unset !important;
        transform:unset !important;
    }

    #mapid {
            height: 400px;
            width: 100%;
        }

    .show_gambar{
        cursor: pointer;
    }
</style>

<div class="row mb-2">
    <h4 class="col-xs-12 col-sm-6 mt-0">Detail Absen</h4>
    <div class="col-xs-12 col-sm-6 ml-auto text-right">
        <form action="" method="get">
            <div class="row">
                <div class="col">
                    <select name="bulan" id="bulan" class="form-control">
                        <option value="" disabled selected>-- Pilih Bulan --</option>
                        <?php foreach($all_bulan as $bn => $bt): ?>
                            <option value="<?= $bn ?>" <?= ($bn == $bulan) ? 'selected' : '' ?>><?= $bt ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col ">
                    <select name="tahun" id="tahun" class="form-control">
                        <option value="" disabled selected>-- Pilih Tahun</option>
                        <?php for($i = date('Y'); $i >= (date('Y') - 5); $i--): ?>
                            <option value="<?= $i ?>" <?= ($i == $tahun) ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col ">
                    <button type="submit" class="btn btn-primary btn-fill btn-block">Tampilkan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <table class="table border-0">
                            <tr>
                                <th class="border-0 py-0">Nama</th>
                                <th class="border-0 py-0">:</th>
                                <th class="border-0 py-0"><?= $karyawan->nama ?></th>
                            </tr>
                            <tr>
                                <th class="border-0 py-0">Divisi</th>
                                <th class="border-0 py-0">:</th>
                                <th class="border-0 py-0"><?= $karyawan->nama_divisi ?></th>
                            </tr>
                        </table>
                    </div>
                    <div class="col-xs-12 col-sm-6 ml-auto text-right mb-2">
                        <div class="dropdown d-inline">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="droprop-action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-print"></i>
                                Export Laporan
                            </button>
                            <div class="dropdown-menu" aria-labelledby="droprop-action">
                                <a href="<?= base_url('absensi/export_pdf/' . $this->uri->segment(3) . "?bulan=$bulan&tahun=$tahun") ?>" class="dropdown-item" target="_blank"><i class="fa fa-file-pdf-o"></i> PDF</a>
                                <a href="<?= base_url('absensi/export_excel/' . $this->uri->segment(3) . "?bulan=$bulan&tahun=$tahun") ?>" class="dropdown-item" target="_blank"><i class="fa fa-file-excel-o"></i> Excel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
            <div class="card-body">
                <h4 class="card-title mb-4">Absen Bulan : <?= bulan($bulan) . ' ' . $tahun ?></h4>
                <table class="table table-striped table-bordered">
                    <thead>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                    </thead>
                    <tbody>
                        <?php if($absen): ?>
                            <?php foreach($hari as $i => $h): ?>
                                <?php
                                    $absen_harian = array_search($h['tgl'], array_column($absen, 'tgl')) !== false ? $absen[array_search($h['tgl'], array_column($absen, 'tgl'))] : '';
                                    // var_dump($absen_harian);
                                ?>
                                <tr>
                                    <td><?= ($i+1) ?></td>
                                    <td><?= $h['hari'] . ', ' . $h['tgl'] ?></td>
                                    <td><?= check_jamNew(@$absen_harian['jam_masuk'], 'Masuk',false,@$absen_harian['id_absen'],@$absen_harian['latitude_masuk'],@$absen_harian['longitude_masuk'],@$absen_harian['user_name'],$absen_harian) ?></td>
                                    <td><?= check_jamNew(@$absen_harian['jam_pulang'], 'Pulang',false,@$absen_harian['id_absen_pulang'],@$absen_harian['latitude_pulang'],@$absen_harian['longitude_pulang'],@$absen_harian['user_name'],$absen_harian) ?></td>
                                    
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="bg-light" colspan="4">Tidak ada data absen</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-capture" tabindex="-1" role="dialog" aria-labelledby="modal-add-divisi-label" aria-hidden="true">
    <div class="modal-dialog h-100 mt-0 d-flex align-items-center" role="document">
        <div class="modal-content">
            <form id="form-add-divisi" action="<?= base_url('divisi/store') ?>" method="post" onsubmit="return false">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-add-divisi-label">Absen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <img src="" id="result" alt="" style="object-fit:contain">
                        <input type="hidden" id="lat">
                        <input type="hidden" id="long">
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <td class="text-center"><button onclick="showMap()" type="btn" class="btn btn-warning text-xs bg-white ">Lihat Lokasi</button></td>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    let url = 'http://localhost/absensi_yefta/'
    let user_name = ''

    $(document).on("click",".show_gambar",function() {
        user_name= $(this).data('username')
        $('#result').attr('src',`${url+$(this).data('file')}`)
        $('#lat').val($(this).data('lat'))
        $('#long').val($(this).data('long'))
       $('#modal-capture').modal('show')
    });

    function showMap() {
        // Ganti dengan koordinat yang disimpan
        const latitude = $('#lat').val();
        const longitude = $('#long').val();

        Swal.fire({
            title: user_name+' Location',
            html: '<div id="mapid"></div>',
            width: 600,
            padding: '3em',
            showCloseButton: true,
            didOpen: () => {
                const map = L.map('mapid').setView([latitude, longitude], 17);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
                L.marker([latitude, longitude]).addTo(map)
                    .bindPopup(user_name)
                    .openPopup();
            }
        });
    }
</script>