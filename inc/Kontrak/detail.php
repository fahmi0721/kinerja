
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title" id="Title">Title</h3>
    </div>
    
    <div class="box-body">
        <div class="col-sm-12"><div class="row"><div id="proses"></div></div></div>
        <form id="FormData" class="form-horizontal" action="#" enctype='multipart/form-data'>
            <input type="hidden" name="aksi" id="aksi" value="insert">
            <input type="hidden" name="IdKontrak" id="IdKontrak" value="">
            <input type="hidden" name="IdKontrakInduk" id="IdKontrakInduk" value="">
            <input type="hidden" name="TmpFile" id="TmpFile" value="">
            <div class="row"><div class='col-sm-8'><h4 class='title title-form'>DATA KONTRAK</h4></div></div>
            <div class="form-group">
                <label class="control-label col-sm-2">Kode/Nama Cabang <span class='text-red'>*</span></label>
                <div class="col-sm-6">
                    <div class='input-group'>
                        <span class='input-group-addon'><i class='fa fa-key'></i></span>
                        <input type='hidden' class='form-control' name='IdCabang'  id='IdCabang' />
                        <input type='text' class='form-control'  id='KodeCabang' placeholder='Enter Kode Cabang' />
                        <span class='input-group-addon'><i class='fa fa-archive'></i></span>
                        <input type='text' class='form-control' readonly id='NamaCabang' placeholder='Enter Nama Cabang' />

                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Jenis Kontrak</label>
                <div class="col-sm-6">
                    <div class='input-group'>
                        <span class='input-group-addon'><input type='radio' class='DetailData jns' name='JenisKontrak' onclick='CekJenis(this.value)' value='0' id='JenisKontrak0' checked /></span>
                        <input type='text' class='form-control'  value='Induk' disabled />
                        <span class='input-group-addon'><input type='radio' class='DetailData jns' name='JenisKontrak' onclick='CekJenis(this.value)' value='1' id='JenisKontrak1'  /></span>
                        <input type='text' class='form-control'  value='Adendum' disabled />
                    </div>
                </div>
			</div>
			
			<div id='ShowFilterJenis'></div>

            <div class="form-group">
                <label class="control-label col-sm-2">Judul Kontrak <span class='text-red'>*</span></label>
                <div class="col-sm-6">
                   <input type='text' class='form-control DetailData'  id='JudulKontrak' class='form-control' name='JudulKontrak' placeholder='Enter Judul Kontrak' />
                </div>
            </div>  

            <div class="form-group">
                <label class="control-label col-sm-2">Nomor Kontrak <span class='text-red'>*</span></label>
                <div class="col-sm-6">
                   <input type='text' class='form-control DetailData' id='NomorKontrak' class='form-control' name='NomorKontrak' placeholder='Enter Nomor Kontrak' />
                </div>
            </div>
            

            <div class="form-group">
                <label class="control-label col-sm-2">Berlaku <span class='text-red'>*</span></label>
                <div class="col-sm-6">
                    <div class='input-group'>
                        <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                        <input type='text' class='form-control Detail' name='BerlakuMulai' onchange="CalculateMounth()" id='BerlakuMulai' placeholder='Dari' />
                        <span class='input-group-addon'><i class='fa fa-calendar'></i></span>
                        <input type='text' class='form-control Detail' name='BerlakuSampai' onchange="CalculateMounth()" id='BerlakuSampai' placeholder='Sampai' />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">Keterangan</label>
                <div class="col-sm-6">
                   <textarea class='form-control DetailData' id='Keterangan' class='form-control' name='Keterangan' placeholder='Keterangan' rows='5'></textarea>
                </div>
            </div>  

            <div class="form-group">
                <label class="control-label col-sm-2">File Kontrak</label>
                <div class="col-sm-6">
                   <input type='file' accept='.pdf' class='form-control DetailData' id='FileKontrak' class='form-control' name='FileKontrak'  />
                </div>
            </div>
            <hr>
            <div class='row'>
            	<div class='col-sm-6'>

            		<div class="row"><div class='col-sm-12'><h4 class='title title-form'>DATA UPAH</h4></div></div>

            		<div class="form-group">
		                <label class="control-label col-sm-3"> <span class='text-red'>*</span>Upah Pokok</label>
		                <div class="col-sm-9">
		                   <div class='input-group'>
		                   		<span class='input-group-addon'>Rp. </span>
		                   		<input type="text" class='form-control Detail Addendum' onkeyup='AngkaRupiah(this);;KalkulasiUpah()' placeholder="Upah Pokok" name='UpahPokok' id='UpahPokok'>
		                   </div>
		                </div>
		            </div>

		            <div class="form-group">
		                <label class="control-label col-sm-3">Upah Makan Parameter <span class='text-red'>*</span></label>
		                <div class="col-sm-9">
		                   <div class='input-group'>
		                   		<input type="text" value='0,50' class='form-control Detail Addendum' onkeyup='decimal(this); KalkulasiUpah();' placeholder="Persentase" name='UangMakanPersen' id='UangMakanPersen'>
		                   		<span class='input-group-addon'>%</span>
		                   		<input type="text" class='form-control Detail Addendum' onkeyup='AngkaRupiah(this); KalkulasiUpah();' placeholder="Jumnlah Hari" name='UangMakanHari' id='UangMakanHari'>
		                   		<span class='input-group-addon'>Hari</span>
		                   </div>
		                </div>
		            </div>

		             <div class="form-group">
		                <label class="control-label col-sm-3">Uang Makan </label>
		                <div class="col-sm-9">
		                   <div class='input-group'>
		                   		<span class='input-group-addon'>Rp. </span>
		                   		<input type="text" readonly class='form-control' placeholder="Uang Makan" name='UangMakan' id='UangMakan'>
		                   </div>
		                </div>
		            </div>

		            <div class="form-group">
		                <label class="control-label col-sm-3">Uang Transport Parameter <span class='text-red'>*</span></label>
		                <div class="col-sm-9">
		                   <div class='input-group'>
		                   		<input type="text" value='0,25' class='form-control Detail Addendum' onkeyup='decimal(this); KalkulasiUpah();' placeholder="Persentase" name='UangTransportPersen' id='UangTransportPersen'>
		                   		<span class='input-group-addon'>%</span>
		                   		<input type="text" class='form-control Detail Addendum' onkeyup='AngkaRupiah(this); KalkulasiUpah();' placeholder="Jumnlah Hari" name='UangTransportHari' id='UangTransportHari'>
		                   		<span class='input-group-addon'>Hari</span>
		                   </div>
		                </div>
		            </div>

		            <div class="form-group">
		                <label class="control-label col-sm-3">Uang Transport</label>
		                <div class="col-sm-9">
		                   <div class='input-group'>
		                   		<span class='input-group-addon'>Rp. </span>
		                   		<input type="text" readonly class='form-control' placeholder="Uang Transport" name='UangTransport' id='UangTransport'>
		                   </div>
		                </div>
		            </div>
		            <div class="form-group">
		                <label class="control-label col-sm-3">Tunjangan</label>
		                <div class="col-sm-9">
		                   <div class='input-group'>
		                   		<span class='input-group-addon'>Rp. </span>
		                   		<input type="text" onkeyup='AngkaRupiah(this); KalkulasiUpah();' class='form-control Detail' placeholder="Tunjangan" name='Tunjangan' id='Tunjangan'>
		                   </div>
		                </div>
		            </div>
		            <div class="form-group">
		                <label class="control-label col-sm-3">Total Upah</label>
		                <div class="col-sm-9">
		                   <div class='input-group'>
		                   		<span class='input-group-addon'>Rp. </span>
		                   		<input type="text" readonly class='form-control' placeholder="Total Upah" name='TotalUpah' id='TotalUpah'>
		                   </div>
		                </div>
		            </div>

		            <div class="row"><div class='col-sm-12'><h4 class='title title-form'>DATA ITEM KONTRAK</h4></div></div>
            		<div class="form-group">
		                <label class="control-label col-sm-3">Nama Item Kontrak <span class='text-red'>*</span></label>
		                <div class="col-sm-9">
		                   	<input type="text" class='form-control Detail' placeholder="Nama Item Kontrak" name='NamaList' id='NamaList'>
		                </div>
		            </div>

		            <div class="form-group">
		                <label class="control-label col-sm-3">Jumlah Tenaga Kerja <span class='text-red'>*</span></label>
		                <div class="col-sm-9">
		                   	<div class='input-group'>
		                   		<input type="text" value='1' onkeyup='AngkaRupiah(this); KalkulasiUpah();' class='form-control Detail' placeholder="Jumlah Tenaga Kerja" name='Jumlah' id='Jumlah'>
		                   		<span class='input-group-addon'>Tenaga Kerja</span>
		                   </div>
		                </div>
					</div>
					
					<div class="form-group">
		                <label class="control-label col-sm-3">Tagihan / Pekerja</label>
		                <div class="col-sm-9">
		                   	<div class='input-group'>
		                   		<span class='input-group-addon'>Rp. </span>
		                   		<input type="text" class='form-control' readonly placeholder="Tagihan / Pekerja" name='Tagihan' id='Tagihan'>
		                   </div>
		                </div>
					</div>

					<div class="form-group">
		                <label class="control-label col-sm-3">Lama Kontrak</label>
		                <div class="col-sm-9">
		                   	<div class='input-group'>
		                   		<input type="text" class='form-control' readonly placeholder="Lama Kontrak" name='LamaKontrak' id='LamaKontrak'>
		                   		<span class='input-group-addon'>Bulan</span>
		                   </div>
		                </div>
		            </div>
					
					<div class="form-group">
		                <label class="control-label col-sm-3">Total Tagihan</label>
		                <div class="col-sm-9">
		                   	<div class='input-group'>
		                   		<span class='input-group-addon'>Rp. </span>
		                   		<input type="text" class='form-control' readonly placeholder="Total Tagihan" name='TotalTagihan' id='TotalTagihan'>
		                   </div>
		                </div>
		            </div>
            	</div>
            	<div class='col-sm-6'>
            		<div class="row"><div class='col-sm-12'><h4 class='title title-form'>DATA BPJS</h4></div></div>
            		<div class="form-group">
		                <label class="control-label col-sm-3">BPJS Kesehatan <span class='text-red'>*</span></label>
		                <div class="col-sm-9">
		                   <div class='input-group'>
		                   		<span class='input-group-addon'>%</span>
		                   		<input type="text" value='4' class='form-control Detail Addendum' onkeyup='decimal(this); KalkulasiUpah();' placeholder="Persentase" name='BpjsKesPersen' id='BpjsKesPersen'>
		                   		<span class='input-group-addon'>Rp. </span>
		                   		<input type="text" readonly class='form-control' placeholder="BPJS Kesehatan" name='BpjsKes' id='BpjsKes'>
		                   </div>
		                </div>
		            </div>
		            <hr>
		            <div class="form-group">
		                <label class="control-label col-sm-3">BPJS Ketenagakerjaan </label>
		                <div class="col-sm-9">
		                   <div class='input-group'>
		                   		<span class='input-group-addon'>%</span>
		                   		<input type="text" readonly class='form-control Detail Addendum' placeholder="Persentase" name='BpjsTkPersen' id='BpjsTkPersen'>
		                   		<span class='input-group-addon'>Rp. </span>
		                   		<input type="text" readonly class='form-control' placeholder="BPJS Ketenagakerjaan" name='BpjsTk' id='BpjsTk'>
		                   </div>
		                </div>
		            </div>
		            <div class="form-group">
		                <label class="control-label col-sm-3">Jaminan Kecelakaan Kerja <span class='text-red'>*</span></label>
		                <div class="col-sm-9">
		                   <div class='input-group'>
		                   		<span class='input-group-addon'>%</span>
		                   		<input type="text" value='0,54'  class='form-control Detail Addendum' onkeyup='decimal(this); KalkulasiUpah();' placeholder="Persentase" name='JaminanKecelakaanKerjaPersen' id='JaminanKecelakaanKerjaPersen'>
		                   		<span class='input-group-addon'>Rp. </span>
		                   		<input type="text" readonly class='form-control' placeholder="Jaminan Kecelakaan Kerja" name='JaminanKecelakaanKerja' id='JaminanKecelakaanKerja'>
		                   </div>
		                </div>
		            </div>

		            <div class="form-group">
		                <label class="control-label col-sm-3">Jaminan Kematian <span class='text-red'>*</span></label>
		                <div class="col-sm-9">
		                   <div class='input-group'>
		                   		<span class='input-group-addon'>%</span>
		                   		<input type="text" value='0,30'  class='form-control Detail Addendum' onkeyup='decimal(this); KalkulasiUpah();' placeholder="Persentase" name='JaminanKematianPersen' id='JaminanKematianPersen'>
		                   		<span class='input-group-addon'>Rp. </span>
		                   		<input type="text" readonly class='form-control' placeholder="Jaminan Kematian" name='JaminanKematian' id='JaminanKematian'>
		                   </div>
		                </div>
		            </div>
		            <div class="form-group">
		                <label class="control-label col-sm-3">Jaminan Hari Tua <span class='text-red'>*</span></label>
		                <div class="col-sm-9">
		                   <div class='input-group'>
		                   		<span class='input-group-addon'>%</span>
		                   		<input type="text" value='3,70' class='form-control Detail Addendum' onkeyup='decimal(this); KalkulasiUpah();' placeholder="Persentase" name='JaminanHariTuaPersen' id='JaminanHariTuaPersen'>
		                   		<span class='input-group-addon'>Rp. </span>
		                   		<input type="text" readonly class='form-control'  placeholder="Jaminan Hari Tua" name='JaminanHariTua' id='JaminanHariTua'>
		                   </div>
		                </div>
		            </div>
		            <div class="form-group">
		                <label class="control-label col-sm-3">Jaminan Pensiun <span class='text-red'>*</span></label>
		                <div class="col-sm-9">
		                   <div class='input-group'>
		                   		<span class='input-group-addon'>%</span>
		                   		<input type="text" value='2' class='form-control Detail Addendum' onkeyup='decimal(this); KalkulasiUpah();' placeholder="Persentase" name='JaminanPensiunPersen' id='JaminanPensiunPersen'>
		                   		<span class='input-group-addon'>Rp. </span>
		                   		<input type="text" readonly class='form-control' placeholder="Jaminan Pensiun" name='JaminanPensiun' id='JaminanPensiun'>
		                   </div>
		                </div>
		            </div>
		            <div class="row"><div class='col-sm-12'><h4 class='title title-form'>DATA LAIN - LAIN</h4></div></div>
		            <div class="form-group">
		                <label class="control-label col-sm-3">Pakaain Kerja & Perlengkapan <span class='text-red'>*</span></label>
		                <div class="col-sm-9">
		                   <div class='input-group'>
		                   		<span class='input-group-addon'>%</span>
		                   		<input type="text" value='60' class='form-control Detail Addendum' onkeyup='decimal(this); KalkulasiUpah();' placeholder="Persentase" name='PakaianKerjaPersen' id='PakaianKerjaPersen'>
		                   		<span class='input-group-addon'>Rp. </span>
		                   		<input type="text" readonly class='form-control' placeholder="Pakaian Kerja" name='PakaianKerja' id='PakaianKerja'>
		                   </div>
		                </div>
		            </div>
		            <div class="form-group">
		                <label class="control-label col-sm-3">THR</label>
		                <div class="col-sm-9">
		                   <div class='input-group'>
		                   		<span class='input-group-addon'>Rp. </span>
		                   		<input readonly type="text" onkeyup='decimal(this); KalkulasiUpah();' class='form-control' placeholder="THR" name='Thr' id='Thr'>
		                   </div>
		                </div>
		            </div>
		            <div class="form-group">
		                <label class="control-label col-sm-3">Pesangon</label>
		                <div class="col-sm-9">
		                   <div class='input-group'>
		                   		<span class='input-group-addon'>Rp. </span>
		                   		<input readonly type="text" onkeyup='decimal(this); KalkulasiUpah();'  class='form-control' placeholder="Pesangon" name='Pesangon' id='Pesangon'>
		                   </div>
		                </div>
		            </div>
		            <div class="form-group">
		                <label class="control-label col-sm-3">DPLK</label>
		                <div class="col-sm-9">
		                   <div class='input-group'>
		                   		<span class='input-group-addon'>Rp. </span>
		                   		<input  type="text" onkeyup='AngkaRupiah(this); KalkulasiUpah();' class='form-control Detail Addendum' placeholder="DPLK" name='Dplk' id='Dplk'>
		                   </div>
		                </div>
		            </div>
		            <div class="form-group">
		                <label class="control-label col-sm-3">Jasa Paket Tenaga Kerja <span class='text-red'>*</span></label>
		                <div class="col-sm-9">
		                   <div class='input-group'>
		                   		<span class='input-group-addon'>%</span>
		                   		<input type="text" value='10' class='form-control Detail Addendum'  onkeyup='decimal(this); KalkulasiUpah();' placeholder="Persentase" name='JasaPjtkPersen' id='JasaPjtkPersen'>
		                   		<span class='input-group-addon'>Rp. </span>
		                   		<input type="text" readonly class='form-control' placeholder="Pakaian Kerja" name='JasaPJTK' id='JasaPjtk'>
		                   </div>
		                </div>
		            </div>
            	</div>
            </div>
            <div class="row">
            	<div class='col-sm-12'>
            		<div class="form-group">
		                <div class="col-sm-12">
		                   <center>
							   <button class="btn btn-primary btn-lg btn-c btn-k" onclick='TambahList()' id='BtnCek' data-cek='0' type='button'><i class='fa fa-plus'></i> Tambah</button>
							   <button class="btn btn-danger btn-lg btn-k" onclick='CelarListKontrak()' type='button'><i class='fa fa-mail-reply'></i> Reset</button>
							</center>
		                </div>
		            </div>
            	</div>

            	<div class='col-sm-12'>
            		<hr>
            		<table class='table table-striped table-bordered'>
            			<thead>
            				<tr>
            					<th width="5%"><center>No</center></th>
            					<th>Nama Item Kontrak</th>
            					<th>Lama Kontrak</th>
            					<th>Jumlah Tenaga Kerja</th>
            					<th>Tagihan / Pekerja</th>
            					<th>Total Tagihan Selama <span id='Selama'></span></th>
            					<th><center>Aksi</center></th>
            				</tr>
						</thead>
						<tbody id='ShoItemKontrak'></tbody>
            		</table>
            	</div>

            </div>


            
            <div class="form-group">
                <div class="col-sm-12">
                	<center>
	                    <button type="button" onclick="SubmitData()" class="btn btn-lg  btn-primary btn-k"><i class="fa fa-check-square"></i> Submit</button>
	                    <button type="button" onclick="Clear()" class="btn btn-lg  btn-danger"><i class="fa fa-mail-reply" ></i> Kembali</button>
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
                            <th>Judul Kontrak</th>
                            <th>Nomor Kontrak</th>
                            <th>Berlaku</th>
                            <th>Total Tagihan</th>
                            <th><center>Addendum</center></th>
                            <th>File</th>
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

<div class='modal fade in' id='modalDetail' data-keyboard="false" data-backdrop="static" tabindex='0' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
<div class='modal-dialog'>
<div class='modal-content modal-lg'>
<div class="modal-header">
    <button type="button" class="close" id="closemodal" data-dismiss="modal">&times;</button>
    <h5 class="modal-title">Detail Addendum</h5>
</div>
<div class='modal-body'>

	<table class="table table-bordered table-striped" id="TableData">
		<thead>
			<tr>
				<th width="5px"><center>No</center></th>
				<th>Judul Kontrak</th>
				<th>Nomor Kontrak</th>
				<th>Berlaku</th>
				<th>Keterangan</th>
				<th>File</th>
				<th width="15%"><center>Aksi</center></th>
			</tr>
		</thead>
		<tbody id='resultAddendum'></tbody>
	</table>
    
</div>
</div>
</div>
</div>