$(document).ready(function(){
    Clear();
	LoadData();
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/RekapRKB/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "Periode" },
			{ "data" : "NamaPejabat" },
			{ "data" : "BobotA" ,"sClass" : "text-right"},
			{ "data" : "TargetA" ,"sClass" : "text-right"},
			{ "data" : "RealisasiA" ,"sClass" : "text-right"},
			{ "data" : "NilaiA" ,"sClass" : "text-right"},
			{ "data" : "BobotB" ,"sClass" : "text-right"},
			{ "data" : "TargetB" ,"sClass" : "text-right"},
			{ "data" : "RealisasiB" ,"sClass" : "text-right"},
			{ "data" : "NilaiB" ,"sClass" : "text-right"},
			{ "data" : "FinalSkor" ,"sClass" : "text-right"},
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}


function Clear(){
	$("#Title").html("Tampil Data Rekap RKB");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
}

function DetailData(Nik,Periode){
	jQuery("#modal").modal('show', {backdrop: 'static'});
	DetailPejabat(Nik);
	DetailKP(Nik,Periode);
	DetailKompetensi(Nik,Periode);
}

function DetailPejabat(Nik){
	$.ajax({
		type : "POST",
		dataType : "json",
		url : "inc/RekapRKB/proses.php?proses=DataPejabat",
		data : "Nik="+Nik,
		success: function(res){
			console.log(res);
			$("#Nama").html(res.Nama);
			$("#NIK").html(res.NIK);
			$("#TTL").html(res.TTL);
			$("#KJ").html(res.KJ);
			$("#NOHP").html(res.NOHP);
			$("#ALAMAT").html(res.ALAMAT);
		},
		error: function(er){
			console.log(er)
		}
	})
}

function DetailKP(Nik,Periode){
	$.ajax({
		type : "POST",
		dataType : "json",
		url : "inc/RekapRKB/proses.php?proses=DetailKP",
		data : "Nik="+Nik+"&Periode="+Periode,
		success: function(res){
			console.log(res);
			var DT = "";
			if(res.msg == "success"){
				for(var i=0; i < res.item.length; i++){
					DT += "<tr>";
						DT += "<td><center>"+res.item[i].No+"<center></td>";
						DT += "<td class='text-left'>"+res.item[i].RKB+"</td>";
						DT += "<td class='text-right'>"+res.item[i].Bobot+"</td>";
						DT += "<td class='text-right'>"+res.item[i].Target+"</td>";
						DT += "<td class='text-right'>"+res.item[i].Realisasi+"</td>";
						DT += "<td class='text-right'>"+res.item[i].Nilai+"</td>";
					DT += "</tr>";
				}
				DT += "<tr>";
					DT += "<td colspan='2' class='text-right'><b>TOTAL<b></td>";
					DT += "<td class='text-right'><b>"+res.TotBobot+"</b></td>";
					DT += "<td class='text-right'><b>"+res.TotTarget+"</b></td>";
					DT += "<td class='text-right'><b>"+res.TotRealisasi+"</b></td>";
					DT += "<td class='text-right'><b>"+res.TotNilai+"</b></td>";
				DT += "</tr>";
				$("#TMKP").html(DT);
			}
		},
		error: function(er){
			console.log(er)
		}
	})
}

function DetailKompetensi(Nik,Periode){
	$.ajax({
		type : "POST",
		dataType : "json",
		url : "inc/RekapRKB/proses.php?proses=DetailKonpetensi",
		data : "Nik="+Nik+"&Periode="+Periode,
		success: function(res){
			console.log(res);
			var DT = "";
			if(res.msg == "success"){
				for(var i=0; i < res.item.length; i++){
					DT += "<tr>";
						DT += "<td><center>"+res.item[i].No+"<center></td>";
						DT += "<td class='text-left'>"+res.item[i].Kompetensi+"</td>";
						DT += "<td class='text-right'>"+res.item[i].Bobot+"</td>";
						DT += "<td class='text-right'>"+res.item[i].Target+"</td>";
						DT += "<td class='text-right'>"+res.item[i].Realisasi+"</td>";
						DT += "<td class='text-right'>"+res.item[i].Nilai+"</td>";
					DT += "</tr>";
				}
				DT += "<tr>";
					DT += "<td colspan='2' class='text-right'><b>TOTAL<b></td>";
					DT += "<td class='text-right'><b>"+res.TotBobot+"</b></td>";
					DT += "<td class='text-right'><b>"+res.TotTarget+"</b></td>";
					DT += "<td class='text-right'><b>"+res.TotRealisasi+"</b></td>";
					DT += "<td class='text-right'><b>"+res.TotNilai+"</b></td>";
				DT += "</tr>";
				$("#TKOMPTENSI").html(DT);
			}
		},
		error: function(er){
			console.log(er)
		}
	})
}