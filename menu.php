<?php 
$pages = isset($_GET['page']) ? $_GET['page'] : null;
$MenuAc = $db->query("SELECT Position, Direktori FROM tb_menu WHERE Direktori = '$pages'")->fetch(PDO::FETCH_ASSOC);
echo "<ul class='sidebar-menu' data-widget='tree'>";
    echo "<li class='header'>MAIN NAVIGATION</li>";
    $actParent = $pages == "" ? "class='active'" : "";
    echo "<li $actParent><a href='index.php'><i class='fa fa-dashboard'></i> <span>Dashboard</span></a></li>";
    $sqlParent = "SELECT tb_menu.Direktori, tb_menu.NamaMenu, tb_menu.Icon FROM tb_menu INNER JOIN tb_menu_akses ON tb_menu.IdMenu = tb_menu_akses.IdMenu WHERE tb_menu.Status = '1' AND tb_menu.Position = 'item-root' AND tb_menu_akses.IdUser = '$_SESSION[IdUser]' GROUP BY tb_menu.IdMenu ORDER BY tb_menu.IdMenu ASC";
    $queryParent = $db->query($sqlParent);
    while($resParent = $queryParent->fetch(PDO::FETCH_ASSOC)){
        $queryMenu = $db->query("SELECT tb_menu.Direktori, tb_menu.NamaMenu, tb_menu.Icon FROM tb_menu INNER JOIN tb_menu_akses ON tb_menu.IdMenu = tb_menu_akses.IdMenu  WHERE tb_menu.Status = '1' AND tb_menu.Position = '$resParent[NamaMenu]' AND tb_menu_akses.IdUser = '$_SESSION[IdUser]'  GROUP BY tb_menu.IdMenu ORDER BY tb_menu.IdMenu");
        $JumMenu = $queryMenu->rowCount();
        if($JumMenu > 0){
            $actUtama = $MenuAc['Position'] == $resParent['NamaMenu'] ? "active" : "";
            echo "<li class='treeview $actUtama'>";
                echo "<a href='#'>
                        <i class='fa ".$resParent['Icon']."'></i> <span>".$resParent['NamaMenu']."</span>
                        <span class='pull-right-container'>
                        <i class='fa fa-angle-left pull-right'></i>
                        </span>
                    </a>";
                    echo "<ul class='treeview-menu'>";
                            while($resMenu = $queryMenu->fetch(PDO::FETCH_ASSOC)){
                                $actParent = $MenuAc['Direktori'] == $resMenu['Direktori'] ? "class='active'" : "";
                                echo "<li $actParent><a href='index.php?page=".$resMenu['Direktori']."'><i class='fa ".$resMenu['Icon']."'></i> ".$resMenu['NamaMenu']."</a></li>";
                            }
                    echo "</ul>";
            echo "</li>";
        }else{
            $actUtama = $pages == $resParent['Direktori'] ? "class='active'" : "";
            echo "<li $actUtama><a href='index.php?page=".$resParent['Direktori']."'><i class='fa ".$resParent['Icon']."'></i> <span>".$resParent['NamaMenu']."</span></a></li>";
        }
        
    }
    if($_SESSION['Level'] == "admin"){
        $actParent = $pages == "MainMenu" ? "class='active'" : "";
        echo "<li $actParent><a href='index.php?page=MainMenu'><i class='fa fa-list'></i> <span>Menu</span></a></li>";
    }
echo "</ul>";

?>