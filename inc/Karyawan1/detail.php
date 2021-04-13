<?php 
$serach = isset($_GET['Filter']) ? $_GET['Filter'] : "";
?>
<input type='hidden' id='Filter' value='<?php echo $serach; ?>'>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="IdKaryawan" id="IdKaryawan" value="">
            <div class='row'>
            <div class='col-md-6'>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12">
                        <h4 class="title title-form">Data Pribadi</h4>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Cabang</label>
                    <div class="col-sm-3">
                        <input type='hidden' id='IdCabang' name='IdCabang'>
                        <input type='text' data-toggle='tooltip' title='Press Enter To Load Data' class='form-control' id='KodeCabang' name='KodeCabang' placeholder='Kode Cabang'>
                    </div>
                    <div class="col-sm-6">
                        <input type='text' class='form-control' readonly id='NamaCabang' name='NamaCabang' placeholder='Nama Cabang'>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Status Karyawan</label>
                    <div class="col-sm-9">
                        <div class='input-group'>
                            <span class='input-group-addon'><input type='radio' name='StatusKaryawan' id='StatusKaryawan2' value='2' checked></span>
                            <input type='text' class='form-control' value='PKWTT' />
                            <span class='input-group-addon'><input type='radio' name='StatusKaryawan' id='StatusKaryawan1' value='1'></span>
                            <input type='text' class='form-control' value='PKWT' />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">TTL</label>
                    <div class="col-sm-9">
                        <div class='input-group'>
                            <input type='text' class='form-control' name='TptLahir' id='TptLahir' placeholder='Tempat Lahir' />
                            <span class='input-group-addon'><i class='fa fa-calendar-o'></i></span>
                            <input type='text' class='form-control' name='TglLahir' id='TglLahir' placeholder='Tanggal Lahir' />
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label col-sm-3">NIK</label>
                    <div class="col-sm-9">
                        <div class='input-group'>
                            <input type='text' class='form-control' onkeyup='angka(this)' max-length='9' name='NIK' id='NIK' readonly placeholder='NIK' />
                            <span class='input-group-btn'><button onclick='GetNIK()' type='button' id='BtnGetNik' class='btn btn-info'><i class='fa fa-cog'></i></button></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">No KTP</label>
                    <div class="col-sm-9">
                        <input type='text' class='form-control' onkeyup='angka(this)' name='NoKtp' id='NoKtp' placeholder='No KTP' />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Nama Karyawan</label>
                    <div class="col-sm-9">
                        <input type='text' class='form-control' name='NamaKaryawan' id='NamaKaryawan' placeholder='Nama Karyawan' />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Jenis Kelamin</label>
                    <div class="col-sm-9">
                        <div class='input-group'>
                            <span class='input-group-addon'><input type='radio' value='LAKI-LAKI' name='JenisKelamin' id='JenisKelamin0' checked></span>
                            <input type='text' class='form-control' value='LAKI-LAKI' readonly />
                            <span class='input-group-addon'><input type='radio' value='PEREMPUAN' name='JenisKelamin' id='JenisKelamin1'></span>
                            <input type='text' class='form-control' value='PEREMPUAN' readonly />
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">No HP</label>
                    <div class="col-sm-9">
                        <input type='text' class='form-control' onkeyup='angka(this)' name='NoHp' id='NoHp' placeholder='No HP' />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Unit Tugas</label>
                    <div class="col-sm-9">
                        <select class='form-control' name='UnitTugas' id='UnitTugas'>
                            <option value=''>..:: Unit Tugas ::..</option>
                            <?php 
                                $sqlUnitTugas = 'SELECT KodeDivisi, NamaDivisi FROM tb_divisi ORDER BY NamaDivisi ASC';
                                $queryUnitTugas = $db->query($sqlUnitTugas);
                                while($rUnitTugas = $queryUnitTugas->fetch(PDO::FETCH_ASSOC)){
                                    echo "<option value='$rUnitTugas[KodeDivisi]'>$rUnitTugas[NamaDivisi]</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Jabatan</label>
                    <div class="col-sm-9">
                        <select name='Jabatan' id='Jabatan' class='form-control'>
                            <option value=''>..:: Pilih Jabatan ::..</option>
                            <?php
                                $sqlJabatan = "SELECT KodeJabatan, NamaJabatan FROM tb_jabatan ORDER BY NamaJabatan ASC";
                                $queryJabatan = $db->query($sqlJabatan);
                                while($rJabatan = $queryJabatan->fetch(PDO::FETCH_ASSOC)){
                                    echo "<option value='$rJabatan[KodeJabatan]'>$rJabatan[NamaJabatan]</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">BPJS Kesehatan</label>
                    <div class="col-sm-9">
                        <input type='text' class='form-control' onkeyup='angka(this)' name='BpjsKes' id='BpjsKes' placeholder='BPJS Kesehatan' />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">BPJS Ketenagakerjaan</label>
                    <div class="col-sm-9">
                        <input type='text' class='form-control' onkeyup='angka(this)' name='BpjsTk' id='BpjsTk' placeholder='BPJS Ketenagakerjaan' />
                    </div>
                </div>

            </div>

             <div class='col-md-6'>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12">
                        <h4 class="title title-form">Data Pribadi</h4>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">No Rek Utama</label>
                    <div id='RekUtama'><div class='col-md-9' id='MessageRekUtama'></div></div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">TMT Cabang</label>
                    <div class="col-sm-9">
                        <input type='text' class='form-control'  name='TMTCabang' id='TMTCabang' placeholder='TMT Cabang' />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">TMT ISEMA</label>
                    <div class="col-sm-9">
                        <input type='text' class='form-control' name='TMTIsu' id='TMTIsu' placeholder='TMT ISMA' />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Pendidkan Terakhir</label>
                    <div id='PendidikanTerakhirBox'><div class='col-md-9' id='MessagePendidikanTerakhir'></div></div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Agama</label>
                    <div class="col-sm-9">
                        <select class='form-control' name='Agama' id='Agama'>
                            <?php 
                                $agama = array("","ISLAM","Kristen Protestan", "Katolik", "Hindu","Buddha","Kong Hu Cu");
                                for($i=0; $i < count($agama); $i++){
                                    $label = $i == 0 ? "..:: Pilih Agama ::.." : $agama[$i];
                                    echo "<option value='$agama[$i]'>$label</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>

                

                <div class="form-group">
                    <label class="control-label col-sm-3">Ukurn Baju</label>
                    <div class="col-sm-9">
                        <input type='text' class='form-control' name='UkuranBaju' id='UkuranBaju' placeholder='Ukuran Baju' />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Ukurn Sepatu</label>
                    <div class="col-sm-9">
                        <input type='text' class='form-control' name='UkuranSepatu' id='UkuranSepatu' placeholder='Ukuran Sepatu' />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Alamat</label>
                    <div class="col-sm-9">
                        <textarea class='form-control' placeholder='Alamat' id='Alamat' name='Alamat' rows='3'></textarea>
                    </div>
                </div>
            </div>
            </div>

            <hr>
            <div class='row' id='DataTambahan'>
            <div class='col-md-6'>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12">
                        <h4 class="title title-form">Nomor Rekening</h4>
                    </div>
                </div>
                <div id="col-sm-12"><span id="PesanRekening"></span></div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Nama Bank</label>
                    <div class="col-sm-9">
                        <input type='text' class='form-control' placeholder='Nama Bank' id='Bank'>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Nomor Rekening</label>
                    <div class="col-sm-9">
                        <input type='text' class='form-control' onkeyup='angka(this)' id='Rekening' placeholder='Nomor Rekening'>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <button type="button" onclick="TamabahRekening()" id='BtnTambahRekening' data-id='0' class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah</button>
                    </div>
                </div>
            
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th><center>#</center></th>
                                    <th>Nama Bank</th>
                                    <th>Nomor Rekening</th>
                                </tr>
                            </thead>
                            <tbody id='ShowRekening'></tbody>
                        </table>
                        <div id='FormRekening'></div>
                    </div>
                </div>
            </div>
            <div class='col-md-6'>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12">
                        <h4 class="title title-form">Pendidikan</h4>
                    </div>
                </div>
                <div id="col-sm-12"><span id="PesanPendidikan"></span></div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Tingkat Pendidikan</label>
                    <div class="col-sm-9">
                        <select class='form-control' id='TingkatPendidikan'>
                            <option value=''>..:: Pilih Pendidikan ::..</option>
                            <?php 
                                $sql = "SELECT KodePendidikan, NamaPendidikan FROM tb_pendidikan ORDER BY IdPendidikan ASC";
                                $query = $db->query($sql);
                                while($res = $query->fetch(PDO::FETCH_ASSOC)){
                                    echo "<option value='$res[KodePendidikan]#$res[NamaPendidikan]'>$res[NamaPendidikan]</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Jurusan / Tahun</label>
                    <div class="col-sm-9">
                        <div class='input-group'>
                            <input type='text' class='form-control' id='Jurusan' placeholder='Jurusan'>
                            <span class='input-group-addon'><i class='fa fa-calendar-o'></i></span>
                            <input type='text' class='form-control' onkeyup='angka(this)' id='Tahun' placeholder='Tahun'>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <button type="button" onclick="TamabahPendidikan()" id='BtnTambahPendidikan' data-id='0' class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah</button>
                    </div>
                </div>
            
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th><center>#</center></th>
                                    <th>Tingkat</th>
                                    <th>Jurusan</th>
                                    <th>Tahun</th>
                                </tr>
                            </thead>
                            <tbody id='ShowPendidikan'></tbody>
                        </table>
                        <div id='FormPendidikan'></div>
                    </div>
                </div>
            </div>
            </div>
           

            
            <div class="form-group">
                <div class="col-sm-12">
                    <center><button type="button" onclick="SubmitData()" class="btn btn-lg btn-primary"><i class="fa fa-check-square"></i> Submit</button>
                    <button type="button" onclick="Clear()" class="btn btn-lg btn-danger"><i class="fa fa-mail-reply" ></i> Kembali</button></center>
                </div>
            </div>
        </form>

        <form id="FormUpload" class='form-horizontal' action='#' enctype='multipart/form-data'>
            
            <input type='hidden' name='IdCabang' id='UploadIdCabang'>
            <input type='hidden' name='aksi' id='UploadAksi'>
            <div class='form-group'>
                <label class='control-label col-sm-2'>Cabang</label>
                <div class='col-sm-2'>
                    <input type='text' class='form-control' name='KodeCabang' id='UploadKodeCabang' placeholder='Kode Cabang'>
                </div>
                <div class='col-sm-3'>
                    <input type='text' disabled class='form-control' id='UploadNamaCabang' placeholder='Cabang Cabang'>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Status Karyawan</label>
                <div class="col-sm-5">
                    <div class='input-group'>
                        <span class='input-group-addon'><input type='radio' name='StatusKaryawan' value='2' checked></span>
                        <input type='text' class='form-control' value='PKWTT' />
                        <span class='input-group-addon'><input type='radio' name='StatusKaryawan' value='1'></span>
                        <input type='text' class='form-control' value='PKWT' />
                    </div>
                </div>
            </div>

            <div class='form-group'>
                <label class='control-label col-sm-2'>Data Karyawan</label>
                <div class='col-sm-5'>
                    <input type='file' name='File' id='File' class='form-control'>
                    <br />
                    <div class="alert alert-info alert-dismissible" role="alert">
                        <strong>INFO</strong> Data yang diupload harus sesuai dengan format yang disediakan sistem. <button type='button' class='btn btn-success btn-xs'><i class='fa fa-download'> Download Format</i></button>
                    </div>
                </div>
            </div>

             <div class='form-group'>
                <div class='col-sm-5 col-sm-offset-2'>
                    <button type='button' onclick='SubmitUploadData()' class='btn btn-sm btn-success'><i class='fa fa-upload'></i> Upload</button>
                    <button type='button' onclick="Clear()" class='btn btn-sm btn-danger'><i class='fa fa-mail-reply'></i> Kembali</button>
                </div>
            </div>

        </form>

        <div id="DetailData">
            <div class="col-sm-12">
            <?php if($_SESSION['Level'] != "author"){ ?>
            <p>
                <button onclick="Crud()" type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah</button>
                <button onclick="UploadData()" type="button" class="btn btn-success btn-sm"><i class="fa fa-upload"></i> Import</button>
                <hr>
            </p>
            <?php } ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="TableData">
                    <thead>
                        <tr>
                            <th width="5px"><center>No</center></th>
                            <th>NIK</th>
                            <th>Nama Karyawan</th>
                            <th>JK</th>
                            <th>TTL</th>
                            <th>Cabang</th>
                            <th>Unit Kerja</th>
                            <th>Jabatan</th>
                            <th>TMT ISEMA</th>
                            <th width="10%"><center>Aksi</center></th>
                        </tr>
                    </thead>
                </table>
            </div>
            </div>
            
        </div> 

    </div>
    



    <div class="overlay LoadingState" >
        <i class="fa fa-refresh fa-spin"></i>
    </div>

</div>


<div class='modal fade in' id='modal' data-keyboard="false" data-backdrop="static" tabindex='0' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
<div class='modal-dialog'>
<div class='modal-content'>
<div class="modal-header">
    <button type="button" class="close" id="close_modal" data-dismiss="modal">&times;</button>
    <h5 class="modal-title">Konfirmasi Delete</h5>
</div>
<div class='modal-body'>

    <div id="proses_del"></div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" onclick="SubmitData()"><i class="fa fa-check-square"></i> &nbsp;Hapus</button>
        <button type="button" class="btn btn-sm btn-danger" onclick="Clear()"><i class="fa fa-mail-reply"></i> &nbsp;Batal</button>
    </div>

</div>
</div>
</div>
</div>