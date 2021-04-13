
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="IdPejabat" id="IdPejabat" value="">
            <input type="hidden" name="TmpFoto" id="TmpFoto" value="">

            <div class="form-group">
                <label class="control-label col-sm-2">Nama</label>
                <div class="col-sm-4">
                    <input type='text' class='form-control' onkeyup='StrToUpper(this)' name='Nama' id='Nama' placeholder='Nama' />
                </div>
            </div>
            

            <div class="form-group">
                <label class="control-label col-sm-2">Title</label>
                <div class="col-sm-4">
                    <input type='text' class='form-control'  name='Title' id='Titles' placeholder='Title' />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">TTL</label>
                <div class="col-sm-4">
                    <div class='input-group'>
                        <span class='input-group-addon'>Tempat</span>
                        <input type='text' class='form-control'  placeholder='Tempat Lahir' name='TptLahir' id='TptLahir'>
                        <span class='input-group-addon'>Tanggal</span>
                        <input type='text'  class='form-control' placeholder='Tanggal Lahir' name='TglLahir' id='TglLahir'>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">NIK</label>
                <div class="col-sm-4">
                    <div class='input-group'>
                        <input type='text' class='form-control' readonly  name='Nik' id='Nik' placeholder='NIK' />
                        <span class='input-group-btn' data-toggle='tooltip' title='Klik Untuk Membuat NIK' id='BtnNik'><button type='button' onclick='GetNik()'  class='btn btn-info'><i class='fa fa-cog'></i></button></span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Jenis Kelamin</label>
                <div class="col-sm-4">
                    <div class='input-group'>
                        <span class='input-group-addon'><input type='radio' name='JK' id='JKL' value='Laki-Laki' checked></span>
                        <input type='text' class='form-control' readonly value='Laki-Laki' >
                        <span class='input-group-addon'><input type='radio' name='JK' id='JKP' value='Perempuan'></span>
                        <input type='text' readonly class='form-control' value='Perempuan' >
                    </div>
                </div>
            </div>

            

            <div class="form-group">
                <label class="control-label col-sm-2">No HP</label>
                <div class="col-sm-4">
                    <input type='text' class='form-control'  name='NoHp' id='NoHp' placeholder='No Hp' />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Kelas Jabatan</label>
                <div class="col-sm-4">
                    <input type='text' class='form-control'  name='KelasJabatan' id='KelasJabatan' placeholder='Kelas Jabatan' />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Jabatan</label>
                <div class="col-sm-4">
                    <input type='text' class='form-control'  id='Jabatan' placeholder='Jabatan' />
                    <input type='hidden' class='form-control' name='IdJabatan'  id='IdJabatan' placeholder='IdJabatan' />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Alamat</label>
                <div class="col-sm-4">
                   <textarea class='form-control' id='Alamat' class='form-control' name='Alamat' placeholder='Alamat' rows='5'></textarea>
                </div>
            </div>  

            <div class="form-group">
                <label class="control-label col-sm-2">Foto</label>
                <div class="col-sm-4">
                    <input type='file' class='form-control'  id='Foto' placeholder='Foto' name='Foto' accept=".jpg, .jpeg, .png" />
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
                            <th>NO HP</th>
                            <th>TTL</th>
                            <th>KJ</th>
                            <th>JABATAN</th>
                            <th>ALAMAT</th>
                            <th><center>Foto</center></th>
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


<div class='modal fade in' id='modalDetail' data-keyboard="false" data-backdrop="static" tabindex='0' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
<div class='modal-dialog'>
<div class='modal-content'>
<div class="modal-header">
    <button type="button" class="close" id="close_modal" data-dismiss="modal">&times;</button>
    <h5 class="modal-title">Detail</h5>
</div>
<div class='modal-body'>

    <div id="DetailFoto"></div>

</div>
</div>
</div>
</div>