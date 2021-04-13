$(document).ready(function(){
    Clear();
	LoadData();
	$("#TglSurat").datepicker({"format": "yyyy-mm-dd", "autoclose" : true});
	var Ditujukan = [
					"DIREKTUR UTAMA",
					"DIREKTUR OPERASIONAL & KOMERSIAL",
					"DIREKTUR SDM & KEUANGAN" 
	];
	$( "#Tujuan" ).autocomplete({
		source: Ditujukan
	});
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/SuratMasuk/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "NoRegis" },
			{ "data" : "TglSurat" },
			{ "data" : "BajuSurat"},
			{ "data" : "Perihal"},
			{ "data" : "NomorSurat"},
			{ "data" : "Tujuan"},
			{ "data" : "AsalSurat"},
			{ "data" : "Status"},
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}


function Clear(){
	$("#Title").html("Tampil Data Surat Masuk");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi","IdSuratMasuk","TmpFileSurat","BajuSurat","TglSurat","NoSurat","Perihal","FileSurat","Tujuan","AsalSurat","Tl","S"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
}

function Crud(IdSuratMasuk,Status){
	Clear();
	if (IdSuratMasuk){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/SuratMasuk/proses.php?proses=ShowData",
				data : "IdSuratMasuk="+IdSuratMasuk,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Surat Masuk");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdSuratMasuk").val(data.IdSuratMasuk);
					$("#TmpFileSurat").val(data.FileSurat);
					$("#TglSurat").val(data.TglSurat);
					$("#BajuSurat").val(data.BajuSurat);
					$("#NoSurat").val(data.NomorSurat);
					$("#Perihal").val(data.Perihal);
					$("#Tujuan").val(data.Tujuan);
					$("#AsalSurat").val(data.AsalSurat);
					$("#Tl").val(data.Tl);
					$("#S").val(data.S);
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#IdSuratMasuk").val(IdSuratMasuk)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data Surat Keluar");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#BajuSurat").focus();
		$("#aksi").val("insert");

	}

}


function DetailSurat(Surat){
	$("#ModalSurat").modal('show');
	$("#SuratDetail").html(
		"<div class='embed-responsive embed-responsive-16by9'>"
			+"<iframe class='embed-responsive-item' src='Files/SuratMasuk/"+Surat+"' allowfullscreen></iframe>"
		+"</div>"
	)
}



function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = ["BajuSurat","TglSurat","NoSurat","Perihal","FileSurat","Tujuan","AsalSurat","Tl","S"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if((aksi != "update") && (iForm[i] != "FileSurat")){
				if($("#"+iForm[i]).val() == ""){ error("Surat Masuk", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
			}
		}
	}
	

	var data = new FormData($("#FormData")[0]);
	$.ajax({
		type : "POST",
		url : "inc/SuratMasuk/proses.php?proses=Crud",
		data : data,
		processData: false,
		contentType: false,
		cache: false,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				sukses(aksi,"Surat Masuk",'003');
				Table.ajax.reload();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	});
	

}

function LoadDataSuratMasuk(IdSuratMasuk){
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "inc/SuratMasuk/proses.php?proses=ShowData",
		data: "IdSuratMasuk=" + IdSuratMasuk,
		beforeSend: function (data) {
			StartLoad();
		},
		success: function (data) {
			$("#IdSurat").val(data.IdSuratMasuk);
			$("#NomorSurat").val(data.NomorSurat);
			$("#TanggalSurat").val(data.TglSurat);
			$("#TanggalMasuk").val(data.TanggalMasuk);
			$("#PerihalSurat").val(data.Perihal);
			StopLoad();
		},
		error: function (er) {
			console.log(er);
		}
	})
}

function LoadDataDisposisi(IdSuratMasuk) {
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "inc/SuratMasuk/proses.php?proses=ShowDataDisposisi",
		data: "IdSuratMasuk=" + IdSuratMasuk,
		beforeSend: function (data) {
			StartLoad();
		},
		success: function (data) {
			console.log(data);
			$("#IdDisposisi").val(data.IdDisposisi);
			$("#Catatan").val(data.Catatan);
			for (var i = 0; i < data.ItemKepada.length; i++){
				$("#Kepada" + data.ItemKepada[i]).prop("checked",true);
			}
			for (var i = 0; i < data['ItemDisposisi'].length; i++) {
				$("#Disposisi" + data['ItemDisposisi'][i]).prop("checked", true);
			}
			StopLoad();
		},
		error: function (er) {
			console.log(er);
		}
	})
}

function CelarDisposisi(){
	$("#AksiDisposisi").val("");
	$("#CtrlBtn").show();
	$(".Kepada").prop("checked", false);
	$(".Disposisi").prop("checked", false);
	$(".Kepada").prop("disabled", false);
	$(".Disposisi").prop("disabled", false);
}

function Disposisi(IdSuratMasuk,st){
	CelarDisposisi();
	$("#ModalDisposisi").modal("show");
	if(st == "0"){
		$("#AksiDisposisi").val("insert");
		LoadDataSuratMasuk(IdSuratMasuk);

	}else if(st == 1){
		$("#AksiDisposisi").val("update");
		LoadDataSuratMasuk(IdSuratMasuk);
		LoadDataDisposisi(IdSuratMasuk);
	}else if(st == 2){
		LoadDataSuratMasuk(IdSuratMasuk);
		LoadDataDisposisi(IdSuratMasuk);
		$("#CtrlBtn").hide();
		$(".Kepada").prop("disabled", true);
		$(".Disposisi").prop("disabled", true);
	}
	

}

function SubmitDiposisi(){
	var data = $("#FormDisposisi").serialize();
	$.ajax({
		type : "POST",
		url : "inc/SuratMasuk/proses.php?proses=UpdateDisposisi",
		data : data,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var aksi = $("#AksiDisposisi").val();
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				$("#ModalDisposisi").modal("hide");
				Clear();
				sukses(aksi,"Disposisi Surat Masuk",'003');
				Table.ajax.reload();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	});
}