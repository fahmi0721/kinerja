
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="IdMutasi" id="IdMutasi" value="">
            <input type="hidden" name="IdPejabat" id="IdPejabat" value="">

            <div class="form-group">
                <label class="control-label col-sm-2">Pejabat</label>
                <div class="col-sm-4">
                <div class='input-group'>
                        <span class='input-group-addon'>NIK</span>
                        <input type='text' class='form-control'  placeholder='NIK' data-toggle='tooltip' title='Press Enter' name='Nik' id='Nik'>
                        <span class='input-group-addon'>Nama</span>
                        <input type='text'  class='form-control' placeholder='Nama' readonly  id='Nama'>
                    </div>
                </div>
            </div>
            

            <div class="form-group">
                <label class="control-label col-sm-2">KJ/Jabatan</label>
                <div class="col-sm-4">
                <div class='input-group'>
                        <span class='input-group-addon'>KJ</span>
                        <input type='text' class='form-control'  placeholder='KJ' readonly id='KelasJabatan'>
                        <span class='input-group-addon'>Jabatan</span>
                        <input type='text'  class='form-control' placeholder='Jabatan' readonly  id='Jabatan'>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">TTL</label>
                <div class="col-sm-4">
                    <div class='input-group'>
                        <span class='input-group-addon'>Tempat</span>
                        <input type='text' class='form-control'  placeholder='Tempat Lahir' readonly  id='TptLahir'>
                        <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                        <input type='text'  class='form-control' placeholder='Tanggal Lahir' readonly id='TglL'>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">TMT Mutasi</label>
                <div class="col-sm-3">
                    <div class='input-group'>
                        <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                        <input type='text' class='form-control'  placeholder='Tempat Lahir'  id='TmtMutasi' name='TmtMutasi'>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Keterangan</label>
                <div class="col-sm-4">
                    <textarea class='form-control' name='Keterangan' id='Keterangan' placeholder='Keterangan' rows='5'></textarea>
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
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>KJ / JABATAN</th>
                            <th>TMT Mutasi</th>
                            <th>Keterangan</th>
                            <th><center>Status</center></th>
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


<div class='modal fade in' id='AprovelModal' data-keyboard="false" data-backdrop="static" tabindex='0' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
<div class='modal-dialog'>
<div class='modal-content'>
<div class="modal-header">
    <button type="button" class="close"  data-dismiss="modal">&times;</button>
    <h5 class="modal-title">Aprove</h5>
</div>
<div class='modal-body'>
    <div id='pesan1'></div>
    <form id='FormAprove' class='form-horizontal'>
        <input type='hidden' id='IdM' name='IdMutasi'>
        <div class='form-group'>
            <label class='control-label col-sm-3'>Status</label>
            <div class='col-sm-9'>
                <select name='Status' class='form-control' id='Status'>
                    <option value=''>... :: Pilih Status :: ..</option>
                    <option value='1'>Setuju</option>
                    <option value='2'>Tidak Setuju</option>
                </select>
            </div>
        </div>
    </form>
            
    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" onclick="SubmitAprove()"><i class="fa fa-check-square"></i> &nbsp;Aprove</button>
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="fa fa-mail-reply"></i> &nbsp;Batal</button>
    </div>
</div>
</div>
</div>
</div>