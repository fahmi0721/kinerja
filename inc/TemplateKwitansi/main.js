$(document).ready(function(){
    Clear();
	LoadData();
});


function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/TemplateKwitansi/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "JudulTemplate" },
			{ "data" : "Keterangan"},
			{ "data" : "TglCreate" },
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}


function Clear(){
	$("#Title").html("Tampil Data Template Kwitansi");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi", "TmpFile", "IdTemplateKwitansi",  "JudulTemplate", "Keterangan", "FileTemplate"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
}

function Crud(IdTemplateKwitansi,Status,TmpFile){
	Clear();
	if (IdTemplateKwitansi){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/TemplateKwitansi/proses.php?proses=ShowData",
				data : "IdTemplateKwitansi="+IdTemplateKwitansi,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Template Kwitansi");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdTemplateKwitansi").val(data.IdTemplateKwitansi);
					$("#JudulTemplate").val(data.JudulTemplate);
					$("#Keterangan").val(data.Keterangan);
					$("#TmpFile").val(data.FileTemplate);
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#IdTemplateKwitansi").val(IdTemplateKwitansi);
			$("#TmpFile").val(TmpFile);
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data Template Kwitansi");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#JudulTemplate").focus();
		$("#aksi").val("insert");

	}

}




function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = ["JudulTemplate", "Keterangan"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Template Kwitansi", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}

	if(aksi == "insert"){
		if($("#FileTemplate").val() == ""){ error("Template Kwitansi", KodeError + 1, "FileTemplate Belum Lengkap!"); $("#FileTemplate").focus(); return false; }
	}

	var data = new FormData($("#FormData")[0]);
	$.ajax({
		type : "POST",
		url : "inc/TemplateKwitansi/proses.php?proses=Crud",
		chace: false,
		contentType : false,
		processData :false,
		data : data,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				sukses(aksi,"Template Kwitansi",'002');
				Table.ajax.reload();
				StopLoad();
			} else if (result == "filenotsupport"){
				error("Kontrak", KodeError + 1, "File Yang Anda Masukkan Tidak Didukung Oleh Sistem!"); $("#FileTemplate").focus(); StopLoad();  return false;
				
			}
		},
		error : function(er){
			console.log(er);
		}
	});
	

}