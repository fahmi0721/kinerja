$(document).ready(function(){
    Clear();
	LoadData();
	$("#TanggalSurat").datepicker({ "format": "yyyy-mm-dd", "autoclose": true });
	$("#Kode").autocomplete({
		source: "load.php?proses=getDataJenisSurat",
		select: function (event, ui) {
			$("#Kode").val(ui.item.label);
			$("#NamaJenis").val(ui.item.NamaJenis)
			$("#IdJenisSurat").val(ui.item.IdJenisSurat);
			$("#KodeJenis").val(ui.item.label);
			GetNomorSurat(ui.item.IdJenisSurat);
		}
	})
	.autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | " + item.NamaJenis + "</div>").appendTo(ul); };
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/SuratKeluar/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "NomorSurat" },
			{ "data" : "Perihal" },
			{ "data": "TanggalSurat" },
			{ "data": "Ditujukan" },
			{ "data": "Keterangan" },
			{ "data": "File", "sClass": "text-center" },
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}


function GetNomorSurat(IdJenisSurat){
	var Tahun = $("#Tahuns").val();
	$.ajax({
		type : "POST",
		dataType : "json",
		url: "inc/SuratKeluar/proses.php?proses=GetNomorSurat",
		data : "IdJenisSurat="+IdJenisSurat+"&Tahun="+Tahun,
		beforeSend: function (data) {
			StartLoad();
		},
		success : function(result){
			console.log(result);
			$("#NomorUrut").val(result['NomorUrut']);
			$("#Halaman").val(result['Halaman']);
			if(result['Tahun'] == "2020" || result['Tahun'] == "2019"){
				$("#Lbl").val("ISEMA");
			}else{
				$("#Lbl").val("ISMA");

			}
			$("#Tahun").val(result['Tahun']);
			StopLoad();
		},
		error: function(er){
			console.log(er);
		}
	})
}

function Clear(){
	$("#Title").html("Tampil Data Surat Keluar");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi", "IdSuratKeluar", "IdJenisSurat", "Kode", "NamaJenis", "NomorUrut", "KodeJenis", "Halaman", "TanggalSurat", "Perihal", "Ditujukan", "Keterangan", "FileSurat","TmpFile"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
}

function Crud(IdSuratKeluar,Status,TmpFile){
	Clear();
	if (IdSuratKeluar){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url: "inc/SuratKeluar/proses.php?proses=ShowData",
				data : "IdSuratKeluar="+IdSuratKeluar,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Surat Keluar");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdSuratKeluar").val(data.IdSuratKeluar);
					$("#IdJenisSurat").val(data.IdJenisSurat);
					$("#Kode").val(data.Kode);
					$("#NamaJenis").val(data.NamaJenis);
					$("#NomorUrut").val(data.NomorUrut);
					$("#KodeJenis").val(data.Kode);
					$("#Halaman").val(data.Halaman);
					$("#Tahun").val(data.Tahun);
					$("#TanggalSurat").val(data.TanggalSurat);
					$("#Perihal").val(data.Perihal);
					$("#Ditujukan").val(data.Ditujukan);
					$("#Keterangan").val(data.Keterangan);
					$("#TmpFile").val(data.FileSurat);
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#IdSuratKeluar").val(IdSuratKeluar);
			$("#TmpFile").val(TmpFile);
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data Surat Keluar");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#JenisSurat").focus();
		$("#aksi").val("insert");

	}

}


function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = ["Kode", "NomorUrut", "Halaman", "TanggalSurat", "Perihal", "Ditujukan", "Keterangan"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Surat Keluar", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}
	
	

	var data = new FormData($("#FormData")[0]);
	$.ajax({
		type : "POST",
		url : "inc/SuratKeluar/proses.php?proses=Crud",
		cache : false,
		contentType : false,
		processData: false,
		data : data,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				sukses(aksi,"Surat Keluar",'002');
				Table.ajax.reload();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	});
	

}