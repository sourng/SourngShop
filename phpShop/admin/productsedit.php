<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "productsinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$products_edit = NULL; // Initialize page object first

class cproducts_edit extends cproducts {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{DE3D48ED-60FB-4F03-A5AD-139B7A8A8A85}';

	// Table name
	var $TableName = 'products';

	// Page object name
	var $PageObjName = 'products_edit';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $Language;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $Language;
		if ($this->Subheading <> "")
			return $this->Subheading;
		if ($this->TableName)
			return $Language->Phrase($this->PageID);
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (products)
		if (!isset($GLOBALS["products"]) || get_class($GLOBALS["products"]) == "cproducts") {
			$GLOBALS["products"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["products"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'products', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Is modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("productslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->product_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->product_id->Visible = FALSE;
		$this->product_cat->SetVisibility();
		$this->product_brand->SetVisibility();
		$this->product_title->SetVisibility();
		$this->product_price->SetVisibility();
		$this->product_desc->SetVisibility();
		$this->product_image->SetVisibility();
		$this->product_keywords->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $products;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($products);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		// Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "productsview.php")
						$row["view"] = "1";
				} else { // List page should not be shown as modal => error
					$row["error"] = $this->getFailureMessage();
					$this->clearFailureMessage();
				}
				header("Content-Type: application/json; charset=utf-8");
				echo ew_ConvertToUtf8(ew_ArrayToJson(array($row)));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewEditForm form-horizontal";
		$sReturnUrl = "";
		$loaded = FALSE;
		$postBack = FALSE;

		// Set up current action and primary key
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			if ($this->CurrentAction <> "I") // Not reload record, handle as postback
				$postBack = TRUE;

			// Load key from Form
			if ($objForm->HasValue("x_product_id")) {
				$this->product_id->setFormValue($objForm->GetValue("x_product_id"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["product_id"])) {
				$this->product_id->setQueryStringValue($_GET["product_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->product_id->CurrentValue = NULL;
			}
		}

		// Load current record
		$loaded = $this->LoadRow();

		// Process form if post back
		if ($postBack) {
			$this->LoadFormValues(); // Get form values
		}

		// Validate form if post back
		if ($postBack) {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$loaded) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("productslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "productslist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetupStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->product_image->Upload->Index = $objForm->Index;
		$this->product_image->Upload->UploadFile();
		$this->product_image->CurrentValue = $this->product_image->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->product_id->FldIsDetailKey)
			$this->product_id->setFormValue($objForm->GetValue("x_product_id"));
		if (!$this->product_cat->FldIsDetailKey) {
			$this->product_cat->setFormValue($objForm->GetValue("x_product_cat"));
		}
		if (!$this->product_brand->FldIsDetailKey) {
			$this->product_brand->setFormValue($objForm->GetValue("x_product_brand"));
		}
		if (!$this->product_title->FldIsDetailKey) {
			$this->product_title->setFormValue($objForm->GetValue("x_product_title"));
		}
		if (!$this->product_price->FldIsDetailKey) {
			$this->product_price->setFormValue($objForm->GetValue("x_product_price"));
		}
		if (!$this->product_desc->FldIsDetailKey) {
			$this->product_desc->setFormValue($objForm->GetValue("x_product_desc"));
		}
		if (!$this->product_keywords->FldIsDetailKey) {
			$this->product_keywords->setFormValue($objForm->GetValue("x_product_keywords"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->product_id->CurrentValue = $this->product_id->FormValue;
		$this->product_cat->CurrentValue = $this->product_cat->FormValue;
		$this->product_brand->CurrentValue = $this->product_brand->FormValue;
		$this->product_title->CurrentValue = $this->product_title->FormValue;
		$this->product_price->CurrentValue = $this->product_price->FormValue;
		$this->product_desc->CurrentValue = $this->product_desc->FormValue;
		$this->product_keywords->CurrentValue = $this->product_keywords->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues($rs = NULL) {
		if ($rs && !$rs->EOF)
			$row = $rs->fields;
		else
			$row = $this->NewRow(); 

		// Call Row Selected event
		$this->Row_Selected($row);
		if (!$rs || $rs->EOF)
			return;
		$this->product_id->setDbValue($row['product_id']);
		$this->product_cat->setDbValue($row['product_cat']);
		if (array_key_exists('EV__product_cat', $rs->fields)) {
			$this->product_cat->VirtualValue = $rs->fields('EV__product_cat'); // Set up virtual field value
		} else {
			$this->product_cat->VirtualValue = ""; // Clear value
		}
		$this->product_brand->setDbValue($row['product_brand']);
		if (array_key_exists('EV__product_brand', $rs->fields)) {
			$this->product_brand->VirtualValue = $rs->fields('EV__product_brand'); // Set up virtual field value
		} else {
			$this->product_brand->VirtualValue = ""; // Clear value
		}
		$this->product_title->setDbValue($row['product_title']);
		$this->product_price->setDbValue($row['product_price']);
		$this->product_desc->setDbValue($row['product_desc']);
		$this->product_image->Upload->DbValue = $row['product_image'];
		$this->product_image->setDbValue($this->product_image->Upload->DbValue);
		$this->product_keywords->setDbValue($row['product_keywords']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['product_id'] = NULL;
		$row['product_cat'] = NULL;
		$row['product_brand'] = NULL;
		$row['product_title'] = NULL;
		$row['product_price'] = NULL;
		$row['product_desc'] = NULL;
		$row['product_image'] = NULL;
		$row['product_keywords'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->product_id->DbValue = $row['product_id'];
		$this->product_cat->DbValue = $row['product_cat'];
		$this->product_brand->DbValue = $row['product_brand'];
		$this->product_title->DbValue = $row['product_title'];
		$this->product_price->DbValue = $row['product_price'];
		$this->product_desc->DbValue = $row['product_desc'];
		$this->product_image->Upload->DbValue = $row['product_image'];
		$this->product_keywords->DbValue = $row['product_keywords'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("product_id")) <> "")
			$this->product_id->CurrentValue = $this->getKey("product_id"); // product_id
		else
			$bValidKey = FALSE;

		// Load old record
		$this->OldRecordset = NULL;
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
		}
		$this->LoadRowValues($this->OldRecordset); // Load row values
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// product_id
		// product_cat
		// product_brand
		// product_title
		// product_price
		// product_desc
		// product_image
		// product_keywords

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// product_id
		$this->product_id->ViewValue = $this->product_id->CurrentValue;
		$this->product_id->ViewCustomAttributes = "";

		// product_cat
		if ($this->product_cat->VirtualValue <> "") {
			$this->product_cat->ViewValue = $this->product_cat->VirtualValue;
		} else {
		if (strval($this->product_cat->CurrentValue) <> "") {
			$sFilterWrk = "`cat_id`" . ew_SearchString("=", $this->product_cat->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT DISTINCT `cat_id`, `cat_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categories`";
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

		// product_title
		$this->product_title->ViewValue = $this->product_title->CurrentValue;
		$this->product_title->ViewCustomAttributes = "";

		// product_price
		$this->product_price->ViewValue = $this->product_price->CurrentValue;
		$this->product_price->ViewCustomAttributes = "";

		// product_desc
		$this->product_desc->ViewValue = $this->product_desc->CurrentValue;
		$this->product_desc->ViewCustomAttributes = "";

		// product_image
		$this->product_image->UploadPath = "..\product_images";
		if (!ew_Empty($this->product_image->Upload->DbValue)) {
			$this->product_image->ImageWidth = 0;
			$this->product_image->ImageHeight = 94;
			$this->product_image->ImageAlt = $this->product_image->FldAlt();
			$this->product_image->ViewValue = $this->product_image->Upload->DbValue;
		} else {
			$this->product_image->ViewValue = "";
		}
		$this->product_image->ViewCustomAttributes = "";

		// product_keywords
		$this->product_keywords->ViewValue = $this->product_keywords->CurrentValue;
		$this->product_keywords->ViewCustomAttributes = "";

			// product_id
			$this->product_id->LinkCustomAttributes = "";
			$this->product_id->HrefValue = "";
			$this->product_id->TooltipValue = "";

			// product_cat
			$this->product_cat->LinkCustomAttributes = "";
			$this->product_cat->HrefValue = "";
			$this->product_cat->TooltipValue = "";

			// product_brand
			$this->product_brand->LinkCustomAttributes = "";
			$this->product_brand->HrefValue = "";
			$this->product_brand->TooltipValue = "";

			// product_title
			$this->product_title->LinkCustomAttributes = "";
			$this->product_title->HrefValue = "";
			$this->product_title->TooltipValue = "";

			// product_price
			$this->product_price->LinkCustomAttributes = "";
			$this->product_price->HrefValue = "";
			$this->product_price->TooltipValue = "";

			// product_desc
			$this->product_desc->LinkCustomAttributes = "";
			$this->product_desc->HrefValue = "";
			$this->product_desc->TooltipValue = "";

			// product_image
			$this->product_image->LinkCustomAttributes = "";
			$this->product_image->UploadPath = "..\product_images";
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

			// product_keywords
			$this->product_keywords->LinkCustomAttributes = "";
			$this->product_keywords->HrefValue = "";
			$this->product_keywords->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// product_id
			$this->product_id->EditAttrs["class"] = "form-control";
			$this->product_id->EditCustomAttributes = "";
			$this->product_id->EditValue = $this->product_id->CurrentValue;
			$this->product_id->ViewCustomAttributes = "";

			// product_cat
			$this->product_cat->EditCustomAttributes = "";
			if (trim(strval($this->product_cat->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`cat_id`" . ew_SearchString("=", $this->product_cat->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT DISTINCT `cat_id`, `cat_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `categories`";
			$sWhereWrk = "";
			$this->product_cat->LookupFilters = array("dx1" => '`cat_title`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->product_cat, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->product_cat->ViewValue = $this->product_cat->DisplayValue($arwrk);
			} else {
				$this->product_cat->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->product_cat->EditValue = $arwrk;

			// product_brand
			$this->product_brand->EditCustomAttributes = "";
			if (trim(strval($this->product_brand->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`brand_id`" . ew_SearchString("=", $this->product_brand->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT DISTINCT `brand_id`, `brand_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `brands`";
			$sWhereWrk = "";
			$this->product_brand->LookupFilters = array("dx1" => '`brand_title`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->product_brand, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->product_brand->ViewValue = $this->product_brand->DisplayValue($arwrk);
			} else {
				$this->product_brand->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->product_brand->EditValue = $arwrk;

			// product_title
			$this->product_title->EditAttrs["class"] = "form-control";
			$this->product_title->EditCustomAttributes = "";
			$this->product_title->EditValue = ew_HtmlEncode($this->product_title->CurrentValue);
			$this->product_title->PlaceHolder = ew_RemoveHtml($this->product_title->FldCaption());

			// product_price
			$this->product_price->EditAttrs["class"] = "form-control";
			$this->product_price->EditCustomAttributes = "";
			$this->product_price->EditValue = ew_HtmlEncode($this->product_price->CurrentValue);
			$this->product_price->PlaceHolder = ew_RemoveHtml($this->product_price->FldCaption());

			// product_desc
			$this->product_desc->EditAttrs["class"] = "form-control";
			$this->product_desc->EditCustomAttributes = "";
			$this->product_desc->EditValue = ew_HtmlEncode($this->product_desc->CurrentValue);
			$this->product_desc->PlaceHolder = ew_RemoveHtml($this->product_desc->FldCaption());

			// product_image
			$this->product_image->EditAttrs["class"] = "form-control";
			$this->product_image->EditCustomAttributes = "";
			$this->product_image->UploadPath = "..\product_images";
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
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->product_image);

			// product_keywords
			$this->product_keywords->EditAttrs["class"] = "form-control";
			$this->product_keywords->EditCustomAttributes = "";
			$this->product_keywords->EditValue = ew_HtmlEncode($this->product_keywords->CurrentValue);
			$this->product_keywords->PlaceHolder = ew_RemoveHtml($this->product_keywords->FldCaption());

			// Edit refer script
			// product_id

			$this->product_id->LinkCustomAttributes = "";
			$this->product_id->HrefValue = "";

			// product_cat
			$this->product_cat->LinkCustomAttributes = "";
			$this->product_cat->HrefValue = "";

			// product_brand
			$this->product_brand->LinkCustomAttributes = "";
			$this->product_brand->HrefValue = "";

			// product_title
			$this->product_title->LinkCustomAttributes = "";
			$this->product_title->HrefValue = "";

			// product_price
			$this->product_price->LinkCustomAttributes = "";
			$this->product_price->HrefValue = "";

			// product_desc
			$this->product_desc->LinkCustomAttributes = "";
			$this->product_desc->HrefValue = "";

			// product_image
			$this->product_image->LinkCustomAttributes = "";
			$this->product_image->UploadPath = "..\product_images";
			if (!ew_Empty($this->product_image->Upload->DbValue)) {
				$this->product_image->HrefValue = ew_GetFileUploadUrl($this->product_image, $this->product_image->Upload->DbValue); // Add prefix/suffix
				$this->product_image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->product_image->HrefValue = ew_FullUrl($this->product_image->HrefValue, "href");
			} else {
				$this->product_image->HrefValue = "";
			}
			$this->product_image->HrefValue2 = $this->product_image->UploadPath . $this->product_image->Upload->DbValue;

			// product_keywords
			$this->product_keywords->LinkCustomAttributes = "";
			$this->product_keywords->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->product_cat->FldIsDetailKey && !is_null($this->product_cat->FormValue) && $this->product_cat->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_cat->FldCaption(), $this->product_cat->ReqErrMsg));
		}
		if (!$this->product_brand->FldIsDetailKey && !is_null($this->product_brand->FormValue) && $this->product_brand->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_brand->FldCaption(), $this->product_brand->ReqErrMsg));
		}
		if (!$this->product_title->FldIsDetailKey && !is_null($this->product_title->FormValue) && $this->product_title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_title->FldCaption(), $this->product_title->ReqErrMsg));
		}
		if (!$this->product_price->FldIsDetailKey && !is_null($this->product_price->FormValue) && $this->product_price->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_price->FldCaption(), $this->product_price->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->product_price->FormValue)) {
			ew_AddMessage($gsFormError, $this->product_price->FldErrMsg());
		}
		if (!$this->product_desc->FldIsDetailKey && !is_null($this->product_desc->FormValue) && $this->product_desc->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_desc->FldCaption(), $this->product_desc->ReqErrMsg));
		}
		if ($this->product_image->Upload->FileName == "" && !$this->product_image->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_image->FldCaption(), $this->product_image->ReqErrMsg));
		}
		if (!$this->product_keywords->FldIsDetailKey && !is_null($this->product_keywords->FormValue) && $this->product_keywords->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_keywords->FldCaption(), $this->product_keywords->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$this->product_image->OldUploadPath = "..\product_images";
			$this->product_image->UploadPath = $this->product_image->OldUploadPath;
			$rsnew = array();

			// product_cat
			$this->product_cat->SetDbValueDef($rsnew, $this->product_cat->CurrentValue, 0, $this->product_cat->ReadOnly);

			// product_brand
			$this->product_brand->SetDbValueDef($rsnew, $this->product_brand->CurrentValue, 0, $this->product_brand->ReadOnly);

			// product_title
			$this->product_title->SetDbValueDef($rsnew, $this->product_title->CurrentValue, "", $this->product_title->ReadOnly);

			// product_price
			$this->product_price->SetDbValueDef($rsnew, $this->product_price->CurrentValue, 0, $this->product_price->ReadOnly);

			// product_desc
			$this->product_desc->SetDbValueDef($rsnew, $this->product_desc->CurrentValue, "", $this->product_desc->ReadOnly);

			// product_image
			if ($this->product_image->Visible && !$this->product_image->ReadOnly && !$this->product_image->Upload->KeepFile) {
				$this->product_image->Upload->DbValue = $rsold['product_image']; // Get original value
				if ($this->product_image->Upload->FileName == "") {
					$rsnew['product_image'] = NULL;
				} else {
					$rsnew['product_image'] = $this->product_image->Upload->FileName;
				}
			}

			// product_keywords
			$this->product_keywords->SetDbValueDef($rsnew, $this->product_keywords->CurrentValue, "", $this->product_keywords->ReadOnly);
			if ($this->product_image->Visible && !$this->product_image->Upload->KeepFile) {
				$this->product_image->UploadPath = "..\product_images";
				$OldFiles = ew_Empty($this->product_image->Upload->DbValue) ? array() : array($this->product_image->Upload->DbValue);
				if (!ew_Empty($this->product_image->Upload->FileName)) {
					$NewFiles = array($this->product_image->Upload->FileName);
					$NewFileCount = count($NewFiles);
					for ($i = 0; $i < $NewFileCount; $i++) {
						$fldvar = ($this->product_image->Upload->Index < 0) ? $this->product_image->FldVar : substr($this->product_image->FldVar, 0, 1) . $this->product_image->Upload->Index . substr($this->product_image->FldVar, 1);
						if ($NewFiles[$i] <> "") {
							$file = $NewFiles[$i];
							if (file_exists(ew_UploadTempPath($fldvar, $this->product_image->TblVar) . $file)) {
								$OldFileFound = FALSE;
								$OldFileCount = count($OldFiles);
								for ($j = 0; $j < $OldFileCount; $j++) {
									$file1 = $OldFiles[$j];
									if ($file1 == $file) { // Old file found, no need to delete anymore
										unset($OldFiles[$j]);
										$OldFileFound = TRUE;
										break;
									}
								}
								if ($OldFileFound) // No need to check if file exists further
									continue;
								$file1 = ew_UploadFileNameEx($this->product_image->PhysicalUploadPath(), $file); // Get new file name
								if ($file1 <> $file) { // Rename temp file
									while (file_exists(ew_UploadTempPath($fldvar, $this->product_image->TblVar) . $file1) || file_exists($this->product_image->PhysicalUploadPath() . $file1)) // Make sure no file name clash
										$file1 = ew_UniqueFilename($this->product_image->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
									rename(ew_UploadTempPath($fldvar, $this->product_image->TblVar) . $file, ew_UploadTempPath($fldvar, $this->product_image->TblVar) . $file1);
									$NewFiles[$i] = $file1;
								}
							}
						}
					}
					$this->product_image->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
					$this->product_image->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
					$this->product_image->SetDbValueDef($rsnew, $this->product_image->Upload->FileName, "", $this->product_image->ReadOnly);
				}
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
					if ($this->product_image->Visible && !$this->product_image->Upload->KeepFile) {
						$OldFiles = ew_Empty($this->product_image->Upload->DbValue) ? array() : array($this->product_image->Upload->DbValue);
						if (!ew_Empty($this->product_image->Upload->FileName)) {
							$NewFiles = array($this->product_image->Upload->FileName);
							$NewFiles2 = array($rsnew['product_image']);
							$NewFileCount = count($NewFiles);
							for ($i = 0; $i < $NewFileCount; $i++) {
								$fldvar = ($this->product_image->Upload->Index < 0) ? $this->product_image->FldVar : substr($this->product_image->FldVar, 0, 1) . $this->product_image->Upload->Index . substr($this->product_image->FldVar, 1);
								if ($NewFiles[$i] <> "") {
									$file = ew_UploadTempPath($fldvar, $this->product_image->TblVar) . $NewFiles[$i];
									if (file_exists($file)) {
										if (@$NewFiles2[$i] <> "") // Use correct file name
											$NewFiles[$i] = $NewFiles2[$i];
										if (!$this->product_image->Upload->SaveToFile($NewFiles[$i], TRUE, $i)) { // Just replace
											$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
											return FALSE;
										}
									}
								}
							}
						} else {
							$NewFiles = array();
						}
						$OldFileCount = count($OldFiles);
						for ($i = 0; $i < $OldFileCount; $i++) {
							if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
								@unlink($this->product_image->OldPhysicalUploadPath() . $OldFiles[$i]);
						}
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();

		// product_image
		ew_CleanUploadTempPath($this->product_image, $this->product_image->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("productslist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_product_cat":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT DISTINCT `cat_id` AS `LinkFld`, `cat_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categories`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`cat_title`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`cat_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->product_cat, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_product_brand":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT DISTINCT `brand_id` AS `LinkFld`, `brand_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `brands`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`brand_title`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`brand_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->product_brand, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($products_edit)) $products_edit = new cproducts_edit();

// Page init
$products_edit->Page_Init();

// Page main
$products_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$products_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fproductsedit = new ew_Form("fproductsedit", "edit");

// Validate form
fproductsedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_product_cat");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_cat->FldCaption(), $products->product_cat->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product_brand");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_brand->FldCaption(), $products->product_brand->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_title->FldCaption(), $products->product_title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product_price");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_price->FldCaption(), $products->product_price->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product_price");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($products->product_price->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_product_desc");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_desc->FldCaption(), $products->product_desc->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_product_image");
			elm = this.GetElements("fn_x" + infix + "_product_image");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_image->FldCaption(), $products->product_image->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product_keywords");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_keywords->FldCaption(), $products->product_keywords->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fproductsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fproductsedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fproductsedit.Lists["x_product_cat"] = {"LinkField":"x_cat_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_cat_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"categories"};
fproductsedit.Lists["x_product_cat"].Data = "<?php echo $products_edit->product_cat->LookupFilterQuery(FALSE, "edit") ?>";
fproductsedit.Lists["x_product_brand"] = {"LinkField":"x_brand_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_brand_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"brands"};
fproductsedit.Lists["x_product_brand"].Data = "<?php echo $products_edit->product_brand->LookupFilterQuery(FALSE, "edit") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $products_edit->ShowPageHeader(); ?>
<?php
$products_edit->ShowMessage();
?>
<form name="fproductsedit" id="fproductsedit" class="<?php echo $products_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($products_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $products_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="products">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($products_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($products->product_id->Visible) { // product_id ?>
	<div id="r_product_id" class="form-group">
		<label id="elh_products_product_id" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->product_id->FldCaption() ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->product_id->CellAttributes() ?>>
<span id="el_products_product_id">
<span<?php echo $products->product_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $products->product_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="products" data-field="x_product_id" name="x_product_id" id="x_product_id" value="<?php echo ew_HtmlEncode($products->product_id->CurrentValue) ?>">
<?php echo $products->product_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_cat->Visible) { // product_cat ?>
	<div id="r_product_cat" class="form-group">
		<label id="elh_products_product_cat" for="x_product_cat" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->product_cat->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->product_cat->CellAttributes() ?>>
<span id="el_products_product_cat">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_product_cat"><?php echo (strval($products->product_cat->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $products->product_cat->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($products->product_cat->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_product_cat',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($products->product_cat->ReadOnly || $products->product_cat->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="products" data-field="x_product_cat" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $products->product_cat->DisplayValueSeparatorAttribute() ?>" name="x_product_cat" id="x_product_cat" value="<?php echo $products->product_cat->CurrentValue ?>"<?php echo $products->product_cat->EditAttributes() ?>>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $products->product_cat->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_product_cat',url:'categoriesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_product_cat"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $products->product_cat->FldCaption() ?></span></button>
</span>
<?php echo $products->product_cat->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_brand->Visible) { // product_brand ?>
	<div id="r_product_brand" class="form-group">
		<label id="elh_products_product_brand" for="x_product_brand" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->product_brand->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->product_brand->CellAttributes() ?>>
<span id="el_products_product_brand">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_product_brand"><?php echo (strval($products->product_brand->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $products->product_brand->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($products->product_brand->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_product_brand',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($products->product_brand->ReadOnly || $products->product_brand->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="products" data-field="x_product_brand" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $products->product_brand->DisplayValueSeparatorAttribute() ?>" name="x_product_brand" id="x_product_brand" value="<?php echo $products->product_brand->CurrentValue ?>"<?php echo $products->product_brand->EditAttributes() ?>>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $products->product_brand->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_product_brand',url:'brandsaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_product_brand"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $products->product_brand->FldCaption() ?></span></button>
</span>
<?php echo $products->product_brand->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_title->Visible) { // product_title ?>
	<div id="r_product_title" class="form-group">
		<label id="elh_products_product_title" for="x_product_title" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->product_title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->product_title->CellAttributes() ?>>
<span id="el_products_product_title">
<input type="text" data-table="products" data-field="x_product_title" name="x_product_title" id="x_product_title" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($products->product_title->getPlaceHolder()) ?>" value="<?php echo $products->product_title->EditValue ?>"<?php echo $products->product_title->EditAttributes() ?>>
</span>
<?php echo $products->product_title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_price->Visible) { // product_price ?>
	<div id="r_product_price" class="form-group">
		<label id="elh_products_product_price" for="x_product_price" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->product_price->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->product_price->CellAttributes() ?>>
<span id="el_products_product_price">
<input type="text" data-table="products" data-field="x_product_price" name="x_product_price" id="x_product_price" size="30" placeholder="<?php echo ew_HtmlEncode($products->product_price->getPlaceHolder()) ?>" value="<?php echo $products->product_price->EditValue ?>"<?php echo $products->product_price->EditAttributes() ?>>
</span>
<?php echo $products->product_price->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_desc->Visible) { // product_desc ?>
	<div id="r_product_desc" class="form-group">
		<label id="elh_products_product_desc" for="x_product_desc" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->product_desc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->product_desc->CellAttributes() ?>>
<span id="el_products_product_desc">
<textarea data-table="products" data-field="x_product_desc" name="x_product_desc" id="x_product_desc" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($products->product_desc->getPlaceHolder()) ?>"<?php echo $products->product_desc->EditAttributes() ?>><?php echo $products->product_desc->EditValue ?></textarea>
</span>
<?php echo $products->product_desc->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_image->Visible) { // product_image ?>
	<div id="r_product_image" class="form-group">
		<label id="elh_products_product_image" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->product_image->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->product_image->CellAttributes() ?>>
<span id="el_products_product_image">
<div id="fd_x_product_image">
<span title="<?php echo $products->product_image->FldTitle() ? $products->product_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_image->ReadOnly || $products->product_image->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_image" name="x_product_image" id="x_product_image"<?php echo $products->product_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_product_image" id= "fn_x_product_image" value="<?php echo $products->product_image->Upload->FileName ?>">
<?php if (@$_POST["fa_x_product_image"] == "0") { ?>
<input type="hidden" name="fa_x_product_image" id= "fa_x_product_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_product_image" id= "fa_x_product_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x_product_image" id= "fs_x_product_image" value="65535">
<input type="hidden" name="fx_x_product_image" id= "fx_x_product_image" value="<?php echo $products->product_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_product_image" id= "fm_x_product_image" value="<?php echo $products->product_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_product_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $products->product_image->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_keywords->Visible) { // product_keywords ?>
	<div id="r_product_keywords" class="form-group">
		<label id="elh_products_product_keywords" for="x_product_keywords" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->product_keywords->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->product_keywords->CellAttributes() ?>>
<span id="el_products_product_keywords">
<textarea data-table="products" data-field="x_product_keywords" name="x_product_keywords" id="x_product_keywords" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($products->product_keywords->getPlaceHolder()) ?>"<?php echo $products->product_keywords->EditAttributes() ?>><?php echo $products->product_keywords->EditValue ?></textarea>
</span>
<?php echo $products->product_keywords->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$products_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $products_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $products_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fproductsedit.Init();
</script>
<?php
$products_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$products_edit->Page_Terminate();
?>
