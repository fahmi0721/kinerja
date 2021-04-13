
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="IdIkp" id="IdIkp" value="">

            <div class="form-group">
                <label class="control-label col-sm-2">Kompetensi</label>
                <div class="col-sm-4">
                    <textarea class='form-control' name='Kompetensi' id='Kompetensi' placeholder='Kompetensi' rows='3'></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Bobot / Target</label>
                <div class="col-sm-4">
                    <div class='input-group'>
                        <span class='input-group-addon'>Bobot</span>
                        <input type='text' onkeyup="angka(this)" autocomplete='off' class='form-control' id='Bobot' name='Bobot' placeholder='Bobot'>
                        <span class='input-group-addon'>Target</span>
                        <input type='text' onkeyup="angka(this)" autocomplete='off' class='form-control' id='Target' name='Target' placeholder='Target'>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Jabatan Terkait</label>
                <div class="col-sm-4">
                    <input class='form-control' name='IdJabatan' id='JabatanTerkait' placeholder='Jabatan Terkait'>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Keterangan</label>
                <div class="col-sm-4">
                   <textarea class='form-control' id='Keterangan' class='form-control' name='Keterangan' placeholder='Keterangan' rows='5'></textarea>
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
                            <th>Kompetensi</th>
                            <th>Bobot</th>
                            <th>Target (%)</th>
                            <th>Jabatan Terkait</th>
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