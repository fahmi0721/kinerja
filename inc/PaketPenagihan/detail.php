
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#">
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="IdPaketPenagihan" id="IdPaketPenagihan" value="">

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
                        <label class='control-label col-sm-3'>Nama Paket</label>
                        <div class='col-sm-9'>
                            <input type='text' id='NamaPaket' name='NamaPaket' class='form-control' placeholder='Nama Paket'>
                        </div>
                    </div>
                </div>
                <div class='col-sm-6'>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12">
                            <h4 class="title title-form">Data Unit Kerja</h4>
                        </div>
                    </div>
                    <div id='ShowUnitKerja'></div>
                </div>
            </div>
            <hr>
            <div class='row'>
                <div class='col-sm-12'>
                    <div class="form-group">
                        <div class="col-md-12 col-sm-12">
                            <h4 class="title title-form">Daftar Tagihan</h4>
                        </div>
                    </div>

                    <div class='form-group'>
                        <div class="col-md-12 col-sm-12">
                            <table class='table table-striped table-bordered'>
                                <thead>
                                    <tr>
                                        <th width='5px'><center>No</center></th>
                                        <th><center>Nama Karyawan</center></th>
                                        <th><center>Jabatan</center></th>
                                    </tr>
                                </thead>
                                <tbody id='ShowDetailList'>
                                    <tr>
                                        <td colspan='3'><center>No data available in table</center></td>
                                    </tr>
                                </tbody>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>


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
            <p>
                <button onclick="Crud()" type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah</button>
            </p>
            <?php } ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="TableData">
                    <thead>
                        <tr>
                            <th width="5px"><center>No</center></th>
                            <th>Nama Cabang</th>
                            <th>Nama Paket</th>
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