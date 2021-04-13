
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="IdDivisi" id="IdDivisi" value="">
            <div class='row'>
                <div class='col-sm-6'> 
                    <div class='row'><div class='col-sm-12'><h4 class='title title-form'>Data Cabang</h4></div></div>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Kode Cabang</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <input type='text' class='form-control' placeholder='Kode Cabang/Nama Cabang' id='KodeCabang'> 
                                <input type='hidden'  id='IdCabang' name='IdCabang'> 
                                <span class='input-group-addon'><i class='fa fa-key'></i></span>
                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Nama Cabang</label>
                        <div class='col-sm-9'>
                            <input type='text' readonly class='form-control' placeholder='Nama Cabang' id='NamaCabang'> 
                        </div>
                    </div>
                </div>

                <div class='col-sm-6'>
                    <div class='row'><div class='col-sm-12'><h4 class='title title-form'>Data Kontrak</h4></div></div>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Nomor Kontrak</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <input type='text' class='form-control' placeholder='Nomor Kontrak/Judul Konrak' id='NomorKontrak'> 
                                <input type='hidden'  id='IdKontrak' name='IdKontrak'> 
                                <span class='input-group-addon'><i class='fa fa-key'></i></span>
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Item Kontrak</label>
                        <div class='col-sm-9' id='ShowItemKontrak'></div>
                    </div>
                </div>
            </div>
            <hr>

            <div class='row'>
                <div class='col-sm-6'> 
                    <div class='row'><div class='col-sm-12'><h4 class='title title-form'>Data Upah</h4></div></div>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Upah Pokok</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='Upah Pokok' id='UpahPokok'> 
                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Uang Makan Parameter</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='Persentase' id='UangMakanPersen'> 
                                <span class='input-group-addon'>%</span>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='Jumlah Hari' id='UangMakanHari'> 
                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Uang Makan</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='Uang Makan' id='UangMakan'> 
                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Uang Transport Parameter</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='Persentase' id='UangTransportPersen'> 
                                <span class='input-group-addon'>%</span>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='Jumlah Hari' id='UangTransportHari'> 
                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Uang Transport</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='Uang Transport' id='UangTransport'> 
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Tunjangan</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='Tunjangan' id='Tunjangan'> 
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Total Upah</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='Total Upah' id='TotalUpah'> 
                            </div>
                        </div>
                    </div>
                </div>

                <div class='col-sm-6'>
                    <div class='row'><div class='col-sm-12'><h4 class='title title-form'>Data Lainnya</h4></div></div>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>BPJS Kesehatan</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='Persentase' id='BpjsKesPersen'> 
                                <span class='input-group-addon'>%</span>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='BPJS Kesehatan' id='BpjsKes'> 
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>BPJS Ketenagakerjaan</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='Persentase' id='BpjsTkPersen'> 
                                <span class='input-group-addon'>%</span>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='BPJS Ketenagakerjan' id='BpjsTk'> 
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Pakaain Kerja & Perlengkapan </label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='Persentase' id='BpjsTkPersen'> 
                                <span class='input-group-addon'>%</span>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='Pakaain Kerja & Perlengkapan ' id='PakaianKerja'> 
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>THR</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='THR' id='Thr'> 
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Pesangon</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='Pesangon' id='Pesangon'> 
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>DPLK</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='DPLK' id='Dplk'> 
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Jasa Paket Tenaga Kerja</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='Persentase' id='JasaPjtkPersen'> 
                                <span class='input-group-addon'>%</span>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control Pengupahan' readonly placeholder='Jasa Paket Tenaga Kerja' id='JasaPjtk'> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class='row'>
                <div class='col-sm-12'><h4 class='title title-form'>Data Karyawan</h4></div>
                <div class='col-sm-12'>
                    <div class='row' id='ShowDataKaryawan'></div>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-12">
                    <center>
                        <button type="button" onclick="SubmitData()" class="btn btn-lg btn-primary"><i class="fa fa-check-square"></i> Submit</button>
                        <button type="button" onclick="Clear()" class="btn btn-lg btn-danger"><i class="fa fa-mail-reply" ></i> Kembali</button>
                    </center>
                </div>
            </div>
        </form>

        <div id="DetailData">
            <div class="col-sm-12">
            <?php if($_SESSION['Level'] != "author"){ ?>
            <p>
                <button onclick="Crud()" type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah</button>
            </p>
            <?php } ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="TableData">
                    <thead>
                        <tr>
                            <th width="5px"><center>No</center></th>
                            <th>Kode Divisi</th>
                            <th>Nama Divisi</th>
                            <th>Keterangan</th>
                            <th width="8%"><center>Aksi</center></th>
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