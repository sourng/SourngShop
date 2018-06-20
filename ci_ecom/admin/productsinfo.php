<?php

// Global variable for table object
$products = NULL;

//
// Table class for products
//
class cproducts extends cTable {
	var $product_id;
	var $product_brand;
	var $product_cat;
	var $product_title;
	var $product_price;
	var $product_dist;
	var $product_desc;
	var $product_image;
	var $product_image1;
	var $product_image2;
	var $product_image3;
	var $top_sell;
	var $condition;
	var $product_keywords;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'products';
		$this->TableName = 'products';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`products`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = TRUE; // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// product_id
		$this->product_id = new cField('products', 'products', 'x_product_id', 'product_id', '`product_id`', '`product_id`', 3, -1, FALSE, '`product_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->product_id->Sortable = TRUE; // Allow sort
		$this->product_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['product_id'] = &$this->product_id;

		// product_brand
		$this->product_brand = new cField('products', 'products', 'x_product_brand', 'product_brand', '`product_brand`', '`product_brand`', 3, -1, FALSE, '`EV__product_brand`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->product_brand->Sortable = TRUE; // Allow sort
		$this->product_brand->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->product_brand->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->product_brand->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['product_brand'] = &$this->product_brand;

		// product_cat
		$this->product_cat = new cField('products', 'products', 'x_product_cat', 'product_cat', '`product_cat`', '`product_cat`', 3, -1, FALSE, '`EV__product_cat`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->product_cat->Sortable = TRUE; // Allow sort
		$this->product_cat->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->product_cat->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->product_cat->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['product_cat'] = &$this->product_cat;

		// product_title
		$this->product_title = new cField('products', 'products', 'x_product_title', 'product_title', '`product_title`', '`product_title`', 200, -1, FALSE, '`product_title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->product_title->Sortable = TRUE; // Allow sort
		$this->fields['product_title'] = &$this->product_title;

		// product_price
		$this->product_price = new cField('products', 'products', 'x_product_price', 'product_price', '`product_price`', '`product_price`', 131, -1, FALSE, '`product_price`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->product_price->Sortable = TRUE; // Allow sort
		$this->product_price->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['product_price'] = &$this->product_price;

		// product_dist
		$this->product_dist = new cField('products', 'products', 'x_product_dist', 'product_dist', '`product_dist`', '`product_dist`', 131, -1, FALSE, '`product_dist`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->product_dist->Sortable = TRUE; // Allow sort
		$this->product_dist->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['product_dist'] = &$this->product_dist;

		// product_desc
		$this->product_desc = new cField('products', 'products', 'x_product_desc', 'product_desc', '`product_desc`', '`product_desc`', 201, -1, FALSE, '`product_desc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->product_desc->Sortable = TRUE; // Allow sort
		$this->fields['product_desc'] = &$this->product_desc;

		// product_image
		$this->product_image = new cField('products', 'products', 'x_product_image', 'product_image', '`product_image`', '`product_image`', 201, -1, TRUE, '`product_image`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->product_image->Sortable = TRUE; // Allow sort
		$this->product_image->ImageResize = TRUE;
		$this->fields['product_image'] = &$this->product_image;

		// product_image1
		$this->product_image1 = new cField('products', 'products', 'x_product_image1', 'product_image1', '`product_image1`', '`product_image1`', 201, -1, TRUE, '`product_image1`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->product_image1->Sortable = TRUE; // Allow sort
		$this->product_image1->ImageResize = TRUE;
		$this->fields['product_image1'] = &$this->product_image1;

		// product_image2
		$this->product_image2 = new cField('products', 'products', 'x_product_image2', 'product_image2', '`product_image2`', '`product_image2`', 201, -1, TRUE, '`product_image2`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->product_image2->Sortable = TRUE; // Allow sort
		$this->product_image2->ImageResize = TRUE;
		$this->fields['product_image2'] = &$this->product_image2;

		// product_image3
		$this->product_image3 = new cField('products', 'products', 'x_product_image3', 'product_image3', '`product_image3`', '`product_image3`', 201, -1, TRUE, '`product_image3`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->product_image3->Sortable = TRUE; // Allow sort
		$this->product_image3->ImageResize = TRUE;
		$this->fields['product_image3'] = &$this->product_image3;

		// top_sell
		$this->top_sell = new cField('products', 'products', 'x_top_sell', 'top_sell', '`top_sell`', '`top_sell`', 16, -1, FALSE, '`top_sell`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->top_sell->Sortable = TRUE; // Allow sort
		$this->top_sell->OptionCount = 2;
		$this->top_sell->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['top_sell'] = &$this->top_sell;

		// condition
		$this->condition = new cField('products', 'products', 'x_condition', 'condition', '`condition`', '`condition`', 200, -1, FALSE, '`condition`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->condition->Sortable = TRUE; // Allow sort
		$this->condition->OptionCount = 2;
		$this->fields['condition'] = &$this->condition;

		// product_keywords
		$this->product_keywords = new cField('products', 'products', 'x_product_keywords', 'product_keywords', '`product_keywords`', '`product_keywords`', 201, -1, FALSE, '`product_keywords`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->product_keywords->Sortable = TRUE; // Allow sort
		$this->fields['product_keywords'] = &$this->product_keywords;
	}

	// Field Visibility
	function GetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Column CSS classes
	var $LeftColumnClass = "col-sm-2 control-label ewLabel";
	var $RightColumnClass = "col-sm-10";
	var $OffsetColumnClass = "col-sm-10 col-sm-offset-2";

	// Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
	function SetLeftColumnClass($class) {
		if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
			$this->LeftColumnClass = $class . " control-label ewLabel";
			$this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - intval($match[2]));
			$this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace($match[1], $match[1] + "-offset", $class);
		}
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`products`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlSelectList = "";

	function getSqlSelectList() { // Select for List page
		$select = "";
		$select = "SELECT * FROM (" .
			"SELECT *, (SELECT DISTINCT `brand_title` FROM `brands` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`brand_id` = `products`.`product_brand` LIMIT 1) AS `EV__product_brand`, (SELECT `cat_title` FROM `categories` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`cat_id` = `products`.`product_cat` LIMIT 1) AS `EV__product_cat` FROM `products`" .
			") `EW_TMP_TABLE`";
		return ($this->_SqlSelectList <> "") ? $this->_SqlSelectList : $select;
	}

	function SqlSelectList() { // For backward compatibility
		return $this->getSqlSelectList();
	}

	function setSqlSelectList($v) {
		$this->_SqlSelectList = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$filter = $this->CurrentFilter;
		$filter = $this->ApplyUserIDFilters($filter);
		$sort = $this->getSessionOrderBy();
		return $this->GetSQL($filter, $sort);
	}

	// Table SQL with List page filter
	var $UseSessionForListSQL = TRUE;

	function ListSQL() {
		$sFilter = $this->UseSessionForListSQL ? $this->getSessionWhere() : "";
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		if ($this->UseVirtualFields()) {
			$sSelect = $this->getSqlSelectList();
			$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderByList() : "";
		} else {
			$sSelect = $this->getSqlSelect();
			$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		}
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->UseSessionForListSQL ? $this->getSessionWhere() : $this->CurrentFilter;
		$sOrderBy = $this->UseSessionForListSQL ? $this->getSessionOrderByList() : "";
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->product_brand->AdvancedSearch->SearchValue <> "" ||
			$this->product_brand->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->product_brand->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->product_brand->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->product_cat->AdvancedSearch->SearchValue <> "" ||
			$this->product_cat->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->product_cat->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->product_cat->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
	}

	// Try to get record count
	function TryGetRecordCount($sql) {
		$cnt = -1;
		$pattern = "/^SELECT \* FROM/i";
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match($pattern, $sql)) {
			$sql = "SELECT COUNT(*) FROM" . preg_replace($pattern, "", $sql);
		} else {
			$sql = "SELECT COUNT(*) FROM (" . $sql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($filter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $filter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function ListRecordCount() {
		$filter = $this->getSessionWhere();
		ew_AddFilter($filter, $this->CurrentFilter);
		$filter = $this->ApplyUserIDFilters($filter);
		$this->Recordset_Selecting($filter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		if ($this->UseVirtualFields())
			$sql = ew_BuildSelectSql($this->getSqlSelectList(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
		else
			$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$names = preg_replace('/,+$/', "", $names);
		$values = preg_replace('/,+$/', "", $values);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->product_id->setDbValue($conn->Insert_ID());
			$rs['product_id'] = $this->product_id->DbValue;
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$sql = preg_replace('/,+$/', "", $sql);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('product_id', $rs))
				ew_AddFilter($where, ew_QuotedName('product_id', $this->DBID) . '=' . ew_QuotedValue($rs['product_id'], $this->product_id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$bDelete = TRUE;
		$conn = &$this->Connection();
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`product_id` = @product_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->product_id->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->product_id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@product_id@", ew_AdjustSql($this->product_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "productslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "productsview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "productsedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "productsadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "productslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("productsview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("productsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "productsadd.php?" . $this->UrlParm($parm);
		else
			$url = "productsadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("productsedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("productsadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("productsdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "product_id:" . ew_VarToJson($this->product_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->product_id->CurrentValue)) {
			$sUrl .= "product_id=" . urlencode($this->product_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = $_POST["key_m"];
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();
			if ($isPost && isset($_POST["product_id"]))
				$arKeys[] = $_POST["product_id"];
			elseif (isset($_GET["product_id"]))
				$arKeys[] = $_GET["product_id"];
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->product_id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($filter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $filter;
		//$sql = $this->SQL();

		$sql = $this->GetSQL($filter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->product_id->setDbValue($rs->fields('product_id'));
		$this->product_brand->setDbValue($rs->fields('product_brand'));
		$this->product_cat->setDbValue($rs->fields('product_cat'));
		$this->product_title->setDbValue($rs->fields('product_title'));
		$this->product_price->setDbValue($rs->fields('product_price'));
		$this->product_dist->setDbValue($rs->fields('product_dist'));
		$this->product_desc->setDbValue($rs->fields('product_desc'));
		$this->product_image->Upload->DbValue = $rs->fields('product_image');
		$this->product_image1->Upload->DbValue = $rs->fields('product_image1');
		$this->product_image2->Upload->DbValue = $rs->fields('product_image2');
		$this->product_image3->Upload->DbValue = $rs->fields('product_image3');
		$this->top_sell->setDbValue($rs->fields('top_sell'));
		$this->condition->setDbValue($rs->fields('condition'));
		$this->product_keywords->setDbValue($rs->fields('product_keywords'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// product_id
		// product_brand
		// product_cat
		// product_title
		// product_price
		// product_dist
		// product_desc
		// product_image
		// product_image1
		// product_image2
		// product_image3
		// top_sell
		// condition
		// product_keywords
		// product_id

		$this->product_id->ViewValue = $this->product_id->CurrentValue;
		$this->product_id->ViewCustomAttributes = "";

		// product_brand
		if ($this->product_brand->VirtualValue <> "") {
			$this->product_brand->ViewValue = $this->product_brand->VirtualValue;
		} else {
		if (strval($this->product_brand->CurrentValue) <> "") {
			$sFilterWrk = "`brand_id`" . ew_SearchString("=", $this->product_brand->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT DISTINCT `brand_id`, `brand_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `brands`";
		$sWhereWrk = "";
		$this->product_brand->LookupFilters = array("dx1" => '`brand_title`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->product_brand, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->product_brand->ViewValue = $this->product_brand->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->product_brand->ViewValue = $this->product_brand->CurrentValue;
			}
		} else {
			$this->product_brand->ViewValue = NULL;
		}
		}
		$this->product_brand->ViewCustomAttributes = "";

		// product_cat
		if ($this->product_cat->VirtualValue <> "") {
			$this->product_cat->ViewValue = $this->product_cat->VirtualValue;
		} else {
		if (strval($this->product_cat->CurrentValue) <> "") {
			$sFilterWrk = "`cat_id`" . ew_SearchString("=", $this->product_cat->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `cat_id`, `cat_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categories`";
		$sWhereWrk = "";
		$this->product_cat->LookupFilters = array("dx1" => '`cat_title`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->product_cat, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->product_cat->ViewValue = $this->product_cat->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->product_cat->ViewValue = $this->product_cat->CurrentValue;
			}
		} else {
			$this->product_cat->ViewValue = NULL;
		}
		}
		$this->product_cat->ViewCustomAttributes = "";

		// product_title
		$this->product_title->ViewValue = $this->product_title->CurrentValue;
		$this->product_title->ViewCustomAttributes = "";

		// product_price
		$this->product_price->ViewValue = $this->product_price->CurrentValue;
		$this->product_price->ViewCustomAttributes = "";

		// product_dist
		$this->product_dist->ViewValue = $this->product_dist->CurrentValue;
		$this->product_dist->ViewCustomAttributes = "";

		// product_desc
		$this->product_desc->ViewValue = $this->product_desc->CurrentValue;
		$this->product_desc->ViewCustomAttributes = "";

		// product_image
		$this->product_image->UploadPath = "../uploads/product/";
		if (!ew_Empty($this->product_image->Upload->DbValue)) {
			$this->product_image->ImageWidth = 0;
			$this->product_image->ImageHeight = 94;
			$this->product_image->ImageAlt = $this->product_image->FldAlt();
			$this->product_image->ViewValue = $this->product_image->Upload->DbValue;
		} else {
			$this->product_image->ViewValue = "";
		}
		$this->product_image->ViewCustomAttributes = "";

		// product_image1
		$this->product_image1->UploadPath = "../uploads/product/";
		if (!ew_Empty($this->product_image1->Upload->DbValue)) {
			$this->product_image1->ImageWidth = EW_THUMBNAIL_DEFAULT_WIDTH;
			$this->product_image1->ImageHeight = EW_THUMBNAIL_DEFAULT_HEIGHT;
			$this->product_image1->ImageAlt = $this->product_image1->FldAlt();
			$this->product_image1->ViewValue = $this->product_image1->Upload->DbValue;
		} else {
			$this->product_image1->ViewValue = "";
		}
		$this->product_image1->ViewCustomAttributes = "";

		// product_image2
		$this->product_image2->UploadPath = "../uploads/product/";
		if (!ew_Empty($this->product_image2->Upload->DbValue)) {
			$this->product_image2->ImageWidth = EW_THUMBNAIL_DEFAULT_WIDTH;
			$this->product_image2->ImageHeight = EW_THUMBNAIL_DEFAULT_HEIGHT;
			$this->product_image2->ImageAlt = $this->product_image2->FldAlt();
			$this->product_image2->ViewValue = $this->product_image2->Upload->DbValue;
		} else {
			$this->product_image2->ViewValue = "";
		}
		$this->product_image2->ViewCustomAttributes = "";

		// product_image3
		$this->product_image3->UploadPath = "../uploads/product/";
		if (!ew_Empty($this->product_image3->Upload->DbValue)) {
			$this->product_image3->ImageWidth = EW_THUMBNAIL_DEFAULT_WIDTH;
			$this->product_image3->ImageHeight = EW_THUMBNAIL_DEFAULT_HEIGHT;
			$this->product_image3->ImageAlt = $this->product_image3->FldAlt();
			$this->product_image3->ViewValue = $this->product_image3->Upload->DbValue;
		} else {
			$this->product_image3->ViewValue = "";
		}
		$this->product_image3->ViewCustomAttributes = "";

		// top_sell
		if (strval($this->top_sell->CurrentValue) <> "") {
			$this->top_sell->ViewValue = $this->top_sell->OptionCaption($this->top_sell->CurrentValue);
		} else {
			$this->top_sell->ViewValue = NULL;
		}
		$this->top_sell->ViewCustomAttributes = "";

		// condition
		if (strval($this->condition->CurrentValue) <> "") {
			$this->condition->ViewValue = $this->condition->OptionCaption($this->condition->CurrentValue);
		} else {
			$this->condition->ViewValue = NULL;
		}
		$this->condition->ViewCustomAttributes = "";

		// product_keywords
		$this->product_keywords->ViewValue = $this->product_keywords->CurrentValue;
		$this->product_keywords->ViewCustomAttributes = "";

		// product_id
		$this->product_id->LinkCustomAttributes = "";
		$this->product_id->HrefValue = "";
		$this->product_id->TooltipValue = "";

		// product_brand
		$this->product_brand->LinkCustomAttributes = "";
		$this->product_brand->HrefValue = "";
		$this->product_brand->TooltipValue = "";

		// product_cat
		$this->product_cat->LinkCustomAttributes = "";
		$this->product_cat->HrefValue = "";
		$this->product_cat->TooltipValue = "";

		// product_title
		$this->product_title->LinkCustomAttributes = "";
		$this->product_title->HrefValue = "";
		$this->product_title->TooltipValue = "";

		// product_price
		$this->product_price->LinkCustomAttributes = "";
		$this->product_price->HrefValue = "";
		$this->product_price->TooltipValue = "";

		// product_dist
		$this->product_dist->LinkCustomAttributes = "";
		$this->product_dist->HrefValue = "";
		$this->product_dist->TooltipValue = "";

		// product_desc
		$this->product_desc->LinkCustomAttributes = "";
		$this->product_desc->HrefValue = "";
		$this->product_desc->TooltipValue = "";

		// product_image
		$this->product_image->LinkCustomAttributes = "";
		$this->product_image->UploadPath = "../uploads/product/";
		if (!ew_Empty($this->product_image->Upload->DbValue)) {
			$this->product_image->HrefValue = ew_GetFileUploadUrl($this->product_image, $this->product_image->Upload->DbValue); // Add prefix/suffix
			$this->product_image->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->product_image->HrefValue = ew_FullUrl($this->product_image->HrefValue, "href");
		} else {
			$this->product_image->HrefValue = "";
		}
		$this->product_image->HrefValue2 = $this->product_image->UploadPath . $this->product_image->Upload->DbValue;
		$this->product_image->TooltipValue = "";
		if ($this->product_image->UseColorbox) {
			if (ew_Empty($this->product_image->TooltipValue))
				$this->product_image->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->product_image->LinkAttrs["data-rel"] = "products_x_product_image";
			ew_AppendClass($this->product_image->LinkAttrs["class"], "ewLightbox");
		}

		// product_image1
		$this->product_image1->LinkCustomAttributes = "";
		$this->product_image1->UploadPath = "../uploads/product/";
		if (!ew_Empty($this->product_image1->Upload->DbValue)) {
			$this->product_image1->HrefValue = ew_GetFileUploadUrl($this->product_image1, $this->product_image1->Upload->DbValue); // Add prefix/suffix
			$this->product_image1->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->product_image1->HrefValue = ew_FullUrl($this->product_image1->HrefValue, "href");
		} else {
			$this->product_image1->HrefValue = "";
		}
		$this->product_image1->HrefValue2 = $this->product_image1->UploadPath . $this->product_image1->Upload->DbValue;
		$this->product_image1->TooltipValue = "";
		if ($this->product_image1->UseColorbox) {
			if (ew_Empty($this->product_image1->TooltipValue))
				$this->product_image1->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->product_image1->LinkAttrs["data-rel"] = "products_x_product_image1";
			ew_AppendClass($this->product_image1->LinkAttrs["class"], "ewLightbox");
		}

		// product_image2
		$this->product_image2->LinkCustomAttributes = "";
		$this->product_image2->UploadPath = "../uploads/product/";
		if (!ew_Empty($this->product_image2->Upload->DbValue)) {
			$this->product_image2->HrefValue = ew_GetFileUploadUrl($this->product_image2, $this->product_image2->Upload->DbValue); // Add prefix/suffix
			$this->product_image2->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->product_image2->HrefValue = ew_FullUrl($this->product_image2->HrefValue, "href");
		} else {
			$this->product_image2->HrefValue = "";
		}
		$this->product_image2->HrefValue2 = $this->product_image2->UploadPath . $this->product_image2->Upload->DbValue;
		$this->product_image2->TooltipValue = "";
		if ($this->product_image2->UseColorbox) {
			if (ew_Empty($this->product_image2->TooltipValue))
				$this->product_image2->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->product_image2->LinkAttrs["data-rel"] = "products_x_product_image2";
			ew_AppendClass($this->product_image2->LinkAttrs["class"], "ewLightbox");
		}

		// product_image3
		$this->product_image3->LinkCustomAttributes = "";
		$this->product_image3->UploadPath = "../uploads/product/";
		if (!ew_Empty($this->product_image3->Upload->DbValue)) {
			$this->product_image3->HrefValue = ew_GetFileUploadUrl($this->product_image3, $this->product_image3->Upload->DbValue); // Add prefix/suffix
			$this->product_image3->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->product_image3->HrefValue = ew_FullUrl($this->product_image3->HrefValue, "href");
		} else {
			$this->product_image3->HrefValue = "";
		}
		$this->product_image3->HrefValue2 = $this->product_image3->UploadPath . $this->product_image3->Upload->DbValue;
		$this->product_image3->TooltipValue = "";
		if ($this->product_image3->UseColorbox) {
			if (ew_Empty($this->product_image3->TooltipValue))
				$this->product_image3->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->product_image3->LinkAttrs["data-rel"] = "products_x_product_image3";
			ew_AppendClass($this->product_image3->LinkAttrs["class"], "ewLightbox");
		}

		// top_sell
		$this->top_sell->LinkCustomAttributes = "";
		$this->top_sell->HrefValue = "";
		$this->top_sell->TooltipValue = "";

		// condition
		$this->condition->LinkCustomAttributes = "";
		$this->condition->HrefValue = "";
		$this->condition->TooltipValue = "";

		// product_keywords
		$this->product_keywords->LinkCustomAttributes = "";
		$this->product_keywords->HrefValue = "";
		$this->product_keywords->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();

		// Save data for Custom Template
		$this->Rows[] = $this->CustomTemplateFieldValues();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// product_id
		$this->product_id->EditAttrs["class"] = "form-control";
		$this->product_id->EditCustomAttributes = "";
		$this->product_id->EditValue = $this->product_id->CurrentValue;
		$this->product_id->ViewCustomAttributes = "";

		// product_brand
		$this->product_brand->EditAttrs["class"] = "form-control";
		$this->product_brand->EditCustomAttributes = "";

		// product_cat
		$this->product_cat->EditAttrs["class"] = "form-control";
		$this->product_cat->EditCustomAttributes = "";

		// product_title
		$this->product_title->EditAttrs["class"] = "form-control";
		$this->product_title->EditCustomAttributes = "";
		$this->product_title->EditValue = $this->product_title->CurrentValue;
		$this->product_title->PlaceHolder = ew_RemoveHtml($this->product_title->FldCaption());

		// product_price
		$this->product_price->EditAttrs["class"] = "form-control";
		$this->product_price->EditCustomAttributes = "";
		$this->product_price->EditValue = $this->product_price->CurrentValue;
		$this->product_price->PlaceHolder = ew_RemoveHtml($this->product_price->FldCaption());
		if (strval($this->product_price->EditValue) <> "" && is_numeric($this->product_price->EditValue)) $this->product_price->EditValue = ew_FormatNumber($this->product_price->EditValue, -2, -1, -2, 0);

		// product_dist
		$this->product_dist->EditAttrs["class"] = "form-control";
		$this->product_dist->EditCustomAttributes = "";
		$this->product_dist->EditValue = $this->product_dist->CurrentValue;
		$this->product_dist->PlaceHolder = ew_RemoveHtml($this->product_dist->FldCaption());
		if (strval($this->product_dist->EditValue) <> "" && is_numeric($this->product_dist->EditValue)) $this->product_dist->EditValue = ew_FormatNumber($this->product_dist->EditValue, -2, -1, -2, 0);

		// product_desc
		$this->product_desc->EditAttrs["class"] = "form-control";
		$this->product_desc->EditCustomAttributes = "";
		$this->product_desc->EditValue = $this->product_desc->CurrentValue;
		$this->product_desc->PlaceHolder = ew_RemoveHtml($this->product_desc->FldCaption());

		// product_image
		$this->product_image->EditAttrs["class"] = "form-control";
		$this->product_image->EditCustomAttributes = "";
		$this->product_image->UploadPath = "../uploads/product/";
		if (!ew_Empty($this->product_image->Upload->DbValue)) {
			$this->product_image->ImageWidth = 0;
			$this->product_image->ImageHeight = 94;
			$this->product_image->ImageAlt = $this->product_image->FldAlt();
			$this->product_image->EditValue = $this->product_image->Upload->DbValue;
		} else {
			$this->product_image->EditValue = "";
		}
		if (!ew_Empty($this->product_image->CurrentValue))
				$this->product_image->Upload->FileName = $this->product_image->CurrentValue;

		// product_image1
		$this->product_image1->EditAttrs["class"] = "form-control";
		$this->product_image1->EditCustomAttributes = "";
		$this->product_image1->UploadPath = "../uploads/product/";
		if (!ew_Empty($this->product_image1->Upload->DbValue)) {
			$this->product_image1->ImageWidth = EW_THUMBNAIL_DEFAULT_WIDTH;
			$this->product_image1->ImageHeight = EW_THUMBNAIL_DEFAULT_HEIGHT;
			$this->product_image1->ImageAlt = $this->product_image1->FldAlt();
			$this->product_image1->EditValue = $this->product_image1->Upload->DbValue;
		} else {
			$this->product_image1->EditValue = "";
		}
		if (!ew_Empty($this->product_image1->CurrentValue))
				$this->product_image1->Upload->FileName = $this->product_image1->CurrentValue;

		// product_image2
		$this->product_image2->EditAttrs["class"] = "form-control";
		$this->product_image2->EditCustomAttributes = "";
		$this->product_image2->UploadPath = "../uploads/product/";
		if (!ew_Empty($this->product_image2->Upload->DbValue)) {
			$this->product_image2->ImageWidth = EW_THUMBNAIL_DEFAULT_WIDTH;
			$this->product_image2->ImageHeight = EW_THUMBNAIL_DEFAULT_HEIGHT;
			$this->product_image2->ImageAlt = $this->product_image2->FldAlt();
			$this->product_image2->EditValue = $this->product_image2->Upload->DbValue;
		} else {
			$this->product_image2->EditValue = "";
		}
		if (!ew_Empty($this->product_image2->CurrentValue))
				$this->product_image2->Upload->FileName = $this->product_image2->CurrentValue;

		// product_image3
		$this->product_image3->EditAttrs["class"] = "form-control";
		$this->product_image3->EditCustomAttributes = "";
		$this->product_image3->UploadPath = "../uploads/product/";
		if (!ew_Empty($this->product_image3->Upload->DbValue)) {
			$this->product_image3->ImageWidth = EW_THUMBNAIL_DEFAULT_WIDTH;
			$this->product_image3->ImageHeight = EW_THUMBNAIL_DEFAULT_HEIGHT;
			$this->product_image3->ImageAlt = $this->product_image3->FldAlt();
			$this->product_image3->EditValue = $this->product_image3->Upload->DbValue;
		} else {
			$this->product_image3->EditValue = "";
		}
		if (!ew_Empty($this->product_image3->CurrentValue))
				$this->product_image3->Upload->FileName = $this->product_image3->CurrentValue;

		// top_sell
		$this->top_sell->EditCustomAttributes = "";
		$this->top_sell->EditValue = $this->top_sell->Options(FALSE);

		// condition
		$this->condition->EditCustomAttributes = "";
		$this->condition->EditValue = $this->condition->Options(FALSE);

		// product_keywords
		$this->product_keywords->EditAttrs["class"] = "form-control";
		$this->product_keywords->EditCustomAttributes = "";
		$this->product_keywords->EditValue = $this->product_keywords->CurrentValue;
		$this->product_keywords->PlaceHolder = ew_RemoveHtml($this->product_keywords->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->product_id->Exportable) $Doc->ExportCaption($this->product_id);
					if ($this->product_brand->Exportable) $Doc->ExportCaption($this->product_brand);
					if ($this->product_cat->Exportable) $Doc->ExportCaption($this->product_cat);
					if ($this->product_title->Exportable) $Doc->ExportCaption($this->product_title);
					if ($this->product_price->Exportable) $Doc->ExportCaption($this->product_price);
					if ($this->product_dist->Exportable) $Doc->ExportCaption($this->product_dist);
					if ($this->product_desc->Exportable) $Doc->ExportCaption($this->product_desc);
					if ($this->product_image->Exportable) $Doc->ExportCaption($this->product_image);
					if ($this->product_image1->Exportable) $Doc->ExportCaption($this->product_image1);
					if ($this->product_image2->Exportable) $Doc->ExportCaption($this->product_image2);
					if ($this->product_image3->Exportable) $Doc->ExportCaption($this->product_image3);
					if ($this->top_sell->Exportable) $Doc->ExportCaption($this->top_sell);
					if ($this->condition->Exportable) $Doc->ExportCaption($this->condition);
					if ($this->product_keywords->Exportable) $Doc->ExportCaption($this->product_keywords);
				} else {
					if ($this->product_id->Exportable) $Doc->ExportCaption($this->product_id);
					if ($this->product_brand->Exportable) $Doc->ExportCaption($this->product_brand);
					if ($this->product_cat->Exportable) $Doc->ExportCaption($this->product_cat);
					if ($this->product_title->Exportable) $Doc->ExportCaption($this->product_title);
					if ($this->product_price->Exportable) $Doc->ExportCaption($this->product_price);
					if ($this->product_dist->Exportable) $Doc->ExportCaption($this->product_dist);
					if ($this->top_sell->Exportable) $Doc->ExportCaption($this->top_sell);
					if ($this->condition->Exportable) $Doc->ExportCaption($this->condition);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->product_id->Exportable) $Doc->ExportField($this->product_id);
						if ($this->product_brand->Exportable) $Doc->ExportField($this->product_brand);
						if ($this->product_cat->Exportable) $Doc->ExportField($this->product_cat);
						if ($this->product_title->Exportable) $Doc->ExportField($this->product_title);
						if ($this->product_price->Exportable) $Doc->ExportField($this->product_price);
						if ($this->product_dist->Exportable) $Doc->ExportField($this->product_dist);
						if ($this->product_desc->Exportable) $Doc->ExportField($this->product_desc);
						if ($this->product_image->Exportable) $Doc->ExportField($this->product_image);
						if ($this->product_image1->Exportable) $Doc->ExportField($this->product_image1);
						if ($this->product_image2->Exportable) $Doc->ExportField($this->product_image2);
						if ($this->product_image3->Exportable) $Doc->ExportField($this->product_image3);
						if ($this->top_sell->Exportable) $Doc->ExportField($this->top_sell);
						if ($this->condition->Exportable) $Doc->ExportField($this->condition);
						if ($this->product_keywords->Exportable) $Doc->ExportField($this->product_keywords);
					} else {
						if ($this->product_id->Exportable) $Doc->ExportField($this->product_id);
						if ($this->product_brand->Exportable) $Doc->ExportField($this->product_brand);
						if ($this->product_cat->Exportable) $Doc->ExportField($this->product_cat);
						if ($this->product_title->Exportable) $Doc->ExportField($this->product_title);
						if ($this->product_price->Exportable) $Doc->ExportField($this->product_price);
						if ($this->product_dist->Exportable) $Doc->ExportField($this->product_dist);
						if ($this->top_sell->Exportable) $Doc->ExportField($this->top_sell);
						if ($this->condition->Exportable) $Doc->ExportField($this->condition);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
