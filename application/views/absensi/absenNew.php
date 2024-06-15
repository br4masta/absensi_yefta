<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .modal.show .modal-dialog{
        -webkit-transform:unset !important;
        transform:unset !important;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Absen Harian</h4>
            </div>
            <div class="card-body">
                <table class="table w-100">
                    <thead>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Absen Masuk</th>
                        <th>Absen Pulang</th>
                    </thead>
                    <tbody>
                        <tr>
                                <td><i class="fa fa-3x fa-<?= ($absen < 2) ? "warning text-warning" : "check-circle-o text-success" ?>"></i></td>
                                <td><?= tgl_hari(date('d-m-Y')) ?></td>
                                <td>
                                    <button type="btn"  data-keterangan='masuk' data-toggle="modal" data-target="#modal-capture" class="btn btn-primary btn-sm btn-fill showCapture"<?= ($absen >= 1) ? 'disabled style="cursor:not-allowed"' : '' ?>>Absen Masuk</button>
                                </td>
                                <td>
                                    <button type="btn"  data-keterangan='pulang' data-toggle="modal" data-target="#modal-capture"  class="btn btn-success btn-sm btn-fill showCapture"<?= ($absen !== 1 || $absen == 2) ? 'disabled style="cursor:not-allowed"' : '' ?>>Absen Pulang</button>
                                </td>
                        </tr>
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
                        <div id="my_camera"></div>
                        <br />
                        <input type="hidden" id="keterangan">
                        <div id="results" hidden></div>
                    </div>
                    <div class="form-group">
                        <label for="Jenis">Jenis : </label>
                        <select name="Jenis" id="Jenis"  class="form-control">
                            <option value="" disabled >-- Pilih Jenis --</option>
                            <option value="0"  selected>-</option>
                            <option value="1" >Izin</option>
                            <option value="2" >Sakit</option>
                            <option value="2" >Cuti</option>
                        </select>
                    </div>
                    <div class="form-group d-none wrap-keterangan">
                        <label for="Keterangan">Keterangan : </label>
                        <textarea name="Keterangan" id="Keterangan" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-primary w-100 btn-capture" onClick="take_snapshot()" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>


    $(document).on("click",".showCapture",function() {
        let dataKeterangan = $(this).data('keterangan')
        $('#keterangan').val(dataKeterangan)
        console.log(dataKeterangan)

    });

    $(document).on("change","#Jenis",function() {
        if($(this).val()!=0){
            // $('.wrap-keterangan').removeClass('d-none')
            // $('.btn-capture').addClass('d-none')
            $('#my_camera').addClass('d-none')
        }else{
            // $('.wrap-keterangan').addClass('d-none')
            // $('.btn-capture').removeClass('d-none')
            $('#my_camera').removeClass('d-none')
        }
            
        
    });
        // Configure webcam settings and attach it
        Webcam.set({
            width: 450,
            height: 337.5,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#my_camera');

        // Get location and take snapshot
        function take_snapshot() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    let latitude = position.coords.latitude;
                    let longitude = position.coords.longitude;
                    console.log('Data berhasil disimpan', latitude);
                    console.log('Data berhasil disimpan', longitude);

                    // Take snapshot
                    Webcam.snap(function (data_uri) {
                        document.getElementById('results').innerHTML = '<img src="' + data_uri + '"/>';

                        // Send image and location data to server
                        $.ajax({
                            type: 'POST',
                            url: 'save_image',
                            data: {
                                image: $('#Jenis').val()==0?data_uri:'',
                                latitude: latitude,
                                longitude: longitude,
                                keterangan:  $('#keterangan').val(),
                                jenis: $('#Jenis').val()
                            },
                            success: function(response) {
                                let res = JSON.parse(response);
                                if (res.status === "success") {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: 'Data berhasil disimpan',
                                        didClose: () => {
                                            window.location.href = 'detail_absensi';
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Terjadi kesalahan saat menyimpan data'
                                    });
                                }
                            },
                            error: function(error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Terjadi kesalahan saat menyimpan data'
                                });
                            },
                            error: function (error) {
                                console.error('Terjadi kesalahan saat menyimpan data', error);
                            }
                        });
                    });
                }, function (error) {
                    console.error('Error mendapatkan lokasi:', error);
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }
    </script>


