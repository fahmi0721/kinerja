$(document).ready(function(){
    Clear();
	LoadData();
	
});

function LoadJabatan(){
	$.ajax({
		type : "GET",
		dataType : "json",
		url : "inc/JenisTk/proses.php",
		data: "proses=ShowJabatan",
		beforeSend : function(){
			StartLoad();
		},
		success : function(result){
			console.log(result);
			var html ="<div class='row'>";
			for(var i=0; i<result['Item'].length; i++){
				html += "<div class='col-sm-3' style='margin-bottom:3px'>";
				html += "<div class='input-group'>";
					html += "<span class='input-group-addon'><input type='checkbox' name='Jabatan[]' value='"+result['Item'][i]['KodeJabatan']+"'> </span>";
					html += "<input type='text' disabled value='"+result['Item'][i]['NamaJabatan']+"' class='form-control' />";
				html += "</div>";
				html += "</div>";
			}
				html += "</div>";
			$("#Jabatans").html(html);
			StopLoad();
		},
		error : function(er){
			console.log(er);
		}
	})
}

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/JenisTk/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "NamaJenisTk" },
			{ "data" : "Jumlah" },
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}


function Clear(){
	$("#Title").html("Tampil Data Breakdown Tenaga Kerja");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi","IdBiayaBahan","IdCabang","KodeCabang","NamaCabang","NamaBiaya","Biaya","Ppn","BerlakuMulai","BerlakuSampai","Keterangan"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
	$("#KodeCabang").prop("readonly", false);
}

function Crud(IdBiayaBahan,Status){
	Clear();
	if (IdBiayaBahan){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/BiayaBahan/proses.php?proses=ShowData",
				data : "IdBiayaBahan="+IdBiayaBahan,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Breakdown Tenaga Kerja");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdBiayaBahan").val(data.IdBiayaBahan);
					$("#IdCabang").val(data.IdCabang);
					$("#KodeCabang").val(data.KodeCabang);
					$("#KodeCabang").prop("readonly",true);
					$("#NamaCabang").val(data.NamaCabang);
					$("#NamaBiaya").val(data.NamaBiaya);
					$("#Biaya").val(formatRupiah(data.Biaya));
					$("#Ppn").val(formatRupiah(data.Ppn));
					$("#BerlakuMulai").val(data.BerlakuMulai);
					$("#BerlakuSampai").val(data.BerlakuSampai);
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
			$("#IdBiayaBahan").val(IdBiayaBahan)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		LoadJabatan();
		$("#Title").html("Tambah Data Breakdown Tenaga Kerja");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#KodeCabang").focus();
		$("#aksi").val("insert");

	}

}


function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = [ "NamaJenisTk",];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Jenis TK", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}
	

	var data = $("#FormData").serialize();
	$.ajax({
		type : "POST",
		url : "inc/JenisTk/proses.php?proses=Crud",
		data : data,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				sukses(aksi,"Biaya Bahan",'002');
				Table.ajax.reload();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	});
	

}