$(document).ready(function(){
    Clear();
   	LoadData();
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/MainMenu/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "NamaMenu" },
			{ "data" : "Direktori" },
			{ "data" : "Position"},
			{ "data" : "Icon", "sWidth": "5%", "sClass": "text-center" },
			{ "data" : "Status", "sWidth" : "5%" , "sClass" : "text-center"},
			{ "data" : "Aksi", "sClass" : "text-center", "sWidth" : "10%" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});


}

function GetParentMenu(Position){
	Position = Position != undefined ? Position : "-";
	$.ajax({
		type : "GET",
		dataType : "json",
		url: "inc/MainMenu/proses.php",
		data: "proses=GetParentMenu",
		beforeSend : function(){
			StartLoad();
		},
		success: function(result){
			console.log(result);
			var option = "<option value=''>:: Pilih Parent Menu ::</option>";
			if(result.row > 0){
				var filter = Position != "-" ? "selected" : "";
				option += "<option value='item-root' "+filter+">Menu Item Root</option>";
				var res = result.item;
				for(var i=0; i<res.length; i++){
					filter = res[i].NamaMenu == Position ? "selected" : ""; 
					option += "<option value='" + res[i].NamaMenu + "' " + filter+">"+res[i].NamaMenu+"</option>";
				}
			}else{
				option += "<option value='item-root'>Menu Item Root</option>";
			}
			$("#ShowParentMenu").html(
				"<select class='form-control' id='Position' name='Position'>"
				+option
				+"</select>"
			);
			StopLoad();
		},
		error : function(er){
			console.log(er);
		}
	})
}

function ShowIcon(e){
	$("#ShowIcon").find("i").attr("class", "fa ");
	if(e.keyCode == 13){
		var icon = $("#Icon").val();
		$("#ShowIcon").find("i").attr("class","fa "+icon);
	}
}

function UpdateStatus(IdMenu,Status){
	St = Status == "0" ? "Ditutup" : "Dibuka";
	$.ajax({
		type : "POST",
		url: "inc/MainMenu/proses.php?proses=UpdateMenu",
		data : "IdMenu="+IdMenu+"&Status="+Status,
		beforeSend : function(){
			StartLoad();
		},
		success : function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				Customsukses("Menu", "002", "Menu Berhasil " + St, "proses");
				Table.ajax.reload();
				StopLoad();
			}
		},
		error: function(er){
			console.log(er);
		}
	})
}

function Clear(){
	$("#Title").html("Tampil Data Menu");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi","IdMenu","NamaMenu","Direktori","Icon","Position"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
	
}

function Crud(IdMenu,Status){
	Clear();
	if(IdMenu){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url: "inc/MainMenu/proses.php?proses=ShowData",
				data : "IdMenu="+IdMenu,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Menu");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					$("#IdMenu").val(data.IdMenu);
					$("#NamaMenu").val(data.NamaMenu);
					$("#Direktori").val(data.Direktori);
					$("#Icon").val(data.Icon);
					$("#ShowIcon").find('i').attr("class","fa "+data.Icon);
					GetParentMenu(data.Position);
					$("#NamaMenu").focus();
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#IdMenu").val(IdMenu)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		GetParentMenu();
		$("#Title").html("Tambah Data Menu");
		$("#FormData").show();
		$("#DetailData").hide();
		$("#NamaLengkap").focus();
		$("#NamaMenu").focus();
		$("#aksi").val("insert");

	}

}


function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = ["NamaMenu", "Direktori", "Icon", "Position"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Menu", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}
	
	

	var data = $("#FormData").serialize();
	$.ajax({
		type : "POST",
		url: "inc/MainMenu/proses.php?proses=Crud",
		data : data,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				sukses(aksi,"Menu",'002');
				Table.ajax.reload();
				StopLoad();
			} else if(result == "ada"){
				error("Menu", 3, "Menu Sudah Ada!"); $("#Username").focus();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	});
	

}