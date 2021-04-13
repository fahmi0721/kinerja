$(document).ready(function(){
    Clear();
	LoadData();
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/Cabang/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "KodeCabang" },
			{ "data" : "NamaCabang" },
			{ "data" : "Alamat"},
			{ "data" : "Keterangan"},
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}


function GetKodeCabang(){
	$.ajax({
		type : "GET",
		url  : "inc/Cabang/proses.php",
		data : "proses=GetKode",
		cache : false,
		beforeSend: function (data) {
			StartLoad();
		},
		success : function(data){
			console.log(data);
			$("#KodeCabang").val(data);
			StopLoad();
		},
		error: function(er){
			console.log(er);
		}
	})
}

function Clear(){
	$("#Title").html("Tampil Data Cabang");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi","IdCabang","KodeCabang","NamaCabang","Alamat","Keterangan"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
}

function Crud(IdCabang,Status){
	Clear();
	if (IdCabang){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/Cabang/proses.php?proses=ShowData",
				data : "IdCabang="+IdCabang,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Cabang");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdCabang").val(data.IdCabang);
					$("#KodeCabang").val(data.KodeCabang);
					$("#NamaCabang").val(data.NamaCabang);
					$("#Alamat").val(data.Alamat);
					$("#Keterangan").val(data.Keterangan);
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#IdCabang").val(IdCabang)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		GetKodeCabang();
		$("#Title").html("Tambah Data Cabang");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#NamaCabang").focus();
		$("#aksi").val("insert");

	}

}


function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = ["NamaCabang", "Alamat","Keterangan"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Cabang", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}
	

	var data = $("#FormData").serialize();
	$.ajax({
		type : "POST",
		url : "inc/Cabang/proses.php?proses=Crud",
		data : data,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				sukses(aksi,"Cabang",'002');
				Table.ajax.reload();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	});
	

}