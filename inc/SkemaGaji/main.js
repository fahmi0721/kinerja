$(document).ready(function(){
    Clear();
	LoadData();
	DetailDataKaryawan(6);
	$("#KodeCabang").autocomplete({
		source: "load.php?proses=getKodeCabang",
		select: function (event, ui) {
			$("#KodeCabang").val(ui.item.label);
			$("#NamaCabang").val(ui.item.NamaCabang);
			$("#IdCabang").val(ui.item.IdCabang);
			LoadDataJabatan(ui.item.IdCabang);
			LoadDataKontrak(ui.item.IdCabang);
		}
	}).autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | " + item.NamaCabang + "</div>").appendTo(ul); };
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/SkemaGaji/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "NamaCabang" },
			{ "data" : "UpahPokok" },
			{ "data" : "JumlahTK"},
			{ "data" : "Aksi", "sClass" : "text-center", "sWidth" : "10px" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}

function LoadDataKontrak(IdCabang){
	$.ajax({
		type : "POST",
		dataType : "json",
		url : "inc/SkemaGaji/proses.php?proses=GetNomorKontrak",
		chace: false,
		data : "IdCabang="+IdCabang,
		beforeSend : function(){
			StartLoad();
			console.log('Load Data Kontrak..............');
		},
		success : function(result){
			var html = "";
			console.log(result);
			if(result['Row'] > 0){
				html += "<select class='form-control' id='IdKontrak' name='IdKontrak'>";
				html += "<option value=''>..:: Pilih PKS / Addendum ::..</option>";
				for(var i=0; i < result['Item'].length; i++){
					html += "<option value='"+result['Item'][i]['IdKontrak']+"'>"+result['Item'][i]['NomorKontrak']+" - "+result['Item'][i]['JudulKontrak']+"</option>";
				}
				html += "</select>";
			}else{
				html += "<select class='form-control' id='IdKontrak' name='IdKontrak'>";
					html += "<option value=''>..:: Belum Ada Kontrak ::..</option>";
				html += "</select>";
			}
			$("#ShowKontrak").html(html);
			StopLoad();
		},
		error: function(er){
			console.log(er);
			StopLoad();
		}
	})
}

function LoadDetailKaryawan(IdCabang){
	$("#DetailData").hide();
	$("#DetailDataKaryawan").show();
	$("#BtnBack").show();
	DetailDataKaryawan(IdCabang);
}

function DetailDataKaryawan(IdCabang){
	var table = $("#TableDataDetail").DataTable({
		"ordering": false,
		
		"ajax" : "inc/SkemaGaji/proses.php?proses=DetailDataKaryawan&IdCabang="+IdCabang,
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "NamaKaryawan" },
			{ "data" : "Jabatan" },
			{ "data" : "Upah"},
			{ "data" : "Aksi", "sClass" : "text-center", "sWidth" : "10%" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		},
		"bDestroy": true,
	});
	table.ajax.reload();
}

function LoadDataJabatan(IdCabang){
	var aksi = $("#aksi").val();
	if(aksi == "insert"){
		GetUmum(IdCabang);
	}else if(aksi = "insertKhusus"){
		GetKhusus(IdCabang);
	}
}

function GetKhusus(IdCabang){
	$.ajax({
		type : "POST",
		dataType : "json",
		url: "inc/SkemaGaji/proses.php?proses=GetDataJabatabCabang",
		data : "IdCabang="+IdCabang,
		chace : false,
		beforeSend: function(){
			StartLoad();
		},
		success: function(result){
			if(result['messages'] == 'success'){
				var html = "<div class='form-group'>";		
					html += "<label class='col-sm-3 control-label'>Jabatan</label>";
					html += "<div class='col-sm-9'>";
					html += "<select class='form-control' onchange=\"GetKaryawanCabang(this.value)\" name='Jabatan' id='Jabatan'>";
					html += "<option value=''>..:: Pilih Jabatan ::..</option>";
				for(var i=1; i < result['Item'].length; i++){
					html += "<option value='"+result['Item'][i]['Jabatan']+"'>"+result['Item'][i]['NamaJabatan']+"</option>";
				}
					html += "</select>";
					html += "</div>";
				html += "</div>";
				$("#ShowUnitKerja").html(html);
				$("#ShowUnitKaryawan").html("<div class='alert alert-info' role='alert'>Silahkan Pilih Jabatan</div>");
			}else if(result['messages'] == 'notfound'){
				$("#ShowUnitKerja").html("<div class='alert alert-info' role='alert'>Belum Ada Data</div>");
			}
			StopLoad();
		},
		error : function(er){
			console.log(er);
		}
	})
}

function GetKaryawanCabang(Jabatan){
	var IdCabang = $("#IdCabang").val();
	$.ajax({
		type : "POST",
		dataType : "json",
		url: "inc/SkemaGaji/proses.php?proses=GetDataKaryawanJabatan",
		data : "IdCabang="+IdCabang+"&Jabatan="+Jabatan,
		chace : false,
		beforeSend: function(){
			StartLoad();
		},
		success: function(result){
			
			if(result['messages'] == 'success'){
				var html = "<div class='form-group'>";	
						html += "<label class='col-sm-3 control-label'>Skema Khusus</label>";
						html += "<div class='col-sm-9'>";
							html += "<div class='input-group'>";
									html += "<input type='text' name='SkemaKhususDepan' class='form-control' readonly value='"+Jabatan+"#'>";
									html += "<span class='input-group-addon'>#</span>";
									html += "<input type='text' class='form-control'  name='SkemaKhusus' id='SkemaKhusus' placeholder='Skema Khusus'>";
							html += "</div>";
						html += "</div>";	
					html += "</div>";	
					
					
					html += "<div class='form-group'>";		
					html += "<label class='col-sm-3 control-label'>Karyawan</label>";
					html += "<div class='col-sm-9'>";
					html += "<div class='row' id='Cekbox'>";
					html += "<div class='col-sm-6' style='margin-bottom:5px;'>";
						html += "<div class='input-group'>";
							html +="<span class='input-group-addon'><input type='checkbox' onclick='CheckAll()' id='ChekAll'></span>";
							html += "<input type='text' class='form-control' readonly value='Check All'>";
						html += "</div>";
					html += "</div>";
				for(var i=0; i < result['Item'].length; i++){
					html += "<div class='col-sm-6' style='margin-bottom:5px;'>";
						html += "<div class='input-group'>";
							html +="<span class='input-group-addon'><input type='checkbox' class='Cekbox' name='IdKaryawan[]' value='"+result['Item'][i]['IdKaryawan']+"'></span>";
							html += "<input type='text' class='form-control' readonly value='"+result['Item'][i]['NamaKaryawan']+"'>";
						html += "</div>";

					html += "</div>";
				}
					html += "</div>";
					html += "</div>";
				html += "</div>";
				$("#ShowUnitKaryawan").html(html);
			}else if(result['messages'] == 'notfound'){
				$("#ShowUnitKaryawan").html("<div class='alert alert-info' role='alert'>Belum Ada Data</div>");
			}
			StopLoad();
		},
		error : function(er){
			console.log(er);
		}
	})
}

function GetUmum(IdCabang){
	$.ajax({
		type : "POST",
		dataType : "json",
		url: "inc/SkemaGaji/proses.php?proses=GetDataJabatabCabang",
		data : "IdCabang="+IdCabang,
		chace : false,
		beforeSend: function(){
			StartLoad();
		},
		success: function(result){
			if(result['messages'] == 'success'){
				var html = "<div class='form-group'>";		
					html += "<label class='col-sm-3 control-label'>Jabatan</label>";
					html += "<div class='col-sm-9'>";
					
					html += "<div class='row' id='Cekbox'>";
					html += "<div class='col-sm-6' style='margin-bottom:5px;'>";
						html += "<div class='input-group'>";
							html +="<span class='input-group-addon'><input type='checkbox' onclick='CheckAll()' id='ChekAll'></span>";
							html += "<input type='text' class='form-control' readonly value='Check All'>";
						html += "</div>";
					html += "</div>";
				for(var i=0; i < result['Item'].length; i++){
					html += "<div class='col-sm-6' style='margin-bottom:5px;'>";
						html += "<div class='input-group'>";
							html +="<span class='input-group-addon'><input type='checkbox' class='Cekbox' name='Jabatan[]' value='"+result['Item'][i]['Jabatan']+"'></span>";
							html += "<input type='text' class='form-control' readonly value='"+result['Item'][i]['NamaJabatan']+"'>";
						html += "</div>";
					html += "</div>";
				}
					html += "</div>";
					html += "</div>";
				html += "</div>";
				$("#ShowUnitKerja").html(html);
			}else if(result['messages'] == 'notfound'){
				$("#ShowUnitKerja").html("<div class='alert alert-info' role='alert'>Belum Ada Data</div>");
			}
			StopLoad();
		},
		error : function(er){
			console.log(er);
		}
	})
}

function CheckAll(){
	var Id = $("#ChekAll");
	if(Id.is(":checked")){
		$(".Cekbox").prop("checked",true);
	}else{
		$(".Cekbox").prop("checked",false);
	}
}

function KalkulasiUpah(){
	TotalUangMakan();
	TotalUangTransport();
	TotalUpah();
	KalkulasiThrPesangon();
	KalkulasiJPTK();
}

function KalkulasiJPTK(){
	var JasaPjtkPersen = $("#JasaPjtkPersen").val() == "" ? 0 : parseFloat($("#JasaPjtkPersen").val().replace(",",'.'));
	var	Upah = $("#Upah").val() == "" ? 0 : parseFloat($("#Upah").val().replace(/[^\d]/g,""));
	var JasaPjtk = Upah * (JasaPjtkPersen/100);
	var res = FormatRupiah(Math.round(JasaPjtk));
	$("#JasaPjtk").val(res);
}

function KalkulasiThrPesangon(){
	var	UpahPokok = $("#UpahPokok").val() == "" ? 0 : parseFloat($("#UpahPokok").val().replace(/[^\d]/g,""));
	var Res = UpahPokok / 12;
	var res = FormatRupiah(Math.round(Res));
	$("#Thr").val(res);
	$("#Pesangon").val(res);
}

function KalkulasiPakaian(){
	var PakaianKerjaPersen = $("#PakaianKerjaPersen").val() == "" ? 0 : parseFloat($("#PakaianKerjaPersen").val().replace(",",'.'));
	var	UpahPokok = $("#UpahPokok").val() == "" ? 0 : parseFloat($("#UpahPokok").val().replace(/[^\d]/g,""));
	var PakaianKerja = (UpahPokok * (PakaianKerjaPersen/100))/12;
	var res = FormatRupiah(Math.round(PakaianKerja));
	$("#PakaianKerja").val(res);
}

function KalkulasiBpjsTk(){
	var BpjsTkPersen = $("#BpjsTkPersen").val() == "" ? 0 : parseFloat($("#BpjsTkPersen").val().replace(",",'.'));
	var	UpahPokok = $("#UpahPokok").val() == "" ? 0 : parseFloat($("#UpahPokok").val().replace(/[^\d]/g,""));
	var BpjsTk = UpahPokok * (BpjsTkPersen/100);
	var res = FormatRupiah(Math.round(BpjsTk));
	$("#BpjsTk").val(res);
}

function KalkulasiBpjsKes(){
	var BpjsKesPersen = $("#BpjsKesPersen").val() == "" ? 0 : parseFloat($("#BpjsKesPersen").val().replace(",",'.'));
	var	UpahPokok = $("#UpahPokok").val() == "" ? 0 : parseFloat($("#UpahPokok").val().replace(/[^\d]/g,""));
	var BpjsKes = UpahPokok * (BpjsKesPersen/100);
	var res = FormatRupiah(Math.round(BpjsKes));
	$("#BpjsKes").val(res);
}

function TotalUangTransport(){
	var UangTransportPersen = $("#UangTransportPersen").val() == "" ? 0 : parseFloat($("#UangTransportPersen").val().replace(",",'.'));
	var UangTransportHari = $("#UangTransportHari").val() == "" ? 0 : parseFloat($("#UangTransportHari").val().replace(/[^\d]/g,""));
	var	UpahPokok = $("#UpahPokok").val() == "" ? 0 : parseFloat($("#UpahPokok").val().replace(/[^\d]/g,""));
	var TotalUangTransport  = (UpahPokok * (UangTransportPersen/100)) * UangTransportHari;
	var res = FormatRupiah(Math.round(TotalUangTransport));
	$("#UangTransport").val(res);
}


function TotalUangMakan(){
	var UangMakanPersen = $("#UangMakanPersen").val() == "" ? 0 : parseFloat($("#UangMakanPersen").val().replace(",",'.'));
	var UangMakanHari = $("#UangMakanHari").val() == "" ? 0 : parseFloat($("#UangMakanHari").val().replace(/[^\d]/g,""));
	var	UpahPokok = $("#UpahPokok").val() == "" ? 0 : parseFloat($("#UpahPokok").val().replace(/[^\d]/g,""));
	var TotalUangMakan  = (UpahPokok * (UangMakanPersen/100)) * UangMakanHari;
	var res = FormatRupiah(Math.round(TotalUangMakan));
	$("#UangMakan").val(res);
}

function TotalUpah(){
	var	UpahPokok = $("#UpahPokok").val() == "" ? 0 : parseFloat($("#UpahPokok").val().replace(/[^\d]/g,""));
	var UangMakan = $("#UangMakan").val() == "" ? 0 : parseFloat($("#UangMakan").val().replace(/[^\d]/g,""));
	var UangTransport = $("#UangTransport").val() == "" ? 0 : parseFloat($("#UangTransport").val().replace(/[^\d]/g,""));
	var Tunjangan = $("#Tunjangan").val() == "" ? 0 : parseFloat($("#Tunjangan").val().replace(/[^\d]/g,""));
	var TotalUpah = UpahPokok + UangMakan + UangTransport + Tunjangan;
	var res = FormatRupiah(TotalUpah);
	$("#Upah").val(res);
}


function Clear(){
	$("#Title").html("Tampil Data Skema Gaji");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#DetailDataKaryawan").hide();
	$("#DataHeadCabang").show();
	$("#aksi").val("");
	$("#BtnBack").hide();
	$("#ShowUnitKaryawan").html("");
	$("#BtnBatal").attr('onclick',"Clear()");
	$("#ShowKontrak").html("<div class='alert alert-info' role='alert'>Silahkan Lengkapi Form Cabang</div>");
	$("#ShowUnitKerja").html("<div class='alert alert-info' role='alert'>Silahkan Lengkapi Form Cabang</div>");
	var iForm = ["aksi","IdSkemaGaji","KodeCabang","IdCabang","NamaCabang","UpahPokok", "UangMakanPersen","UangMakanHari", "UangMakan", "UangTransportPersen", "UangTransportHari","UangTransport","Tunjangan", "Upah", "BpjsKesPersen", "BpjsKes","BpjsTkPersen", "BpjsTk", "PakaianKerjaPersen","PakaianKerja", "Thr", "Pesangon", "Dplk","JasaPjtkPersen","JasaPjtk"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
}
function ClearDua(){
	Clear();
	$("#DetailData").hide();
	$("#DetailDataKaryawan").show();
	$("#DataHeadCabang").hide();
	$("#BtnBack").show();
}

function Crud(IdSkemaGaji,Status){
	if (IdSkemaGaji){
		if(Status == "ubah"){
			Clear();
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/SkemaGaji/proses.php?proses=ShowData",
				data : "IdSkemaGaji="+IdSkemaGaji,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					$("#DataHeadCabang").hide();
					$("#Title").html("Ubah Data Skema Gaji <b>"+data.NamaKaryawan+"</b>");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#DetailDataKaryawan").hide();
					$("#aksi").val("update");
					$("#BtnBatal").attr('onclick',"ClearDua()");
					


					$("#IdSkemaGaji").val(data.IdSkemaGaji);
					$("#UpahPokok").val(FormatRupiah(data.GajiPokok));
					$("#UangMakanPersen").val(data.UangMakanPersen.replace(".",","));
					$("#UangMakanHari").val(FormatRupiah(data.UangMakanHari));
					$("#UangMakan").val(FormatRupiah(data.UangMakan));
					$("#UangTransportPersen").val(data.UangTransportPersen.replace(".",","));
					$("#UangTransportHari").val(FormatRupiah(data.UangTransportHari));
					$("#UangTransport").val(FormatRupiah(data.UangTransportHari));
					$("#Tunjangan").val(FormatRupiah(data.Tunjangan));
					$("#Upah").val(FormatRupiah(data.Upah));
					$("#BpjsKesPersen").val(data.BpjsKesPersen.replace(".",","));
					$("#BpjsKes").val(FormatRupiah(data.BpjsKes));
					$("#BpjsTkPersen").val(data.BpjsTkPersen.replace(".",","));
					$("#BpjsTk").val(FormatRupiah(data.BpjsKes));
					$("#PakaianKerjaPersen").val(data.PakaianKerjaPersen.replace(".",","));
					$("#PakaianKerja").val(FormatRupiah(data.PakaianKerja));
					$("#Thr").val(FormatRupiah(data.Thr));
					$("#Pesangon").val(FormatRupiah(data.Pesangon));
					$("#Dplk").val(FormatRupiah(data.Dplk));
					$("#JasaPjtkPersen").val(data.JasaPjtkPersen.replace(".",","));
					$("#JasaPjtk").val(FormatRupiah(data.JasaPjtk));
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#IdSkemaGaji").val(IdSkemaGaji)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		Clear();
		$("#Title").html("Tambah Data Skema Gaji");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#KodeCabang").focus();
		$("#aksi").val("insert");
	}

}

function DetailCabangKaryawan(IdSkemaGaji){
	jQuery("#modalDetail").modal('show', {backdrop: 'static'});
	$.ajax({
		type : "POST",
		dataType : "json",
		url : "inc/SkemaGaji/proses.php?proses=DetailDataKaryawanOne",
		data : "IdSkemaGaji="+IdSkemaGaji,
		beforeSend : function(){
			StartLoad()
		},
		success : function(data){
			console.log(data)
			$("#TitleModal").html("<b>"+data['NamaKaryawan']+"</b>");
			var html = "<div class='table-responsive'>";
				html += "<table class='table table-striped'>";
					html +="<tr>";
						html += "<th>Nama</th>";
						html += "<th>:</th>";
						html += "<td>"+data['NamaKaryawan']+"</td>";
						html += "<th>Jabatan</th>";
						html += "<th>:</th>";
						html += "<td>"+data['NamaJabatan']+"</td>";
					html +="</tr>";

					html +="<tr>";
						html += "<th>Uang Makan</th>";
						html += "<th>:</th>";
						html += "<td>Rp. "+FormatRupiah(data['UangMakan'])+"</td>";
						html += "<th>Uang Transport</th>";
						html += "<th>:</th>";
						html += "<td>Rp. "+FormatRupiah(data['UangTransport'])+"</td>";
					html +="</tr>";

					html +="<tr>";
						html += "<th>Tunjangan</th>";
						html += "<th>:</th>";
						html += "<td>Rp. "+FormatRupiah(data['Tunjangan'])+"</td>";
						html += "<th>Total Upah</th>";
						html += "<th>:</th>";
						html += "<td>Rp. "+FormatRupiah(data['Upah'])+"</td>";
					html +="</tr>";

					html +="<tr>";
						html += "<th>BPJS Kesehatan</th>";
						html += "<th>:</th>";
						html += "<td>Rp. "+FormatRupiah(data['BpjsKes'])+"</td>";
						html += "<th>BPJS Ketenagakerjaan</th>";
						html += "<th>:</th>";
						html += "<td>Rp. "+FormatRupiah(data['BpjsTk'])+"</td>";
					html +="</tr>";
					
					html +="<tr>";
						html += "<th>Pakaian Pekerja & Perlengkapan</th>";
						html += "<th>:</th>";
						html += "<td>Rp. "+FormatRupiah(data['PakaianKerja'])+"</td>";
						html += "<th>THR</th>";
						html += "<th>:</th>";
						html += "<td>Rp. "+FormatRupiah(data['Thr'])+"</th>";
					html +="</tr>";

					html +="<tr>";
						html += "<th>Pesangon</th>";
						html += "<th>:</th>";
						html += "<td>Rp. "+FormatRupiah(data['Pesangon'])+"</td>";
						html += "<th>DPLK</th>";
						html += "<th>:</th>";
						html += "<td>Rp. "+FormatRupiah(data['Dplk'])+"</td>";
					html +="</tr>";

					html +="<tr>";
						html += "<th>Jasa Paket Tenaga Kerja</th>";
						html += "<th>:</th>";
						html += "<td>Rp. "+FormatRupiah(data['JasaPjtk'])+"</td>";
						html += "<th colspan='3'></th>";
					html +="</tr>";
				html += "</table>";
			html += "</div>";
			$("#DetailModal").html(html);
			StopLoad();
		},
		error : function(er){
			console.log(er);
		}
	});
}

function CrudKhusus(){
	Clear();
	$("#Title").html("Tambah Data Skema Gaji Khusus");
	$("#FormData").show();
	$("#DetailData").hide();
	$("#KodeCabang").focus();
	
	$("#aksi").val("insertKhusus");

}

function Validasi() {
	var aksi = $("#aksi").val();
	if(aksi != "update"){
		var iForm = ["KodeCabang","IdCabang","NamaCabang","UpahPokok", "UangMakanPersen","UangMakanHari", "UangMakan", "UangTransportPersen", "UangTransportHari","UangTransport", "Upah", "BpjsKesPersen", "BpjsKes","PakaianKerjaPersen", "BpjsTk", "PakaianKerjaPersen","PakaianKerja", "Thr", "Pesangon","JasaPjtkPersen","JasaPjtk"];
	}else{
		iForm = ["UpahPokok", "UangMakanPersen","UangMakanHari", "UangMakan", "UangTransportPersen", "UangTransportHari","UangTransport", "Upah", "BpjsKesPersen", "BpjsKes","PakaianKerjaPersen", "BpjsTk", "PakaianKerjaPersen","PakaianKerja", "Thr", "Pesangon","JasaPjtkPersen","JasaPjtk"];
	}
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Skema Gaji", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); $('html, body').animate({
				scrollTop: $("#proses").offset().top
			}, 2000); return false; }
		}
	}
	if(aksi != "update" && aksi != "delete"){
		var total=0;
			$("#Cekbox input[type='checkbox']:checked").each(function(){
				//Update total
				total += parseInt($(this).data("exval"),10);
		});
		if(total <= 0){
				error("Skema Gaji", KodeError, "Unit Kerja Belum Dipilih!"); 
				$('html, body').animate({
					scrollTop: $("#proses").offset().top
				}, 2000);
				return false; 
		}
	}
}

function ValidasiKhusus() {
	var aksi = $("#aksi").val();
	var iForm = ["KodeCabang","IdCabang","NamaCabang", "Jabatan", "SkemaKhusus", "UpahPokok", "UangMakanPersen","UangMakanHari", "UangMakan", "UangTransportPersen", "UangTransportHari","UangTransport", "Upah", "BpjsKesPersen", "BpjsKes","BpjsTkPersen", "BpjsTk", "PakaianKerjaPersen","PakaianKerja", "Thr", "Pesangon","JasaPjtkPersen","JasaPjtk"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Skema Gaji", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); $('html, body').animate({
				scrollTop: $("#proses").offset().top
			}, 2000); return false; }
		}
	}
	var total=0;
	$("#Cekbox input[type='checkbox']:checked").each(function(){
		//Update total
		 total += parseInt($(this).data("exval"),10);
   });
   if(total <= 0){
		error("Skema Gaji", KodeError, "Karyawan Belum Dipilih!"); 
		$('html, body').animate({
			scrollTop: $("#proses").offset().top
		}, 2000);
		return false; 
   }
}



function SubmitData(){
	var aksi = $("#aksi").val();
	var vals = false;
	if(aksi == "insert" || aksi == "update" || aksi == "delete"){
		vals = Validasi();
	}else{
		vals = ValidasiKhusus();
	}
	if(vals != false){
		var data = $("#FormData").serialize();
		$.ajax({
			type : "POST",
			url : "inc/SkemaGaji/proses.php?proses=Crud",
			data : data,
			beforeSend: function() {
				StartLoad();
			},
			success: function(result){
				console.log(result);
				aksi = aksi == "insertKhusus" ? "insert" : aksi;
				var Table = $("#TableData").DataTable();
				var Table1 = $("#TableDataDetail").DataTable();
				if(result == "sukses"){
					if(aksi == "update" || aksi != "delete"){
						ClearDua();
						Table1.ajax.reload();
					}else{
						Clear();
						Table.ajax.reload();
					}
					sukses(aksi,"Skema Gaji",'003');
					
					StopLoad();
				}
			},
			error : function(er){
				console.log(er);
			}
		});
	}
	

}