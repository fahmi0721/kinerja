
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="IdSuratKeluar" id="IdSuratKeluar" value="">
            <input type="hidden" name="IdJenisSurat" id="IdJenisSurat" value="">
            <input type="hidden" name="TmpFile" id="TmpFile" value="">

            <div class="form-group">
                <label class="control-label col-sm-2">Tahun</label>
                <div class="col-sm-5">
                   <select name='Tahuns' id='Tahuns' class="form-control">
                   		<?php 
                   			$now = 2019;
                   			for($i=2020; $i >= $now; $i--){
                   				echo "<option value='".$i."'>".$i."</option>";
                   			}
                   		?>
                   </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Jenis Surat</label>
                <div class="col-sm-5">
                    <div class='input-group'>
                        <input class='form-control' name='Kode' id='Kode' placeholder='Kode/Jenis Surat'>
                        <span class='input-group-addon'><i class='fa fa-tag'></i></span>
                        <input class='form-control'  id='NamaJenis' placeholder='Jenis Surat' readonly>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Nomor Surat</label>
                <div class="col-sm-5">
                    <div class='input-group'>
                        <input class='form-control' name='NomorUrut' id='NomorUrut' placeholder='0'>
                        <span class='input-group-addon'>/</span>
                        <input class='form-control'  id='KodeJenis' name='KodeJenis' placeholder='-' readonly>
                        <span class='input-group-addon'>/</span>
                        <input class='form-control' name='Halaman' id='Halaman' placeholder='0'>
                        <span class='input-group-addon'>/</span>
                        <input class='form-control' name='isema' id="Lbl"  readonly value='ISEMA'>
                        <span class='input-group-addon'>-</span>
                        <input class='form-control' name='Tahun' id='Tahun'  readonly>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Tanggal Surat</label>
                <div class="col-sm-5">
                    <div class='input-group'>
                        <input class='form-control' placeholder='Tangal Surat' name='TanggalSurat' id='TanggalSurat' placeholder=''>
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
                <label class="control-label col-sm-2">Ditujukan</label>
                <div class="col-sm-5">
                   <input type='text' name='Ditujukan' class='form-control' id='Ditujukan' placeholder='Ditujukan' />
                </div>
            </div>  

            <div class="form-group">
                <label class="control-label col-sm-2">Keterangan</label>
                <div class="col-sm-5">
                   <textarea name='Keterangan' class='form-control' id='Keterangan' placeholder='Keterangan' rows='5'></textarea>
                </div>
            </div>  

            <div class="form-group">
                <label class="control-label col-sm-2">File Surat</label>
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
                            <th>Nomor Surat</th>
                            <th>Perihal</th>
                            <th>Tanggal Surat</th>
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