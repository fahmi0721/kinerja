$(document).ready(function(){
    Clear();
	LoadData();
	var Jabatan = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('NamaJabatan'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: 'load.php?proses=GetTags'
    });
	Jabatan.initialize();

	$("#JabatanTerkait").tagsinput({
        tagClass: 'label label-success',
        itemValue: 'value',
        itemText: 'NamaJabatan',
        typeaheadjs: {
            name: 'Jabatan',
            displayKey: 'NamaJabatan',
            source: Jabatan.ttAdapter()
        }
	});
	
});


function ShowJabtan(data){
	for(var i=0; i < data.item.length; i ++){
		$("#JabatanTerkait").tagsinput('add', { "value" : data.item[i].value, "NamaJabatan" : data.item[i].NamaJabatan });
	}
}

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/IndikatorKp/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "Kompetensi" },
			{ "data" : "Bobot" },
			{ "data" : "Target"},
			{ "data" : "Jabatan"},	
			{ "data" : "Keterangan"},
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}



function Clear(){
	$("#Title").html("Tampil Data Indikator Kompetensi");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi","IdIkp","Bobot","Target","Kompetensi","JabatanTerkait","Keterangan"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
	var cek = $(".tag").length;
	if(cek > 0){
		$("#JabatanTerkait").tagsinput("removeAll");
	}
	
}

function Crud(IdIkp,Status){
	Clear();
	if (IdIkp){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/IndikatorKp/proses.php?proses=ShowData",
				data : "IdIkp="+IdIkp,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Indikator Kompetensi");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdIkp").val(data.IdIkp);
					$("#Kompetensi").val(data.Kompetensi);
					$("#Bobot").val(data.Bobot);
					$("#Target").val(data.Target);
					$("#Keterangan").val(data.Keterangan);
					ShowJabtan(data);
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#IdIkp").val(IdIkp)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data Indikator Kompetensi");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#Kompetensi").focus();
		$("#aksi").val("insert");

	}

}


function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = ["Kompetensi", "Bobot","Target","Keterangan"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Indikator Kompetensi", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}
	

	var data = $("#FormData").serialize();
	$.ajax({
		type : "POST",
		url : "inc/IndikatorKp/proses.php?proses=Crud",
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