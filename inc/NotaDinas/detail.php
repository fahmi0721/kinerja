
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="IdNotaDinas" id="IdNotaDinas" value="">
            <input type="hidden" name="TmpFile" id="TmpFile" value="">

            <div class="form-group">
                <label class="control-label col-sm-2">Dari</label>
                <div class="col-sm-5">
                    <div class='input-group'>
                        <span class='input-group-addon'><i class='fa fa-user'></i></span>
                        <input type='text' class='form-control'  id='Dari' placeholder='Dari'>
                        <input type='hidden' id='IdJabatanDari' name='IdJabatanDari' />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Ditujukan</label>
                <div class="col-sm-5">
                    <div class='input-group'>
                        <span class='input-group-addon'><i class='fa fa-user'></i></span>
                        <input type='text' class='form-control'  id='Ditujukan' placeholder='Ditujukan'>
                        <input type='hidden' id='IdJabatanDitujukan' name='IdJabatanDitujukan' />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Nomor Nota Dinas</label>
                <div class="col-sm-5">
                    <div class='input-group'>
                        <input class='form-control' name='ND'  readonly value='ND'>
                        <span class='input-group-addon'>.</span>
                        <input class='form-control' name='NomorUrut' id='NomorUrut' placeholder='0'>
                        <span class='input-group-addon'>/</span>
                        <input class='form-control'  id='Bulan' name='Bulan' placeholder='0' value='<?php echo date('m') ?>' readonly>
                        <span class='input-group-addon'>/</span>
                        <input class='form-control' name='KodeDisposisi'  id="KodeDisposisi">
                        <span class='input-group-addon'>-</span>
                        <input class='form-control' name='Tahun' id='Tahun'  value='<?php echo date('Y') ?>' readonly>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Tanggal Nota Dinas</label>
                <div class="col-sm-5">
                    <div class='input-group'>
                        <input class='form-control' placeholder='Tangal Nota Dinas' name='TanggalNotaDinas' id='TanggalNotaDinas' >
                        <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Perihal</label>
                <div class="col-sm-5">
                    <input type='text' name='Perihal' class='form-control' id='Perihal' placeholder='Perihal' />
                </div>
            </div>       


            <div class="form-group">
                <label class="control-label col-sm-2">Keterangan</label>
                <div class="col-sm-5">
                   <textarea name='Keterangan' class='form-control' id='Keterangan' placeholder='Keterangan' rows='5'></textarea>
                </div>
            </div>  

            <div class="form-group">
                <label class="control-label col-sm-2">File Nota Dinas</label>
                <div class="col-sm-5">
                   <input type='file' name='FileSurat' class='form-control' id='FileSurat' accept='.pdf' placeholder='Ditujukan' />
                </div>
            </div>  

            
            <div class="form-group">
                <div class="col-sm-5 col-sm-offset-2">
                    <button type="button" onclick="SubmitData()" class="btn btn-sm btn-primary"><i class="fa fa-check-square"></i> Submit</button>
                    <button type="button" onclick="Clear()" class="btn btn-sm btn-danger"><i class="fa fa-mail-reply" ></i> Kembali</button>
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
                            <th>Nomor Nota Dinas</th>
                            <th>Perihal</th>
                            <th>Tanggal Nota Dinas</th>
                            <th>Ditujukan</th>
                            <th>Keterangan</th>
                            <th width="8%"><center>File</center></th>
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

<div class='modal fade in' id='DisposisiModal' data-keyboard="false" data-backdrop="static" tabindex='0' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
<div class='modal-dialog'>
<div class='modal-content'>
<div class="modal-header">
    <button type="button" class="close" id="CloseModal" data-dismiss="modal">&times;</button>
    <h5 class="modal-title">Disposisi</h5>
</div>
<div class='modal-body'>

   <form id='FormDisposisi' class='form-horizontal'>
        <input type='hidden' name='IdSurat' id='IdSurat'>
        <input type='hidden' name='IdDisposisi' id='IdDisposisi'>
        <input type='hidden' name='aksi' id='AksiDisposisi'>
    
        <div class='form-group'>
            <label class='col-sm-3 control-label'>NOMOR</label>
            <div class='col-sm-9'>
                <input type='text' readonly class='form-control'  id='NomorNota' placeholder='Nomor Nota Dinas'>
            </div>
        </div>
        <div class='form-group'>
            <label class='col-sm-3 control-label'>TANGGAL</label>
            <div class='col-sm-9'>
                <input type='text' readonly class='form-control' id='TglSurat' placeholder='Tanggal Nota Dinas'>
            </div>
        </div>

        <div class='form-group'>
            <label class='col-sm-3 control-label'>TANGGAL MASUK SURAT</label>
            <div class='col-sm-9'>
                <input type='text' readonly class='form-control' id='TglMasuk' value ='<?php echo date("Y-m-d"); ?>'>
            </div>
        </div>
        
        <div class='form-group'>
            <label class='col-sm-3 control-label'>PERIHAL</label>
            <div class='col-sm-9'>
                <textarea type='text' readonly class='form-control' id='PerihalSurat' placeholder='Perihal'></textarea>
            </div>
        </div>
        <hr>
        <div class='form-group'>
            <label class='col-sm-3 control-label'>KEPADA YTH</label>
            <div class='col-sm-9'>
                <div class='input-group'>
                    <span class='input-group-addon'>S1</span>
                    <input type='text' class='form-control' readonly value='DIREKTUR UTAMA'>
                    <span class='input-group-addon'><input type='checkbox' class='Kepada' id='KepadaS1'  name='Kepada[]' value='S1'></span>
                </div>
                <div class='input-group'>
                    <span class='input-group-addon'>S2</span>
                    <input type='text' class='form-control' readonly value='DIREKTUR OPERASI & KOMERSIAL'>
                    <span class='input-group-addon'><input type='checkbox' class='Kepada' id='KepadaS2' name='Kepada[]' value='S2'></span>
                </div>
                <div class='input-group'>
                    <span class='input-group-addon'>S3</span>
                    <input type='text' class='form-control' readonly value='DIREKTUR SDM & KEUANGAN'>
                    <span class='input-group-addon'><input type='checkbox' class='Kepada' id='KepadaS3' name='Kepada[]'  value='S3'></span>
                </div>
                <hr>
                <h5>LAIN-LAIN</h5>
                <div class='input-group'>
                    <span class='input-group-addon'>B1</span>
                    <input type='text' class='form-control' readonly value='MANAGER KEUANGAN'>
                    <span class='input-group-addon'><input type='checkbox' class='Kepada' id='KepadaB1' name='Kepada[]' value='B1'></span>
                </div>
                <div class='input-group'>
                    <span class='input-group-addon'>B2</span>
                    <input type='text' class='form-control' readonly value='MANAGER SDM & UMUM'>
                    <span class='input-group-addon'><input type='checkbox' class='Kepada' id='KepadaB2' name='Kepada[]' value='B2'></span>
                </div>
                <div class='input-group'>
                    <span class='input-group-addon'>B3</span>
                    <input type='text' class='form-control' readonly value='MANAGER OPERASI & KOMERSIAL'>
                    <span class='input-group-addon'><input type='checkbox' class='Kepada' id='KepadaB3' name='Kepada[]' value='B3'></span>
                </div>
                
            </div>
        </div>
        <div class='form-group'>
            <label class='col-sm-3 control-label'>DISPOSISI</label>
            <div class='col-sm-9'>
                <div class='input-group'>
                    <span class='input-group-addon'>1. </span>
                    <input type='text' class='form-control' readonly value='Untuk diketahui seperlunya'>
                    <span class='input-group-addon'><input type='checkbox' class='Disposisi' name='Disposisi[]' id='DisposisiD1' value='D1'></span>
                </div>

                <div class='input-group'>
                    <span class='input-group-addon'>2. </span>
                    <input type='text' class='form-control' readonly value='Pelajari, untuk saran/pendapat'>
                    <span class='input-group-addon'><input type='checkbox' class='Disposisi' name='Disposisi[]' id='DisposisiD2' value='D2'></span>
                </div>

                <div class='input-group'>
                    <span class='input-group-addon'>3. </span>
                    <input type='text' class='form-control' readonly value='Segera konsep jawaban'>
                    <span class='input-group-addon'><input type='checkbox' class='Disposisi' name='Disposisi[]' id='DisposisiD3' value='D3'></span>
                </div>
                
                <div class='input-group'>
                    <span class='input-group-addon'>4. </span>
                    <input type='text' class='form-control' readonly value='Proses Sesuai ketentuan'>
                    <span class='input-group-addon'><input type='checkbox' class='Disposisi' id='DisposisiD4' name='Disposisi[]' value='D4'></span>
                </div>

                <div class='input-group'>
                    <span class='input-group-addon'>5. </span>
                    <input type='text' class='form-control' readonly value='Bicarakan dengan Saya'>
                    <span class='input-group-addon'><input type='checkbox' class='Disposisi' id='DisposisiD5' name='Disposisi[]' value='D5'></span>
                </div>

                <div class='input-group'>
                    <span class='input-group-addon'>6. </span>
                    <input type='text' class='form-control' readonly value='Untuk di jawab dan jelaskan'>
                    <span class='input-group-addon'><input type='checkbox' class='Disposisi' id='DisposisiD6'  name='Disposisi[]' value='D6'></span>
                </div>

                <div class='input-group'>
                    <span class='input-group-addon'>7. </span>
                    <input type='text' class='form-control' readonly value='Teliti & Laporkan'>
                    <span class='input-group-addon'><input type='checkbox' class='Disposisi' id='DisposisiD7' name='Disposisi[]' value='D7'></span>
                </div>

                <div class='input-group'>
                    <span class='input-group-addon'>8. </span>
                    <input type='text' class='form-control' readonly value='Untuk menjadi perhatian dan pelaksanaan lebih lanjut'>
                    <span class='input-group-addon'><input type='checkbox' class='Disposisi' id='DisposisiD8'  name='Disposisi[]' value='D8'></span>
                </div>
            </div>
        </div>
        <div class='form-group'>
            <label class='col-sm-3 control-label'>CATATAN</label>
            <div class='col-sm-9'>
                <textarea class='form-control' rows='5' id='Catatan' name='Catatan' placeholder='Catatan'></textarea>
            </div>
        </div>
    </form>
    
    <div class="modal-footer" id='BtnSub'>
        <button type="button" class="btn btn-sm btn-primary" onclick="SubmitDisposisi()"><i class="fa fa-check-square"></i> &nbsp;Submit</button>
        <button type="button" class="btn btn-sm btn-danger" onclick="ClearDisposisi()"><i class="fa fa-mail-reply"></i> &nbsp;Batal</button>
    </div>

</div>
</div>
</div>
</div>