$(document).ready(function(){
    Clear();
	LoadData();
});


function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/TemplateInvoice/proses.php?proses=DetailData",
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
	$("#Title").html("Tampil Data Template Invoice");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi", "TmpFile", "IdTemplateInvoice",  "JudulTemplate", "Keterangan", "FileTemplate"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
}

function Crud(IdTemplateInvoice,Status,TmpFile){
	Clear();
	if (IdTemplateInvoice){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/TemplateInvoice/proses.php?proses=ShowData",
				data : "IdTemplateInvoice="+IdTemplateInvoice,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Template Invoice");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdTemplateInvoice").val(data.IdTemplateInvoice);
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
			$("#IdTemplateInvoice").val(IdTemplateInvoice);
			$("#TmpFile").val(TmpFile);
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data Template Invoice");
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
			if($("#"+iForm[i]).val() == ""){ error("Template Invoice", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}

	if(aksi == "insert"){
		if($("#FileTemplate")){ error("Template Invoice", KodeError + 1, "FileTemplate Belum Lengkap!"); $("#FileTemplate").focus(); return false; }
	}

	var data = new FormData($("#FormData")[0]);
	$.ajax({
		type : "POST",
		url : "inc/TemplateInvoice/proses.php?proses=Crud",
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
				sukses(aksi,"Template Invoice",'002');
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