$(document).ready(function(){
    Clear();
	LoadData();
	$("#TanggalNotaDinas").datepicker({ "format": "yyyy-mm-dd", "autoclose": true });
	$("#Dari").autocomplete({
		source: "load.php?proses=getDataJabatanPejabat",
		select: function (event, ui) {
			$("#Dari").val(ui.item.label);
			$("#IdJabatanDari").val(ui.item.IdJabatan)
			$("#KodeDisposisi").val(ui.item.KodeDisposisi);
		}
	})
	.autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | " + item.KodeDisposisi + "</div>").appendTo(ul); };

	$("#Ditujukan").autocomplete({
		source: "load.php?proses=getDataJabatanPejabat",
		select: function (event, ui) {
			$("#Ditujukan").val(ui.item.label);
			$("#IdJabatanDitujukan").val(ui.item.IdJabatan)
		}
	})
	.autocomplete("instance")._renderItem = function (ul, item) { return $("<li>").append("<div>" + item.label + " | " + item.KodeDisposisi + "</div>").appendTo(ul); };
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/NotaDinas/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data": "NomorNotaDinas" },
			{ "data" : "Perihal" },
			{ "data": "TanggalNotaDinas" },
			{ "data": "NamaJabatan" },
			{ "data": "Keterangan" },
			{ "data": "File", "sClass": "text-center" },
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}


function GetNomorSurat(){
	
	$.ajax({
		type : "GET",
		dataType : "json",
		url: "inc/NotaDinas/proses.php?proses=GetNomorSurat",
		data: "proses=GetNomorSurat",
		beforeSend: function (data) {
			StartLoad();
		},
		success : function(result){
			console.log(result);
			$("#NomorUrut").val(result['NomorUrut']);
			StopLoad();
		},
		error: function(er){
			console.log(er);
		}
	})
}

function Clear(){
	$("#Title").html("Tampil Data Nota Dinas");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi", "IdNotaDinas", "IdJabatanDari", "IdJabatanDitujukan", "Dari", "Ditujukan", "NomorUrut", "KodeDisposisi", "TanggalNotaDinas", "Perihal", "Keterangan", "FileSurat","TmpFile"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
}

function Crud(IdNotaDinas,Status,TmpFile){
	Clear();
	if (IdNotaDinas){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url: "inc/NotaDinas/proses.php?proses=ShowData",
				data : "IdNotaDinas="+IdNotaDinas,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Nota Dinas");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdNotaDinas").val(data.IdNotaDinas);
					$("#IdJabatanDari").val(data.IdJabatanDari);
					$("#IdJabatanDitujukan").val(data.IdJabatanDitujukan);
					$("#Dari").val(data.Dari);
					$("#Ditujukan").val(data.Ditujukan);
					$("#NomorUrut").val(data.NomorUrut);
					$("#KodeDisposisi").val(data.KodeDisposisi);
					$("#Bulan").val(data.Bulan);
					$("#Tahun").val(data.Tahun);
					$("#TanggalNotaDinas").val(data.TanggalNotaDinas);
					$("#Perihal").val(data.Perihal);
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
			$("#IdNotaDinas").val(IdNotaDinas);
			$("#TmpFile").val(TmpFile);
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		GetNomorSurat()
		$("#Title").html("Tambah Data Nota Dinas");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#JenisSurat").focus();
		$("#aksi").val("insert");

	}

}

function ClearDisposisi(){
	$("#CloseModal").trigger('click');
	var iForm = ["#AksiDisposisi", "#IdSurat", "#NomorNota", "#TglSurat", "#PerihalSurat","#Catatan"];
	for(var i=0; i < iForm.length; i++){
		$(iForm[i]).val("");
	}
	iForm2 = [".Kepada",".Disposisi"];
	for (var i = 0; i < iForm2.length; i++) {
		$(iForm2[i]).prop("checked", false);
		$(iForm2[i]).prop("disabled", false);
	}
	$("#Catatan").prop("disabled", false);
	$("#BtnSub").show();

}

function OpenDisposisi(IdNotaDinas,Status,Level){
	ClearDisposisi();
	jQuery("#DisposisiModal").modal('show', { backdrop: 'static' });
	if(Status == 0){
		$("#AksiDisposisi").val("insert");
		LoadDataNotaDinas(IdNotaDinas);
	}else if(Status == 1){
		$("#AksiDisposisi").val("update");
		LoadDataNotaDinas(IdNotaDinas);
		LoadDataDisposisi(IdNotaDinas);
	}else{
		LoadDataNotaDinas(IdNotaDinas);
		LoadDataDisposisi(IdNotaDinas);
		iForm2 = [".Kepada", ".Disposisi"];
		for (var i = 0; i < iForm2.length; i++) {
			$(iForm2[i]).prop("disabled", true);
		}
		$("#Catatan").prop("disabled", true);
		$("#BtnSub").hide();
	}
	
}

function LoadDataDisposisi(IdNotaDinas){
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "inc/NotaDinas/proses.php?proses=ShowDataDisposisi",
		data: "IdNotaDinas=" + IdNotaDinas,
		beforeSend: function () {
			StartLoad();
		},
		success: function (res) {
			console.log(res);
			console.log(res['ItemDisposisi'].length);
			for (i = 0; i < res['ItemDisposisi'].length; i++){
				$("#Disposisi" + res['ItemDisposisi'][i]).prop("checked",true);
			}

			for (i = 0; i < res['ItemKepada'].length; i++) {
				$("#Kepada" + res['ItemKepada'][i]).prop("checked", true);
			}
			$("#IdDisposisi").val(res['IdDisposisi']);
			$("#Catatan").val(res['Catatan']);
			StopLoad();
		},
		error: function (er) {
			console.log(er);
		}

	})
}

function LoadDataNotaDinas(IdNotaDinas){
	$.ajax({
		type : "POST",
		dataType : "json",
		url : "inc/NotaDinas/proses.php?proses=ShowData",
		data : "IdNotaDinas="+IdNotaDinas,
		beforeSend : function(){
			StartLoad();
		},
		success : function(res){
			$("#IdSurat").val(IdNotaDinas);
			$("#NomorNota").val(res['NomorNotaDinas']);
			$("#TglSurat").val(res['TanggalNotaDinas']);
			$("#PerihalSurat").val(res['Perihal']);
			StopLoad();
		},
		error : function(er){
			console.log(er);
		}

	})
}

function SubmitDisposisi(){
	var iData = $("#FormDisposisi").serialize();
	$.ajax({
		type : "POST",
		url : "inc/NotaDinas/proses.php?proses=CrudDisposisi",
		data : iData,
		beforeSend : function(){
			StartLoad();
		},
		success : function(res){
			console.log(res);
			var Table = $("#TableData").DataTable();
			if (res == "sukses") {
				Clear();
				ClearDisposisi();
				var msg = $("#AksiDisposisi").va() == "insert" ? "Menambah" : "Mengubah";
				Customsukses("002", "001", "Berhasil "+msg+" Disposisi", "proses");
				Table.ajax.reload();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	})
}

function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = ["Dari", "Ditujukan", "NomorUrut", "KodeDisposisi", "TanggalNotaDinas", "Perihal", "Keterangan"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Nota Dinas", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}
	
	

	var data = new FormData($("#FormData")[0]);
	$.ajax({
		type : "POST",
		url : "inc/NotaDinas/proses.php?proses=Crud",
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
				sukses(aksi,"Nota Dinas",'002');
				Table.ajax.reload();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	});
	

}