
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
        <span class='pull-right'><button type='button' id='BtnBack' class='btn btn-xs btn-danger' onclick='Clear()'><i class='fa fa-mail-reply'></i> Kembali</button></span>
        <span class='clearfix'></span>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="IdSkemaGaji" id="IdSkemaGaji">
            <div class='row' id='DataHeadCabang'>
                <div class='col-sm-6'>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12">
                            <h4 class="title title-form">Data Cabang</h4>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Kode Cabang</label>
                        <div class='col-sm-9'>
                            <input type='text' name='KodeCabang' id='KodeCabang' class='form-control' placeholder='Kode Cabang'>
                            <input type='hidden' name='IdCabang' id='IdCabang'>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Nama Cabang</label>
                        <div class='col-sm-9'>
                            <input type='text' id='NamaCabang' class='form-control' readonly placeholder='Nama Cabang'>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Kontrak</label>
                        <div class='col-sm-9' id='ShowKontrak'></div>
                    </div>

                </div>
                <div class='col-sm-6'>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12">
                            <h4 class="title title-form">Data Unit Kerja</h4>
                        </div>
                    </div>
                    <div id='ShowUnitKerja'></div>
                    <div id='ShowUnitKaryawan'></div>
                </div>
            </div>
            <hr>

            <div class='row'>
                <div class='col-sm-6'>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12">
                            <h4 class="title title-form">Data Upah</h4>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Upah Pokok</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control' onkeyup='AngkaRupiah(this); KalkulasiUpah();' name='UpahPokok' id='UpahPokok' placeholder='Upah Pokok'>
                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Uang Makan Parameter</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <input type='text' class='form-control' name='UangMakanPersen' onkeyup='decimal(this); KalkulasiUpah();' id='UangMakanPersen' placeholder='Persentase'>
                                <span class='input-group-addon'><i class='fa fa-percent'></i></span>
                                <input type='text' class='form-control' name='UangMakanHari' onkeyup='AngkaRupiah(this); KalkulasiUpah();' id='UangMakanHari' placeholder='Jumlah Hari'>
                                <span class='input-group-addon'>Hari</span>

                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Uang Makan</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control' readonly name='UangMakan' id='UangMakan' placeholder='Uang Makan'>
                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Uang Transport Parameter</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <input type='text' class='form-control' name='UangTransportPersen' onkeyup='decimal(this); KalkulasiUpah();' id='UangTransportPersen' placeholder='Persentase'>
                                <span class='input-group-addon'><i class='fa fa-percent'></i></span>
                                <input type='text' class='form-control' name='UangTransportHari' onkeyup='AngkaRupiah(this); KalkulasiUpah();' id='UangTransportHari' placeholder='Jumlah Hari'>
                                <span class='input-group-addon'>Hari</span>

                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Uang Transport</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control' readonly name='UangTransport' id='UangTransport' placeholder='Uang Transport'>
                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Tunjangan</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control' onkeyup='AngkaRupiah(this); KalkulasiUpah();'  name='Tunjangan' id='Tunjangan' placeholder='Tunjangan'>
                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Total Upah</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control' readonly  name='Upah' id='Upah' placeholder='Total Upah'>
                            </div>
                        </div>
                    </div>


                </div>
                <div class='col-sm-6'>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12">
                            <h4 class="title title-form">Data Lain-Lain</h4>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>BPJS Kesehatan</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'><i class='fa fa-percent'></i></span>
                                <input type='text' class='form-control' onkeyup='decimal(this); KalkulasiBpjsKes();'  name='BpjsKesPersen' id='BpjsKesPersen' placeholder='Persentase'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control'  name='BpjsKes' id='BpjsKes' readonly placeholder='BPJS Kesehatan'>
                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>BPJS Ketenagakerjaan</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'><i class='fa fa-percent'></i></span>
                                <input type='text' class='form-control' onkeyup='decimal(this); KalkulasiBpjsTk();'  name='BpjsTkPersen' id='BpjsTkPersen' placeholder='Persentase'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control'  name='BpjsTk' id='BpjsTk' readonly placeholder='BPJS Ketenagakerjaan'>
                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Pakaian Kerja & Perlengkapan</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'><i class='fa fa-percent'></i></span>
                                <input type='text' class='form-control' onkeyup='decimal(this); KalkulasiPakaian();'  name='PakaianKerjaPersen' id='PakaianKerjaPersen' placeholder='Persentase'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control'  name='PakaianKerja' id='PakaianKerja' readonly placeholder='Pakaian Kerja'>
                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>THR</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control'  name='Thr'  id='Thr' placeholder='THR'>
                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Pesangon</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control'  name='Pesangon'  id='Pesangon' placeholder='Pesangon'>
                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>DPLK</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control' onkeyup='AngkaRupiah(this)'  name='Dplk' id='Dplk' placeholder='DPLK'>
                            </div>
                        </div>
                    </div>

                    <div class='form-group'>
                        <label class='control-label col-sm-3'>Jasa Paket Tenaga Kerja</label>
                        <div class='col-sm-9'>
                            <div class='input-group'>
                                <span class='input-group-addon'><i class='fa fa-percent'></i></span>
                                <input type='text' class='form-control' onkeyup='decimal(this); KalkulasiJPTK();'  name='JasaPjtkPersen' id='JasaPjtkPersen' placeholder='Persentase'>
                                <span class='input-group-addon'>Rp. </span>
                                <input type='text' class='form-control'  name='JasaPjtk' id='JasaPjtk' readonly placeholder='Jasa Paket Tenaga Kerja'>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <hr>
            <div class='row'>
                <div class='col-sm-12'>
                    <center>
                        <button class='btn btn-lg btn-primary' onclick='SubmitData()' type='button'><i class='fa fa-check-square'></i> Submit</button>
                        <button class='btn btn-lg btn-danger' id='BtnBatal' onclick='Clear()' type='button'><i class='fa fa-mail-reply'></i> Batal</button>
                    </center>
                </div>
            </div>

        </form>

        <div id="DetailData">
            <div class="col-sm-12">
            <?php if($_SESSION['Level'] != "author"){ ?>
            <p id='BtnAdd'>
                <button onclick="Crud()" type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah</button>
                <button onclick="CrudKhusus()" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus-circle"></i> Tambah Skema Khusus</button>
            </p>
            <?php } ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="TableData">
                    <thead>
                        <tr>
                            <th width="5px"><center>No</center></th>
                            <th>Nama Cabang</th>
                            <th>Upah Pokok</th>
                            <th>Jumlah Tenaga Kerja</th>
                            <th width="8%"><center>Aksi</center></th>
                        </tr>
                    </thead>
                </table>

                
            </div>
            </div>
        </div> 

        <div id='DetailDataKaryawan'>
            <div class="col-sm-12">
            <div class='table table-responsive'>
                <table class="table table-bordered table-striped" id="TableDataDetail">
                    <thead>
                        <tr>
                            <th width='5px'><center>No</center></th>
                            <th>Nama Karyawan</th>
                            <th>Jabatan</th>
                            <th>Upah</th>
                            <th width="12%">Aksi</th>
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

<div class='modal fade in' id='modalDetail' data-keyboard="false" data-backdrop="static" tabindex='0' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
<div class='modal-dialog'>
<div class='modal-content modal-lg'>
<div class="modal-header">
    <button type="button" class="close"  data-dismiss="modal">&times;</button>
    <h5 class="modal-title">Detail Data <span id='TitleModal'><span></h5>
</div>
<div class='modal-body'>

    <div id="DetailModal"></div>
    
    

</div>
</div>
</div>
</div>