$(document).ready(function(){

    Clear();
	LoadData();
	
	var dataBank = ['BNI','BRI','MANDIRI','BTN'];
	
	$("#UploadKodeCabang").autocomplete({
		source: "load.php?proses=getKodeCabang",
		select: function (event, ui) {
			$("#UploadKodeCabang").val(ui.item.label);
			$("#UploadNamaCabang").val(ui.item.NamaCabang);
			$("#UploadIdCabang").val(ui.item.IdCabang);
		}
	})
	.autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | " + item.NamaCabang + "</div>").appendTo(ul); };

	$("#KodeCabang").autocomplete({
		source: "load.php?proses=getKodeCabang",
		select: function (event, ui) {
			$("#KodeCabang").val(ui.item.label);
			$("#NamaCabang").val(ui.item.NamaCabang);
			$("#IdCabang").val(ui.item.IdCabang);
		}
	})
		.autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | " + item.NamaCabang + "</div>").appendTo(ul); };
	
	$("#Bank").autocomplete({
		source: dataBank
	});
});



function LoadData(){
	var Filter = $("#Filter").val();
	$("#TableData_filter").find('input').val(Filter);
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/Karyawan/proses.php?proses=DetailData&Filter="+Filter,
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "NIK" },
			{ "data" : "NamaKaryawan" },
			{ "data" : "JK" },
			{ "data" : "TTL" },
			{ "data": "Cabang" },
			{ "data": "UnitTugas" },
			{ "data": "Jabatan" },
			{ "data": "TMTISMA" },
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}




function Clear(){
	$("#Title").html("Tampil Data Karyawan");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#FormUpload").hide();
	$("#DetailData").show();
	$("#BtnGetNik").attr("onclick", "GetNIK()");
	$("#BtnGetNik").prop("disabled",false);
	$("#aksi").val("");
	var iForm = ["aksi", "IdKaryawan", "IdCabang", "KodeCabang", "NamaCabang", "TptLahir", "TglLahir", "NIK", "NoKtp", "NamaKaryawan", "NoHp", "UnitTugas", "Jabatan", "BpjsKes", "BpjsTk", "TMTCabang", "TMTIsu", "Agama", "UkuranBaju", "UkuranSepatu","Alamat"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
	var iForm = ['UploadIdCabang', 'UploadAksi', 'UploadKodeCabang', 'File'];
	for (var i = 0; i < iForm.length; i++) {
		$("#" + iForm[i]).val("");
	}

	$("#RekUtama").html("<div class='col-md-9' id='MessageRekUtama'></div>");
	$("#PendidikanTerakhirBox").html("<div class='col-md-9' id='MessagePendidikanTerakhir'></div>");
	ClearTambahRekening();
	MessageInfo("Silahkan Tambah No Rekening Terlebi Dahulu Untuk Memiih No Rekening Utama", "MessageRekUtama");
	ClearTambahPendidikan();
	MessageInfo("Silahkan Tambah Pendidikan Terlebi Dahulu Untuk Memiih Pendidikan Terakhir","MessagePendidikanTerakhir");
	$("#ShowPendidikan").html("");
	$("#ShowRekening").html("");
	$("#FormaPendidikan").html("");
	$("#FormaRekening").html("");
	$(".itemRekening").remove();
	$(".itemPendidikan").remove();
	$("#DataTambahan").show();
}


/*
 *	NIK 
 */

function GetNIK(){
	var iForm = ['TptLahir','TglLahir'];
	KodeError = 1;
	for(var i=0; i < iForm.length; i++){
		if ($("#" + iForm[i]).val() == "") { error("Karyawan", KodeError + i, iForm[i] + " Belum Lengkap!"); $("#" + iForm[i]).focus(); return false;  }
	}
	var TglLahir = $("#TglLahir").val();
	TglLahir = TglLahir.split("-");
	var bulan = TglLahir[1];
	var Tahun = TglLahir[0].substr(2,2);
	var StatusKaryawan = $("#StatusKaryawan2").is(':checked') ? 2 : 1;
	$.ajax({
		type : "GET",
		url : "inc/Karyawan/proses.php",
		data: "proses=GetNewNIK",
		beforeSend : function(){
			StartLoad();
		},
		success: function(nik){
			var NIK = StatusKaryawan+TglLahir[1]+Tahun+nik;
			$("#NIK").val(NIK);
			StopLoad();
		},
		error: function(er){
			console.log(er);
		}

	});
}

$("#StatusKaryawan2").click(function(){
	$('#NIK').val("");
});

$("#TglLahir").change(function(){
	$('#NIK').val("");
});

$("#StatusKaryawan1").click(function () {
	$('#NIK').val("");
});

/**
 * Modul Tambah Nomor Rekening
 */
function ClearTambahRekening(){
	$("#Bank").val("");
	$("#Rekening").val("");
	$("#PesanRekening").html("");
	$("#Bank").focus();
	
}

function TamabahRekening(){
	jumData = $(".itemRekening").length;
	if (jumData <= 0) {
		$("#RekUtama").html("<div class='col-md-9'><select class='form-control' name='RekeningUtama' id='RekeningUtama'><option value=''>..:: Pilih No Rekening Utama ::..</option></select></div>");
	} 
	var posisi = $("#BtnTambahRekening").attr('data-id');
	var Bank = $("#Bank").val();
	var Rekening = $("#Rekening").val();
	if(Bank == ""){
		Customerror('No Rekening', '001', "Nama Bank Belum Lengkap", "PesanRekening"); $("#Bank").focus(); return false;
	}
	if (Rekening == "") {
		Customerror('No Rekening', '002', "No Rekening Belum Lengkap", "PesanRekening"); $("#Rekening").focus(); return false;
	}
	$("#ShowRekening").append(
		"<tr class='itemRekening itemRekening"+posisi+"'>"
			+"<td><center><button onclick=\"HapusRekening('"+posisi+"')\" class='btn btn-xs btn-danger'><i class='fa fa-trash-o'></i></button></center></td>"
			+ "<td>" + Bank + "</td>"
			+ "<td>" + Rekening + "</td>"
		+"</tr>"
	);
	$("#FormRekening").append(
		"<p class='itemRekening itemRekening" + posisi +"'>"
			+ "<input type='hidden' name='NamaBank[]' value='" + Bank + "'>" 
			+ "<input type='hidden' name='Norekening[]' value='" + Rekening + "'>"
		+"</p>"
	);
	$("#RekeningUtama").append("<option class='itemRekening"+posisi+"' value='"+Bank+"#"+Rekening+"'>"+Bank+" - "+Rekening+"</option>");
	$("#BtnTambahRekening").attr('data-id',parseInt(posisi) + 1);
	ClearTambahRekening();
}

function HapusRekening(posisi){
	$(".itemRekening"+posisi).remove();
	var jumData = $("#itemRekening").length;
	if (jumData <= 0) {
		$("#RekUtama").html("<div class='col-md-9' id='MessageRekUtama'></div>");
		MessageInfo("Silahkan Tambah No Rekening Terlebi Dahulu Untuk Memiih No Rekening Utama", "MessageRekUtama");
	} 
}

/*
 * END MODUL TAMBAH REKENING
 */


/**
 * Modul Tambah Pendidikan
 */
function ClearTambahPendidikan() {
	$("#TingkatPendidikan").val("");
	$("#Jurusan").val("");
	$("#Tahun").val("");
	$("#PesanPendidikan").html("");
	$("#TingkatPendidikan").focus();

}

function TamabahPendidikan() {
	jumData = $(".itemPendidikan").length;
	if (jumData <= 0) {
		$("#PendidikanTerakhirBox").html("<div class='col-md-9'><select class='form-control' name='PendidikanTerakhir' id='PendidikanTerakhir'><option value=''>..:: Pilih Pendidikan Terakhir ::..</option></select></div>");
	}
	var posisi = $("#BtnTambahPendidikan").attr('data-id');
	var TingkatPendidikan1 = $("#TingkatPendidikan").val();
	var TingkatPendidikan = TingkatPendidikan1.split("#");
	var Jurusan = $("#Jurusan").val();
	var Tahun = $("#Tahun").val();
	if (TingkatPendidikan1 == "") {
		Customerror('Pendidikan', '001', "Tingkat Pendidikan Belum Dipilih", "PesanPendidikan"); $("#TingkatPendidikan").focus(); return false;
	}
	if (Jurusan == "") {
		Customerror('Pendidikan', '002', "Jurusan Belum Lengkap", "PesanPendidikan"); $("#Jurusan").focus(); return false;
	}
	if (Tahun == "") {
		Customerror('Pendidikan', '002', "Tahun Belum Lengkap", "PesanPendidikan"); $("#Tahun").focus(); return false;
	}
	$("#ShowPendidikan").append(
		"<tr class='itemPendidikan itemPendidikan" + posisi + "'>"
		+ "<td><center><button onclick=\"HapusPendidikan('" + posisi + "')\" class='btn btn-xs btn-danger'><i class='fa fa-trash-o'></i></button></center></td>"
		+ "<td>" + TingkatPendidikan[1] + "</td>"
		+ "<td>" + Jurusan + "</td>"
		+ "<td>" + Tahun + "</td>"
		+ "</tr>"
	);
	$("#FormPendidikan").append(
		"<p class='itemPendidikan itemPendidikan" + posisi + "'>"
		+ "<input type='hidden' name='Tingkat[]' value='" + TingkatPendidikan[0] + "'>"
		+ "<input type='hidden' name='Jurusan[]' value='" + Jurusan + "'>"
		+ "<input type='hidden' name='Tahun[]' value='" + Tahun + "'>"
		+ "</p>"
	);
	$("#PendidikanTerakhir").append("<option class='itemPendidikan" + posisi + "' value='" + TingkatPendidikan[1] + "#" + Jurusan + "'>" + TingkatPendidikan[1] + " - " + Jurusan + "</option>");
	$("#BtnTambahPendidikan").attr('data-id', parseInt(posisi) + 1);
	ClearTambahPendidikan();
}

function HapusPendidikan(posisi) {
	$(".itemPendidikan" + posisi).remove();
	var jumData = $("#itemPendidikan").length;
	if (jumData <= 0) {
		$("#PendidikanTerakhirBox").html("<div class='col-md-9' id='MessagePendidikanTerakhir'></div>");
		MessageInfo("Silahkan Tambah Pendidikan Terlebi Dahulu Untuk Memiih Pendidikan Terakhir", "MessagePendidikanTerakhir");
	}
}

/*
 * END MODUL TAMBAH PENDIDIKAN
 */


/**
* Isi Data
*/

function LoadBank(IdKaryawan,RekeningUtama){
	$.ajax({
		type : "POST",
		dataType : "json",
		url : "inc/Karyawan/proses.php?proses=LoadDataBank",
		data : "IdKaryawan="+IdKaryawan,
		beforeSend : function(){
			StartLoad();
			console.log('Load data rekning.....');
		},
		success : function(data){
			if (!data.msg){
				$("#RekUtama").html("<div class='col-md-9'><select class='form-control' name='RekeningUtama' id='RekeningUtama'><option value=''>..:: Pilih No Rekening Utama ::..</option></select></div>");
				for(var i=0; i < data.length; i++){
					var ExpRek = data[i].split("#");
					var NamaBank = ExpRek[0];
					var Norekening = ExpRek[1];
					$("#RekeningUtama").append("<option value='" + NamaBank + "#" + Norekening + "'>" + NamaBank + " - " + Norekening + "</option>");
				}
				$("#RekeningUtama").val(RekeningUtama);
			}else{
				console.log(data);
			}
		},
		error: function(er){
			console.log(er);
		}
		
	})
}


function LoadPendidikan(IdKaryawan, PendidikanTerakhir) {
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "inc/Karyawan/proses.php?proses=LoadPendidikan",
		data: "IdKaryawan=" + IdKaryawan,
		beforeSend: function () {
			StartLoad();
			console.log('Load data pendidikan.....');
		},
		success: function (data) {
			if (!data.msg) {
				$("#PendidikanTerakhirBox").html("<div class='col-md-9'><select class='form-control' name='PendidikanTerakhir' id='PendidikanTerakhir'><option value=''>..:: Pilih No Rekening Utama ::..</option></select></div>");
				for (var i = 0; i < data.length; i++) {
					var ExpPen = data[i].split("#");
					var Tingkatan = ExpPen[0];
					var Jurusan = ExpPen[1];
					$("#PendidikanTerakhir").append("<option  value='" + Tingkatan + "#" + Jurusan + "'>" + Tingkatan + " - " + Jurusan + "</option>");
				}
				$("#PendidikanTerakhir").val(PendidikanTerakhir);
			} else {
				console.log(data);
			}
		},
		error: function (er) {
			console.log(er);
		}

	})
}


function IsiData(data){
	$("#IdKaryawan").val(data.IdKaryawan);
	$("#IdCabang").val(data.IdCabang);
	$("#NamaCabang").val(data.NamaCabang);
	$("#KodeCabang").val(data.KodeCabang);
	$("#StatusKaryawan"+data.StatusKaryawan).prop('checked',true);
	$("#TptLahir").val(data.TptLahir);
	$("#TglLahir").val(data.TglLahir);
	$("#NIK").val(data.NIK);
	$("#NoKtp").val(data.NoKtp);
	$("#NamaKaryawan").val(data.NamaKaryawan);
	if (data.JenisKelamin == "LAKI-LAKI"){
		$("#JenisKelamin0").prop('checked', true);
	}else{
		$("#JenisKelamin1").prop('checked', true);
	}
	$("#NoHp").val(data.NoHp);
	$("#UnitTugas").val(data.UnitTugas);
	$("#Jabatan").val(data.Jabatan);
	$("#BpjsKes").val(data.BpjsKes);
	$("#BpjsTk").val(data.BpjsTk);
	$("#TMTCabang").val(data.TMTCabang);
	$("#TMTIsu").val(data.TMTIsu);
	$("#Agama").val(data.Agama);
	$("#UkuranBaju").val(data.UkuranBaju);
	$("#UkuranSepatu").val(data.UkuranSepatu);
	$("#Alamat").val(data.Alamat);
	LoadBank(data.IdKaryawan,data.RekeningUtama);
	LoadPendidikan(data.IdKaryawan, data.PendidikanTerakhir)

}
 

function Crud(IdKaryawan,Status){
	Clear();
	if (IdKaryawan){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/Karyawan/proses.php?proses=ShowData",
				data : "IdKaryawan="+IdKaryawan,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					//console.log(data);
					$("#DataTambahan").hide();
					$("#Title").html("Ubah Data Karyawan");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");
					$("#BtnGetNik").attr("onclick", "");
					$("#BtnGetNik").prop("disabled","false");
					IsiData(data);
					
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#IdKaryawan").val(IdKaryawan)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		//GetKodeCabang();
		$("#Title").html("Tambah Data Karyawan");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#NamaDivisi").focus();
		$("#aksi").val("insert");

	}

}

function UploadData(){
	Clear();
	$("#FormUpload").show();
	$("#DetailData").hide();
	$("#Title").html("Upload Data Karyawan");
	$("#UploadAksi").val("upload");
}

function Validasi(aksi){
	iForm = ["KodeCabang", "NamaCabang", "TptLahir", "TglLahir", "NIK", "NoKtp", "NamaKaryawan", "NoHp", "UnitTugas", "Jabatan", "BpjsKes", "BpjsTk", "TMTIsu", "UkuranBaju", "UkuranSepatu", "Alamat"];
	var KodeError = 1;
	if (aksi != "delete") {
		for(var i=0; i<iForm.length; i++){
			if ($("#" + iForm[i]).val() == "") { error("Karyawan", KodeError + i, iForm[i] + " Belum Lengkap!"); $("#" + iForm[i]).focus(); return false; }
		}

		if(aksi != "update"){
			var DataRekening = $(".itemRekening").length;
			if (DataRekening <= 0) {
				Customerror('Pendidikan', '001', "Tingkat Rekening Masih Kosong", "PesanRekening"); $("#Bank").focus(); return false;
			} else {
				if ($("#RekeningUtama").val() == "") { error("Karyawan", KodeError + i, "Rekening Utama Belum Lengkap!"); $("#RekeningUtama").focus(); return false; }
			}


			var DataPendidikan = $(".itemPendidikan").length;
			if (DataPendidikan <= 0) { 
				Customerror('Pendidikan', '001', "Tingkat Pendidikan Masih Kosong", "PesanPendidikan"); $("#TingkatPendidikan").focus(); return false; 
			}else{
				if ($("#PendidikanTerakhir").val() == "") { error("Karyawan", KodeError + i, "Pendidikan Terakhir Belum Lengkap!"); $("#PendidikanTerakhir").focus(); return false; }	
			}
		}else{
			if ($("#RekeningUtama").val() == "") { error("Karyawan", KodeError + i, "Rekening Utama Belum Lengkap!"); $("#RekeningUtama").focus(); return false; }
			if ($("#PendidikanTerakhir").val() == "") { error("Karyawan", KodeError + i, "Pendidikan Terakhir Belum Lengkap!"); $("#PendidikanTerakhir").focus(); return false; }	
		}
	}

}

function ValidasiUpload(){
	var iForm = ['UploadIdCabang', 'UploadAksi', 'UploadKodeCabang', 'UploadNamaCabang', 'File'];
	var KodeError = 1;
		for (var i = 0; i < iForm.length; i++) {
			if ($("#" + iForm).val() == "") { error("Karyawan", KodeError + i, iForm[i] + " Belum Lengkap!"); $("#" + iForm[i]).focus(); return false; }
		}
	
}

function SubmitUploadData(){
	var data = new FormData($("#FormUpload")[0]);
	if(ValidasiUpload() != false){
	    console.log(data);
		$.ajax({
			type : "POST",
			dataType : 'json',
			url : "inc/Karyawan/proses.php?proses=UploadData",
			processData: false,
			contentType: false,
			cache : false,
			data : data,
			beforeSend : function(){
				console.log('Upload Data.....');
				StartLoad();
			},
			success: function(data){
			    var Table = $("#TableData").DataTable();
				console.log(data);
				if(data.msg == "sukses"){
				    Clear();
				    Customsukses('Karyawan', '004', 'Berhasil Mengupload Data Karyawan', 'proses');
				    Table.ajax.reload();
				    StopLoad();
				}else if(data.msg == 'filenotfound'){
				    error("Karyawan", 3 , "File  Belum Lampirkan!"); $("#File").focus(); return false;
				    StopLoad();
				}else if(data.msg == 'filenotsupport'){
				    error("Karyawan", 3 , "Extensi File tidak didukung oleh Sistem. silahkan upload file dengan Extensi <b>.xls</b>"); $("#File").focus(); return false;
				    StopLoad();
				}
			},
			error: function(er){
				console.log(er);
			}
		});
	}
}

function SubmitData(){
	var aksi = $("#aksi").val();

	if(Validasi(aksi) != false){
		var data = $("#FormData").serialize();
		$.ajax({
			type : "POST",
			url : "inc/Karyawan/proses.php?proses=Crud",
			data : data,
			beforeSend: function() {
				StartLoad();
			},
			success: function(result){
				console.log(result);
				var Table = $("#TableData").DataTable();
				if(result == "sukses"){
					Clear();
					sukses(aksi,"Divisi",'003');
					Table.ajax.reload();
					StopLoad();
				}
			},
			error : function(er){
				console.log(er);
			}
		});
	}
	

}

function getUrlVars(param = null) {
	if (param !== null) {
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for (var i = 0; i < hashes.length; i++) {
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars[param];
	}
	else {
		return null;
	}
}