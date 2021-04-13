$(document).ready(function(){
    Clear();
	LoadData();
	
});

function LoadData(){
	$("#TableData").DataTable({
		"ordering": false,
		"ajax" : "inc/Users/proses.php?proses=DetailData",
		"columns" : [
			{ "data" : "No" ,"sClass" : "text-center", "sWidth" : "5px"},
			{ "data" : "NamaUser" },
			{ "data" : "Username"},
			{ "data" : "Level", "sWidth" : "10%" },
			{ "data" : "Aksi", "sClass" : "text-center" }
		],
		
    	"drawCallback": function( settings ) {
			$('[data-toggle="tooltip"]').tooltip();
		}
	});


}

function LoadAksesMenu(IdUser){
	IdUser = IdUser != undefined ? IdUser : "";
	$.ajax({
		type: "POST",
		url : "inc/Users/proses.php?proses=LoadAksesMenu",
		data : "IdUser="+IdUser,
		success: function(data){
			console.log(data);
			$("#ShowAksesMenu").html(data);
		}
	});
}

function PilihSubMenu(IdMenu){
	var cek = $(".CountMenu").filter(':checked').length;
	if(cek > 0){
		$("#Menu"+IdMenu).prop("checked",true);
	}else{
		$("#Menu" + IdMenu).prop("checked", false);
	}
	
}

function PilihMenu(IdMenu){
	if($("#Menu"+IdMenu).is(':checked')){
		$(".Menu"+IdMenu).prop("checked",true);
	}else{
		$(".Menu" + IdMenu).prop("checked", false);
	}
}

$("#SetPassword").click(function(){
	var id = $(this).attr("data-id");
	if(id == "0"){
		$(this).removeClass('btn-primary').addClass('btn-danger').html("<i class='fa fa-eye-slash'><i>").attr('data-id','1');
		$("#Password").attr("type","text");
	}else{
		$(this).removeClass('btn-danger').addClass('btn-primary').html("<i class='fa fa-eye'><i>").attr('data-id', '0');
		$("#Password").attr("type", "password");
	}
});


function Clear(){
	$("#Title").html("Tampil Data Users");
	$("#close_modal").trigger('click');
	$("#FormData").hide();
	$("#DetailData").show();
	$("#aksi").val("");
	var iForm = ["aksi","IdUser","NamaUser","Level","Username","Password"];
	for (var i = 0; i < iForm.length; i++) {
		$("#"+iForm[i]).val('');
	}
	
}

function Crud(IdUser,Status){
	Clear();
	if(IdUser){
		if(Status == "ubah"){
			$.ajax({
				type : "POST",
				dataType : "json",
				url : "inc/Users/proses.php?proses=ShowData",
				data : "IdUser="+IdUser,
				beforeSend : function(data){
					StartLoad();
				},
				success: function(data){
					console.log(data);
					$("#Title").html("Ubah Data Users");
					$("#FormData").show();
					$("#DetailData").hide();
					$("#aksi").val("update");

					LoadAksesMenu(data.IdUser);
					$("#IdUser").val(data.IdUser);
					$("#NamaUser").val(data.NamaUser);
					$("#Username").val(data.UserName);
					$("#Level").val(data.Level);
					StopLoad();
				},
				error: function(er){
					console.log(er);
				}
			})
		}else{
			jQuery("#modal").modal('show', {backdrop: 'static'});
			$("#aksi").val('delete');
			$("#IdUser").val(IdUser)
			$("#proses_del").html("<div class='alert alert-danger'>Apakah anda yakin ingin menghapus data ini ?</div>");
		}
	}else{
		$("#Title").html("Tambah Data Users");
		$("#FormData").show();
		$("#DetailData").hide();
		LoadAksesMenu();
		$("#NamaLengkap").focus();
		$("#aksi").val("insert");

	}

}


function SubmitData(){
	var aksi = $("#aksi").val();
	var iForm = ["NamaUser", "Level","Username"];
	var KodeError = 1;
	for (var i = 0; i < iForm.length; i++) {
		if(aksi != "delete"){
			if($("#"+iForm[i]).val() == ""){ error("Users", KodeError + i, iForm[i]+" Belum Lengkap!"); $("#"+iForm[i]).focus(); return false; }
		}
	}
	if(aksi != "delete" && aksi != "update"){
		if($("#Password").val() == ""){ error("Users", 4, "Password Belum Lengkap!"); $("#Password").focus(); return false; }
	}
	if(aksi != "delete"){
		var cek = $(".Menu").filter(':checked').length;
		if (cek <= 0) { error("Users", 5, "Akses Menu Belum Dipilih!");  return false; }
	}

	
	

	var data = $("#FormData").serialize();
	$.ajax({
		type : "POST",
		url : "inc/Users/proses.php?proses=Crud",
		data : data,
		beforeSend: function() {
        	StartLoad();
    	},
		success: function(result){
			console.log(result);
			var Table = $("#TableData").DataTable();
			if(result == "sukses"){
				Clear();
				sukses(aksi,"Users",'002');
				Table.ajax.reload();
				StopLoad();
			} else if(result == "ada"){
				error("Users", 3, "No Akun Sudah Ada!"); $("#Username").focus();
				StopLoad();
			}
		},
		error : function(er){
			console.log(er);
		}
	});
	

}