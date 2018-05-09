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

$products_add = NULL; // Initialize page object first

class cproducts_add extends cproducts {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{DE3D48ED-60FB-4F03-A5AD-139B7A8A8A85}';

	// Table name
	var $TableName = 'products';

	// Page object name
	var $PageObjName = 'products_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewAddForm form-horizontal";

		// Set up current action
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["product_id"] != "") {
				$this->product_id->setQueryStringValue($_GET["product_id"]);
				$this->setKey("product_id", $this->product_id->CurrentValue); // Set up key
			} else {
				$this->setKey("product_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Load old record / default values
		$loaded = $this->LoadOldRecord();

		// Load form values
		if (@$_POST["a_add"] <> "") {
			$this->LoadFormValues(); // Load form values
		}

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Blank record
				break;
			case "C": // Copy an existing record
				if (!$loaded) { // Record not loaded
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("productslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "productslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "productsview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to View page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->product_image->Upload->Index = $objForm->Index;
		$this->product_image->Upload->UploadFile();
		$this->product_image->CurrentValue = $this->product_image->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->product_id->CurrentValue = NULL;
		$this->product_id->OldValue = $this->product_id->CurrentValue;
		$this->product_cat->CurrentValue = NULL;
		$this->product_cat->OldValue = $this->product_cat->CurrentValue;
		$this->product_brand->CurrentValue = NULL;
		$this->product_brand->OldValue = $this->product_brand->CurrentValue;
		$this->product_title->CurrentValue = NULL;
		$this->product_title->OldValue = $this->product_title->CurrentValue;
		$this->product_price->CurrentValue = NULL;
		$this->product_price->OldValue = $this->product_price->CurrentValue;
		$this->product_desc->CurrentValue = NULL;
		$this->product_desc->OldValue = $this->product_desc->CurrentValue;
		$this->product_image->Upload->DbValue = NULL;
		$this->product_image->OldValue = $this->product_image->Upload->DbValue;
		$this->product_image->CurrentValue = NULL; // Clear file related field
		$this->product_keywords->CurrentValue = NULL;
		$this->product_keywords->OldValue = $this->product_keywords->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
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
		$this->LoadDefaultValues();
		$row = array();
		$row['product_id'] = $this->product_id->CurrentValue;
		$row['product_cat'] = $this->product_cat->CurrentValue;
		$row['product_brand'] = $this->product_brand->CurrentValue;
		$row['product_title'] = $this->product_title->CurrentValue;
		$row['product_price'] = $this->product_price->CurrentValue;
		$row['product_desc'] = $this->product_desc->CurrentValue;
		$row['product_image'] = $this->product_image->Upload->DbValue;
		$row['product_keywords'] = $this->product_keywords->CurrentValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->product_image);

			// product_keywords
			$this->product_keywords->EditAttrs["class"] = "form-control";
			$this->product_keywords->EditCustomAttributes = "";
			$this->product_keywords->EditValue = ew_HtmlEncode($this->product_keywords->CurrentValue);
			$this->product_keywords->PlaceHolder = ew_RemoveHtml($this->product_keywords->FldCaption());

			// Add refer script
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
			$this->product_image->OldUploadPath = "..\product_images";
			$this->product_image->UploadPath = $this->product_image->OldUploadPath;
		}
		$rsnew = array();

		// product_cat
		$this->product_cat->SetDbValueDef($rsnew, $this->product_cat->CurrentValue, 0, FALSE);

		// product_brand
		$this->product_brand->SetDbValueDef($rsnew, $this->product_brand->CurrentValue, 0, FALSE);

		// product_title
		$this->product_title->SetDbValueDef($rsnew, $this->product_title->CurrentValue, "", FALSE);

		// product_price
		$this->product_price->SetDbValueDef($rsnew, $this->product_price->CurrentValue, 0, FALSE);

		// product_desc
		$this->product_desc->SetDbValueDef($rsnew, $this->product_desc->CurrentValue, "", FALSE);

		// product_image
		if ($this->product_image->Visible && !$this->product_image->Upload->KeepFile) {
			$this->product_image->Upload->DbValue = ""; // No need to delete old file
			if ($this->product_image->Upload->FileName == "") {
				$rsnew['product_image'] = NULL;
			} else {
				$rsnew['product_image'] = $this->product_image->Upload->FileName;
			}
		}

		// product_keywords
		$this->product_keywords->SetDbValueDef($rsnew, $this->product_keywords->CurrentValue, "", FALSE);
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
				$this->product_image->SetDbValueDef($rsnew, $this->product_image->Upload->FileName, "", FALSE);
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
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
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// product_image
		ew_CleanUploadTempPath($this->product_image, $this->product_image->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("productslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($products_add)) $products_add = new cproducts_add();

// Page init
$products_add->Page_Init();

// Page main
$products_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$products_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fproductsadd = new ew_Form("fproductsadd", "add");

// Validate form
fproductsadd.Validate = function() {
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
fproductsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fproductsadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fproductsadd.Lists["x_product_cat"] = {"LinkField":"x_cat_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_cat_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"categories"};
fproductsadd.Lists["x_product_cat"].Data = "<?php echo $products_add->product_cat->LookupFilterQuery(FALSE, "add") ?>";
fproductsadd.Lists["x_product_brand"] = {"LinkField":"x_brand_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_brand_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"brands"};
fproductsadd.Lists["x_product_brand"].Data = "<?php echo $products_add->product_brand->LookupFilterQuery(FALSE, "add") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $products_add->ShowPageHeader(); ?>
<?php
$products_add->ShowMessage();
?>
<form name="fproductsadd" id="fproductsadd" class="<?php echo $products_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($products_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $products_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="products">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($products_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($products->product_cat->Visible) { // product_cat ?>
	<div id="r_product_cat" class="form-group">
		<label id="elh_products_product_cat" for="x_product_cat" class="<?php echo $products_add->LeftColumnClass ?>"><?php echo $products->product_cat->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_add->RightColumnClass ?>"><div<?php echo $products->product_cat->CellAttributes() ?>>
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
		<label id="elh_products_product_brand" for="x_product_brand" class="<?php echo $products_add->LeftColumnClass ?>"><?php echo $products->product_brand->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_add->RightColumnClass ?>"><div<?php echo $products->product_brand->CellAttributes() ?>>
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
		<label id="elh_products_product_title" for="x_product_title" class="<?php echo $products_add->LeftColumnClass ?>"><?php echo $products->product_title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_add->RightColumnClass ?>"><div<?php echo $products->product_title->CellAttributes() ?>>
<span id="el_products_product_title">
<input type="text" data-table="products" data-field="x_product_title" name="x_product_title" id="x_product_title" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($products->product_title->getPlaceHolder()) ?>" value="<?php echo $products->product_title->EditValue ?>"<?php echo $products->product_title->EditAttributes() ?>>
</span>
<?php echo $products->product_title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_price->Visible) { // product_price ?>
	<div id="r_product_price" class="form-group">
		<label id="elh_products_product_price" for="x_product_price" class="<?php echo $products_add->LeftColumnClass ?>"><?php echo $products->product_price->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_add->RightColumnClass ?>"><div<?php echo $products->product_price->CellAttributes() ?>>
<span id="el_products_product_price">
<input type="text" data-table="products" data-field="x_product_price" name="x_product_price" id="x_product_price" size="30" placeholder="<?php echo ew_HtmlEncode($products->product_price->getPlaceHolder()) ?>" value="<?php echo $products->product_price->EditValue ?>"<?php echo $products->product_price->EditAttributes() ?>>
</span>
<?php echo $products->product_price->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_desc->Visible) { // product_desc ?>
	<div id="r_product_desc" class="form-group">
		<label id="elh_products_product_desc" for="x_product_desc" class="<?php echo $products_add->LeftColumnClass ?>"><?php echo $products->product_desc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_add->RightColumnClass ?>"><div<?php echo $products->product_desc->CellAttributes() ?>>
<span id="el_products_product_desc">
<textarea data-table="products" data-field="x_product_desc" name="x_product_desc" id="x_product_desc" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($products->product_desc->getPlaceHolder()) ?>"<?php echo $products->product_desc->EditAttributes() ?>><?php echo $products->product_desc->EditValue ?></textarea>
</span>
<?php echo $products->product_desc->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_image->Visible) { // product_image ?>
	<div id="r_product_image" class="form-group">
		<label id="elh_products_product_image" class="<?php echo $products_add->LeftColumnClass ?>"><?php echo $products->product_image->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_add->RightColumnClass ?>"><div<?php echo $products->product_image->CellAttributes() ?>>
<span id="el_products_product_image">
<div id="fd_x_product_image">
<span title="<?php echo $products->product_image->FldTitle() ? $products->product_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_image->ReadOnly || $products->product_image->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_image" name="x_product_image" id="x_product_image"<?php echo $products->product_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_product_image" id= "fn_x_product_image" value="<?php echo $products->product_image->Upload->FileName ?>">
<input type="hidden" name="fa_x_product_image" id= "fa_x_product_image" value="0">
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
		<label id="elh_products_product_keywords" for="x_product_keywords" class="<?php echo $products_add->LeftColumnClass ?>"><?php echo $products->product_keywords->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_add->RightColumnClass ?>"><div<?php echo $products->product_keywords->CellAttributes() ?>>
<span id="el_products_product_keywords">
<textarea data-table="products" data-field="x_product_keywords" name="x_product_keywords" id="x_product_keywords" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($products->product_keywords->getPlaceHolder()) ?>"<?php echo $products->product_keywords->EditAttributes() ?>><?php echo $products->product_keywords->EditValue ?></textarea>
</span>
<?php echo $products->product_keywords->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$products_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $products_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $products_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fproductsadd.Init();
</script>
<?php
$products_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$products_add->Page_Terminate();
?>
