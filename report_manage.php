<?php
include("inc/db.php");
include("inc/utility.php");
include("inc/session.php");
include("inc/paging.php");
define("APP_START", 1);
$filename = 'sales_manage.php';
include("inc/admin_type_access.php");
$tab_array=array("balance_sheet", "general_journal", "general_journal_print", "income", "income_print");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="general_journal";
}

switch($tab){
	case 'general_journal':
		include("modules/reports/general_journal_do.php");
	break;
	case 'general_journal_print':
		include("modules/reports/general_journal_print.php");
	break;
	case 'income_print':
		include("modules/reports/income_print.php");
	break;
}
?>
<?php include("inc/header.php");?>
  <div class="main-content-inner">
      <?php
		switch($tab){
			case 'balance_sheet': 
				include("modules/reports/balance_sheet.php");
			break;
			case 'general_journal':
				include("modules/reports/general_journal.php");
			break;
			case 'income':
				include("modules/reports/income.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("inc/footer.php");?>