
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="IdJabatan" id="IdJabatan" value="">

            <div class="form-group">
                <label class="control-label col-sm-2">Kode Jabatan</label>
                <div class="col-sm-4">
                    <input type='text' class='form-control' readonly name='KodeJabatan' id='KodeJabatan' placholder='Kode Jabatan' />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Nama Jabatan</label>
                <div class="col-sm-4">
                    <input type='text' class='form-control' name='NamaJabatan' id='NamaJabatan' placholder='Nama Jabatan' />
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

        <form id='FormUplopad' class="form-horizontal" action="#">
            <div class="form-group">
                <label class="control-label col-sm-2">File</label>
                <div class="col-sm-4">
                    <input type='file' class='form-control' name='File' id='File' accept='.xls' placholder='File' />
                    <br />
                    <div class="alert alert-info alert-dismissible" role="alert">
                        <strong>INFO</strong> Data yang diupload harus sesuai dengan format yang disediakan sistem. <a href='FormatDataExport/FormatJabatan.xls' target='_blank' class='btn btn-success btn-xs'><i class='fa fa-download'> Download Format</i></a>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-5 col-sm-offset-2">
                    <button type="button" onclick="SubmitUpload()" class="btn btn-sm btn-primary"><i class="fa fa-upload"></i> Submit</button>
                    <button type="button" onclick="Clear()" class="btn btn-sm btn-danger"><i class="fa fa-mail-reply" ></i> Kembali</button>
                </div>
            </div>

        </form>

        <div id="DetailData">
            <div class="col-sm-12">
            <?php if($_SESSION['Level'] != "author"){ ?>
            <p>
                <button onclick="Crud()" type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah</button>
                <button onclick="ShowFormUpload()" type="button" class="btn btn-success btn-sm"><i class="fa fa-upload"></i> Upload</button>
            </p>
            <?php } ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="TableData">
                    <thead>
                        <tr>
                            <th width="5px"><center>No</center></th>
                            <th>Kode Jabatan</th>
                            <th>Nama Jabatan</th>
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