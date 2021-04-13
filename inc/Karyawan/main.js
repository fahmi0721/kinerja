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
	}).autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | " + item.NamaCabang + "</div>").appendTo(ul); };

	$("#UploadJabatan").autocomplete({
		source: "load.php?proses=getKodeJabatan",
		select: function (event, ui) {
			$("#UploadJabatan").val(ui.item.label);
			$("#UploadNamaJabatan").val(ui.item.NamaJabatan);
		}
	}).autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | " + item.NamaJabatan + "</div>").appendTo(ul); };
	
	$("#Bank").autocomplete({
		source: dataBank
	});
});



function LoadData(){
	var Filter = $("#Filter").val();
	$("#TableData_filter").find('input').val(Filter);
	$("#TableData").DataTable({
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
	var iForm = ["aksi", "IdKaryawan", "IdCabang", "KodeCabang", "NamaCabang", "TptLahir", "TglLahir", "NIK", "NoKtp", "NamaKaryawan", "NoHp", "UnitTugas", "Jabatan", "TMTCabang", "TMTIsu", "Agama", "UkuranBaju", "UkuranSepatu","Alamat"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
	var iForm = ['UploadIdCabang', 'UploadAksi', 'UploadKodeCabang','UploadJabatan', 'File'];
	for (var i = 0; i < iForm.length; i++) {
		$("#" + iForm[i]).val("");
	}

	$("#TptLahir").prop('disabled', false);
	$("#TglLahir").prop('disabled', false);
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
* Isi Data
*/

function IsiData(data){
	console.log(data);
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
	$("#TMTCabang").val(data.TMTCabang);
	$("#TMTIsu").val(data.TMTIsu);
	$("#Agama").val(data.Agama);
	$("#UkuranBaju").val(data.UkuranBaju);
	$("#UkuranSepatu").val(data.UkuranSepatu);
	$("#Alamat").val(data.Alamat);

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
					$("#TptLahir").prop('disabled', true);
					$("#TglLahir").prop('disabled', true);
					
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
	iForm = ["KodeCabang", "NamaCabang", "TptLahir", "TglLahir", "NIK", "NoKtp", "NamaKaryawan", "NoHp", "UnitTugas", "Jabatan", "TMTIsu", "UkuranBaju", "UkuranSepatu", "Alamat"];
	var KodeError = 1;
	if (aksi != "delete") {
		for(var i=0; i<iForm.length; i++){
			if ($("#" + iForm[i]).val() == "") { error("Karyawan", KodeError + i, iForm[i] + " Belum Lengkap!"); $("#" + iForm[i]).focus(); return false; }
		}
	}

}

function ValidasiUpload(){
	var iForm = ['UploadIdCabang', 'UploadAksi', 'UploadKodeCabang', 'UploadNamaCabang', 'UploadJabatan', 'File'];
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
				    error("Karyawan", 3 , "File  Belum Lampirkan!"); $("#File").focus(); StopLoad(); return false;
				    
				}else if(data.msg == 'filenotsupport'){
				    error("Karyawan", 3 , "Extensi File tidak didukung oleh Sistem. silahkan upload file dengan Extensi <b>.xls</b>"); $("#File").focus(); StopLoad(); return false;
				    
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