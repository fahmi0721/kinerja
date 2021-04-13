$(document).ready(function(){
	Clear();
	var Jabatan = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('NamaJabatan'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: 'load.php?proses=GetTags'
    });
	Jabatan.initialize();

	$("#JabatanAprove").tagsinput({
        tagClass: 'label label-success',
        itemValue: 'value',
        itemText: 'NamaJabatan',
        typeaheadjs: {
            name: 'Jabatan',
            displayKey: 'NamaJabatan',
            source: Jabatan.ttAdapter()
        }
	});
	
	$("#JabatanPembuatRKB").tagsinput({
        tagClass: 'label label-primary',
        itemValue: 'value',
        itemText: 'NamaJabatan',
        typeaheadjs: {
            name: 'Jabatan',
            displayKey: 'NamaJabatan',
            source: Jabatan.ttAdapter()
        }
    });
	LoadData();
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/SAM/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "JabatanAprove" },
			{ "data" : "JabatanPembuatRKB" },
			{ "data" : "Keterangan"},
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}

function ShowJabtanAprove(data){
	$("#JabatanAprove").tagsinput('add', { "value" : data.itemJabatanAperove.value, "NamaJabatan" : data.itemJabatanAperove.NamaJabatan });
}

function ShowJabtanRKB(data){
	for(var i=0; i < data.itemJabatanRKB.length; i ++){
		$("#JabatanPembuatRKB").tagsinput('add', { "value" : data.itemJabatanRKB[i].value, "NamaJabatan" : data.itemJabatanRKB[i].NamaJabatan });
	}
}
function Clear(){
	$("#Title").html("Tampil Data Aprove MKP");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi","IdAproveMkp","JabatanAprove","JabatanPembuatRKB","Keterangan"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
	
}

function Crud(IdAproveMkp,Status){
	Clear();
	if (IdAproveMkp){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/SAM/proses.php?proses=ShowData",
				data : "IdAproveMkp="+IdAproveMkp,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					$('#JabatanAprove').tagsinput('removeAll');
					$('#JabatanPembuatRKB').tagsinput('removeAll');
					console.log(data);
					$("#Title").html("Ubah Data Aprove MKP");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdAproveMkp").val(data.IdAproveMkp);
					ShowJabtanAprove(data);
					ShowJabtanRKB(data);
					$("#JabatanPembuatRKB").val(data.JabatanPembuatRKB);
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
			$("#IdAproveMkp").val(IdAproveMkp)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data Aprove MKP");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#JabatanAprove").focus();
		$("#aksi").val("insert");

	}

}


function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = ["JabatanPembuatRKB","Keterangan"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Aprove MKP", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}
	

	var data = $("#FormData").serialize();
	$.ajax({
		type : "POST",
		url : "inc/SAM/proses.php?proses=Crud",
		data : data,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				sukses(aksi,"Aprove MKP",'003');
				Table.ajax.reload();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	});
	

}