$(document).ready(function(){
    Clear();
	LoadData();
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/Lisensi/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "KodeLisensi" },
			{ "data" : "NamaLisensi" },
			{ "data" : "Keterangan"},
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}


function GetKodeLisensi(){
	$.ajax({
		type : "GET",
		url  : "inc/Lisensi/proses.php",
		data : "proses=GetKode",
		cache : false,
		beforeSend: function (data) {
			StartLoad();
		},
		success : function(data){
			console.log(data);
			$("#KodeLisensi").val(data);
			StopLoad();
		},
		error: function(er){
			console.log(er);
		}
	})
}

function Clear(){
	$("#Title").html("Tampil Data Lisensi");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi","IdLisensi","KodeLisensi","NamaLisensi","Keterangan"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
}

function Crud(IdLisensi,Status){
	Clear();
	if (IdLisensi){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/Lisensi/proses.php?proses=ShowData",
				data : "IdLisensi="+IdLisensi,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Lisensi");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdLisensi").val(data.IdLisensi);
					$("#KodeLisensi").val(data.KodeLisensi);
					$("#NamaLisensi").val(data.NamaLisensi);
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
			$("#IdLisensi").val(IdLisensi)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		GetKodeLisensi();
		$("#Title").html("Tambah Data Lisensi");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#NamaLisensi").focus();
		$("#aksi").val("insert");

	}

}


function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = ["NamaLisensi","Keterangan"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Lisensi", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}
	

	var data = $("#FormData").serialize();
	$.ajax({
		type : "POST",
		url : "inc/Lisensi/proses.php?proses=Crud",
		data : data,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				sukses(aksi,"Lisensi",'004');
				Table.ajax.reload();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	});
	

}