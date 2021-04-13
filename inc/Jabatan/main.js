$(document).ready(function(){
    Clear();
	LoadData();
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/Jabatan/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "KodeJabatan" },
			{ "data" : "NamaJabatan" },
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
		url  : "inc/Jabatan/proses.php",
		data : "proses=GetKode",
		cache : false,
		beforeSend: function (data) {
			StartLoad();
		},
		success : function(data){
			console.log(data);
			$("#KodeJabatan").val(data);
			StopLoad();
		},
		error: function(er){
			console.log(er);
		}
	})
}

function Clear(){
	$("#Title").html("Tampil Data Jabatan");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#FormUplopad").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi","IdJabatan","KodeJabatan","NamaJabatan","Keterangan"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
}

function Crud(IdJabatan,Status){
	Clear();
	if (IdJabatan){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/Jabatan/proses.php?proses=ShowData",
				data : "IdJabatan="+IdJabatan,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Jabatan");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdJabatan").val(data.IdJabatan);
					$("#KodeJabatan").val(data.KodeJabatan);
					$("#NamaJabatan").val(data.NamaJabatan);
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
			$("#IdJabatan").val(IdJabatan)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		GetKodeCabang();
		$("#Title").html("Tambah Data Jabatan");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#NamaJabatan").focus();
		$("#aksi").val("insert");

	}

}

function ShowFormUpload(){
	Clear();
	$("#FormUplopad").show();
	$("#DetailData").hide();
}

function SubmitUpload(){
	if($("#File").val() == ""){ error("Jabatan", 1, "File Belum Lengkap!"); $("#File").focus(); return false; }
	var data = new FormData($("#FormUplopad")[0]);
	$.ajax({
		type : "POST",
		url : "inc/Jabatan/proses.php?proses=UploadData",
		processData : false,
		contentType : false,
		chace : false,
		data : data,
		beforeSend : function(){
			StartLoad();
		},
		success: function(res){
			var Table = $("#TableData").DataTable();
			if(res == "sukses"){
				Clear();
				sukses('insert',"Jabatan",'003');
				Table.ajax.reload();
				StopLoad();
			}
		},
		error: function(er){
			console.log(er);
		}
	})
}

function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = ["NamaJabatan","Keterangan"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Jabatan", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}
	

	var data = $("#FormData").serialize();
	$.ajax({
		type : "POST",
		url : "inc/Jabatan/proses.php?proses=Crud",
		data : data,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				sukses(aksi,"Jabatan",'003');
				Table.ajax.reload();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	});
	

}