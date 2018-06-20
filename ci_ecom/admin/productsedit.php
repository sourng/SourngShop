<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "productsinfo.php" ?>
<?php include_once "user_infoinfo.php" ?>
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
	var $ProjectID = '{35B667F0-3972-4C72-8E88-51DD671EF082}';

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
		global $UserTable, $UserTableConn;
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

		// Table object (user_info)
		if (!isset($GLOBALS['user_info'])) $GLOBALS['user_info'] = new cuser_info();

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

		// User table object (user_info)
		if (!isset($UserTable)) {
			$UserTable = new cuser_info();
			$UserTableConn = Conn($UserTable->DBID);
		}
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
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
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
		$this->product_brand->SetVisibility();
		$this->product_cat->SetVisibility();
		$this->product_title->SetVisibility();
		$this->product_price->SetVisibility();
		$this->product_dist->SetVisibility();
		$this->product_desc->SetVisibility();
		$this->product_image->SetVisibility();
		$this->product_image1->SetVisibility();
		$this->product_image2->SetVisibility();
		$this->product_image3->SetVisibility();
		$this->top_sell->SetVisibility();
		$this->condition->SetVisibility();
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
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $AutoHidePager = EW_AUTO_HIDE_PAGER;
	var $RecCnt;
	var $Recordset;

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

		// Load record by position
		$loadByPosition = FALSE;
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
			if (!$loadByQuery)
				$loadByPosition = TRUE;
		}

		// Load recordset
		$this->StartRec = 1; // Initialize start position
		if ($this->Recordset = $this->LoadRecordset()) // Load records
			$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
		if ($this->TotalRecs <= 0) { // No record found
			if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$this->Page_Terminate("productslist.php"); // Return to list page
		} elseif ($loadByPosition) { // Load record by position
			$this->SetupStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$this->Recordset->Move($this->StartRec-1);
				$loaded = TRUE;
			}
		} else { // Match key values
			if (!is_null($this->product_id->CurrentValue)) {
				while (!$this->Recordset->EOF) {
					if (strval($this->product_id->CurrentValue) == strval($this->Recordset->fields('product_id'))) {
						$this->setStartRecordNumber($this->StartRec); // Save record position
						$loaded = TRUE;
						break;
					} else {
						$this->StartRec++;
						$this->Recordset->MoveNext();
					}
				}
			}
		}

		// Load current row values
		if ($loaded)
			$this->LoadRowValues($this->Recordset);

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
				if (!$loaded) {
					if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
						$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
					$this->Page_Terminate("productslist.php"); // Return to list page
				} else {
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
		$this->product_image1->Upload->Index = $objForm->Index;
		$this->product_image1->Upload->UploadFile();
		$this->product_image1->CurrentValue = $this->product_image1->Upload->FileName;
		$this->product_image2->Upload->Index = $objForm->Index;
		$this->product_image2->Upload->UploadFile();
		$this->product_image2->CurrentValue = $this->product_image2->Upload->FileName;
		$this->product_image3->Upload->Index = $objForm->Index;
		$this->product_image3->Upload->UploadFile();
		$this->product_image3->CurrentValue = $this->product_image3->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->product_id->FldIsDetailKey)
			$this->product_id->setFormValue($objForm->GetValue("x_product_id"));
		if (!$this->product_brand->FldIsDetailKey) {
			$this->product_brand->setFormValue($objForm->GetValue("x_product_brand"));
		}
		if (!$this->product_cat->FldIsDetailKey) {
			$this->product_cat->setFormValue($objForm->GetValue("x_product_cat"));
		}
		if (!$this->product_title->FldIsDetailKey) {
			$this->product_title->setFormValue($objForm->GetValue("x_product_title"));
		}
		if (!$this->product_price->FldIsDetailKey) {
			$this->product_price->setFormValue($objForm->GetValue("x_product_price"));
		}
		if (!$this->product_dist->FldIsDetailKey) {
			$this->product_dist->setFormValue($objForm->GetValue("x_product_dist"));
		}
		if (!$this->product_desc->FldIsDetailKey) {
			$this->product_desc->setFormValue($objForm->GetValue("x_product_desc"));
		}
		if (!$this->top_sell->FldIsDetailKey) {
			$this->top_sell->setFormValue($objForm->GetValue("x_top_sell"));
		}
		if (!$this->condition->FldIsDetailKey) {
			$this->condition->setFormValue($objForm->GetValue("x_condition"));
		}
		if (!$this->product_keywords->FldIsDetailKey) {
			$this->product_keywords->setFormValue($objForm->GetValue("x_product_keywords"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->product_id->CurrentValue = $this->product_id->FormValue;
		$this->product_brand->CurrentValue = $this->product_brand->FormValue;
		$this->product_cat->CurrentValue = $this->product_cat->FormValue;
		$this->product_title->CurrentValue = $this->product_title->FormValue;
		$this->product_price->CurrentValue = $this->product_price->FormValue;
		$this->product_dist->CurrentValue = $this->product_dist->FormValue;
		$this->product_desc->CurrentValue = $this->product_desc->FormValue;
		$this->top_sell->CurrentValue = $this->top_sell->FormValue;
		$this->condition->CurrentValue = $this->condition->FormValue;
		$this->product_keywords->CurrentValue = $this->product_keywords->FormValue;
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->ListSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		$this->product_brand->setDbValue($row['product_brand']);
		if (array_key_exists('EV__product_brand', $rs->fields)) {
			$this->product_brand->VirtualValue = $rs->fields('EV__product_brand'); // Set up virtual field value
		} else {
			$this->product_brand->VirtualValue = ""; // Clear value
		}
		$this->product_cat->setDbValue($row['product_cat']);
		if (array_key_exists('EV__product_cat', $rs->fields)) {
			$this->product_cat->VirtualValue = $rs->fields('EV__product_cat'); // Set up virtual field value
		} else {
			$this->product_cat->VirtualValue = ""; // Clear value
		}
		$this->product_title->setDbValue($row['product_title']);
		$this->product_price->setDbValue($row['product_price']);
		$this->product_dist->setDbValue($row['product_dist']);
		$this->product_desc->setDbValue($row['product_desc']);
		$this->product_image->Upload->DbValue = $row['product_image'];
		$this->product_image->setDbValue($this->product_image->Upload->DbValue);
		$this->product_image1->Upload->DbValue = $row['product_image1'];
		$this->product_image1->setDbValue($this->product_image1->Upload->DbValue);
		$this->product_image2->Upload->DbValue = $row['product_image2'];
		$this->product_image2->setDbValue($this->product_image2->Upload->DbValue);
		$this->product_image3->Upload->DbValue = $row['product_image3'];
		$this->product_image3->setDbValue($this->product_image3->Upload->DbValue);
		$this->top_sell->setDbValue($row['top_sell']);
		$this->condition->setDbValue($row['condition']);
		$this->product_keywords->setDbValue($row['product_keywords']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['product_id'] = NULL;
		$row['product_brand'] = NULL;
		$row['product_cat'] = NULL;
		$row['product_title'] = NULL;
		$row['product_price'] = NULL;
		$row['product_dist'] = NULL;
		$row['product_desc'] = NULL;
		$row['product_image'] = NULL;
		$row['product_image1'] = NULL;
		$row['product_image2'] = NULL;
		$row['product_image3'] = NULL;
		$row['top_sell'] = NULL;
		$row['condition'] = NULL;
		$row['product_keywords'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->product_id->DbValue = $row['product_id'];
		$this->product_brand->DbValue = $row['product_brand'];
		$this->product_cat->DbValue = $row['product_cat'];
		$this->product_title->DbValue = $row['product_title'];
		$this->product_price->DbValue = $row['product_price'];
		$this->product_dist->DbValue = $row['product_dist'];
		$this->product_desc->DbValue = $row['product_desc'];
		$this->product_image->Upload->DbValue = $row['product_image'];
		$this->product_image1->Upload->DbValue = $row['product_image1'];
		$this->product_image2->Upload->DbValue = $row['product_image2'];
		$this->product_image3->Upload->DbValue = $row['product_image3'];
		$this->top_sell->DbValue = $row['top_sell'];
		$this->condition->DbValue = $row['condition'];
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
		// Convert decimal values if posted back

		if ($this->product_price->FormValue == $this->product_price->CurrentValue && is_numeric(ew_StrToFloat($this->product_price->CurrentValue)))
			$this->product_price->CurrentValue = ew_StrToFloat($this->product_price->CurrentValue);

		// Convert decimal values if posted back
		if ($this->product_dist->FormValue == $this->product_dist->CurrentValue && is_numeric(ew_StrToFloat($this->product_dist->CurrentValue)))
			$this->product_dist->CurrentValue = ew_StrToFloat($this->product_dist->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// product_id
			$this->product_id->EditAttrs["class"] = "form-control";
			$this->product_id->EditCustomAttributes = "";
			$this->product_id->EditValue = $this->product_id->CurrentValue;
			$this->product_id->ViewCustomAttributes = "";

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

			// product_cat
			$this->product_cat->EditCustomAttributes = "";
			if (trim(strval($this->product_cat->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`cat_id`" . ew_SearchString("=", $this->product_cat->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `cat_id`, `cat_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `brand_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `categories`";
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
			if (strval($this->product_price->EditValue) <> "" && is_numeric($this->product_price->EditValue)) $this->product_price->EditValue = ew_FormatNumber($this->product_price->EditValue, -2, -1, -2, 0);

			// product_dist
			$this->product_dist->EditAttrs["class"] = "form-control";
			$this->product_dist->EditCustomAttributes = "";
			$this->product_dist->EditValue = ew_HtmlEncode($this->product_dist->CurrentValue);
			$this->product_dist->PlaceHolder = ew_RemoveHtml($this->product_dist->FldCaption());
			if (strval($this->product_dist->EditValue) <> "" && is_numeric($this->product_dist->EditValue)) $this->product_dist->EditValue = ew_FormatNumber($this->product_dist->EditValue, -2, -1, -2, 0);

			// product_desc
			$this->product_desc->EditAttrs["class"] = "form-control";
			$this->product_desc->EditCustomAttributes = "";
			$this->product_desc->EditValue = ew_HtmlEncode($this->product_desc->CurrentValue);
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
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->product_image);

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
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->product_image1);

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
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->product_image2);

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
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->product_image3);

			// top_sell
			$this->top_sell->EditCustomAttributes = "";
			$this->top_sell->EditValue = $this->top_sell->Options(FALSE);

			// condition
			$this->condition->EditCustomAttributes = "";
			$this->condition->EditValue = $this->condition->Options(FALSE);

			// product_keywords
			$this->product_keywords->EditAttrs["class"] = "form-control";
			$this->product_keywords->EditCustomAttributes = "";
			$this->product_keywords->EditValue = ew_HtmlEncode($this->product_keywords->CurrentValue);
			$this->product_keywords->PlaceHolder = ew_RemoveHtml($this->product_keywords->FldCaption());

			// Edit refer script
			// product_id

			$this->product_id->LinkCustomAttributes = "";
			$this->product_id->HrefValue = "";

			// product_brand
			$this->product_brand->LinkCustomAttributes = "";
			$this->product_brand->HrefValue = "";

			// product_cat
			$this->product_cat->LinkCustomAttributes = "";
			$this->product_cat->HrefValue = "";

			// product_title
			$this->product_title->LinkCustomAttributes = "";
			$this->product_title->HrefValue = "";

			// product_price
			$this->product_price->LinkCustomAttributes = "";
			$this->product_price->HrefValue = "";

			// product_dist
			$this->product_dist->LinkCustomAttributes = "";
			$this->product_dist->HrefValue = "";

			// product_desc
			$this->product_desc->LinkCustomAttributes = "";
			$this->product_desc->HrefValue = "";

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

			// top_sell
			$this->top_sell->LinkCustomAttributes = "";
			$this->top_sell->HrefValue = "";

			// condition
			$this->condition->LinkCustomAttributes = "";
			$this->condition->HrefValue = "";

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
		if (!$this->product_brand->FldIsDetailKey && !is_null($this->product_brand->FormValue) && $this->product_brand->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_brand->FldCaption(), $this->product_brand->ReqErrMsg));
		}
		if (!$this->product_cat->FldIsDetailKey && !is_null($this->product_cat->FormValue) && $this->product_cat->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_cat->FldCaption(), $this->product_cat->ReqErrMsg));
		}
		if (!$this->product_title->FldIsDetailKey && !is_null($this->product_title->FormValue) && $this->product_title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_title->FldCaption(), $this->product_title->ReqErrMsg));
		}
		if (!$this->product_price->FldIsDetailKey && !is_null($this->product_price->FormValue) && $this->product_price->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_price->FldCaption(), $this->product_price->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->product_price->FormValue)) {
			ew_AddMessage($gsFormError, $this->product_price->FldErrMsg());
		}
		if (!$this->product_dist->FldIsDetailKey && !is_null($this->product_dist->FormValue) && $this->product_dist->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_dist->FldCaption(), $this->product_dist->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->product_dist->FormValue)) {
			ew_AddMessage($gsFormError, $this->product_dist->FldErrMsg());
		}
		if (!$this->product_desc->FldIsDetailKey && !is_null($this->product_desc->FormValue) && $this->product_desc->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_desc->FldCaption(), $this->product_desc->ReqErrMsg));
		}
		if ($this->product_image->Upload->FileName == "" && !$this->product_image->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_image->FldCaption(), $this->product_image->ReqErrMsg));
		}
		if ($this->product_image1->Upload->FileName == "" && !$this->product_image1->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_image1->FldCaption(), $this->product_image1->ReqErrMsg));
		}
		if ($this->product_image2->Upload->FileName == "" && !$this->product_image2->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_image2->FldCaption(), $this->product_image2->ReqErrMsg));
		}
		if ($this->product_image3->Upload->FileName == "" && !$this->product_image3->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_image3->FldCaption(), $this->product_image3->ReqErrMsg));
		}
		if ($this->top_sell->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->top_sell->FldCaption(), $this->top_sell->ReqErrMsg));
		}
		if ($this->condition->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->condition->FldCaption(), $this->condition->ReqErrMsg));
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
			$this->product_image->OldUploadPath = "../uploads/product/";
			$this->product_image->UploadPath = $this->product_image->OldUploadPath;
			$this->product_image1->OldUploadPath = "../uploads/product/";
			$this->product_image1->UploadPath = $this->product_image1->OldUploadPath;
			$this->product_image2->OldUploadPath = "../uploads/product/";
			$this->product_image2->UploadPath = $this->product_image2->OldUploadPath;
			$this->product_image3->OldUploadPath = "../uploads/product/";
			$this->product_image3->UploadPath = $this->product_image3->OldUploadPath;
			$rsnew = array();

			// product_brand
			$this->product_brand->SetDbValueDef($rsnew, $this->product_brand->CurrentValue, 0, $this->product_brand->ReadOnly);

			// product_cat
			$this->product_cat->SetDbValueDef($rsnew, $this->product_cat->CurrentValue, 0, $this->product_cat->ReadOnly);

			// product_title
			$this->product_title->SetDbValueDef($rsnew, $this->product_title->CurrentValue, "", $this->product_title->ReadOnly);

			// product_price
			$this->product_price->SetDbValueDef($rsnew, $this->product_price->CurrentValue, 0, $this->product_price->ReadOnly);

			// product_dist
			$this->product_dist->SetDbValueDef($rsnew, $this->product_dist->CurrentValue, 0, $this->product_dist->ReadOnly);

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
				$this->product_image->ImageWidth = 450; // Resize width
				$this->product_image->ImageHeight = 600; // Resize height
			}

			// product_image1
			if ($this->product_image1->Visible && !$this->product_image1->ReadOnly && !$this->product_image1->Upload->KeepFile) {
				$this->product_image1->Upload->DbValue = $rsold['product_image1']; // Get original value
				if ($this->product_image1->Upload->FileName == "") {
					$rsnew['product_image1'] = NULL;
				} else {
					$rsnew['product_image1'] = $this->product_image1->Upload->FileName;
				}
				$this->product_image1->ImageWidth = 450; // Resize width
				$this->product_image1->ImageHeight = 600; // Resize height
			}

			// product_image2
			if ($this->product_image2->Visible && !$this->product_image2->ReadOnly && !$this->product_image2->Upload->KeepFile) {
				$this->product_image2->Upload->DbValue = $rsold['product_image2']; // Get original value
				if ($this->product_image2->Upload->FileName == "") {
					$rsnew['product_image2'] = NULL;
				} else {
					$rsnew['product_image2'] = $this->product_image2->Upload->FileName;
				}
				$this->product_image2->ImageWidth = 450; // Resize width
				$this->product_image2->ImageHeight = 600; // Resize height
			}

			// product_image3
			if ($this->product_image3->Visible && !$this->product_image3->ReadOnly && !$this->product_image3->Upload->KeepFile) {
				$this->product_image3->Upload->DbValue = $rsold['product_image3']; // Get original value
				if ($this->product_image3->Upload->FileName == "") {
					$rsnew['product_image3'] = NULL;
				} else {
					$rsnew['product_image3'] = $this->product_image3->Upload->FileName;
				}
				$this->product_image3->ImageWidth = 450; // Resize width
				$this->product_image3->ImageHeight = 600; // Resize height
			}

			// top_sell
			$this->top_sell->SetDbValueDef($rsnew, $this->top_sell->CurrentValue, 0, $this->top_sell->ReadOnly);

			// condition
			$this->condition->SetDbValueDef($rsnew, $this->condition->CurrentValue, "", $this->condition->ReadOnly);

			// product_keywords
			$this->product_keywords->SetDbValueDef($rsnew, $this->product_keywords->CurrentValue, "", $this->product_keywords->ReadOnly);
			if ($this->product_image->Visible && !$this->product_image->Upload->KeepFile) {
				$this->product_image->UploadPath = "../uploads/product/";
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
			if ($this->product_image1->Visible && !$this->product_image1->Upload->KeepFile) {
				$this->product_image1->UploadPath = "../uploads/product/";
				$OldFiles = ew_Empty($this->product_image1->Upload->DbValue) ? array() : array($this->product_image1->Upload->DbValue);
				if (!ew_Empty($this->product_image1->Upload->FileName)) {
					$NewFiles = array($this->product_image1->Upload->FileName);
					$NewFileCount = count($NewFiles);
					for ($i = 0; $i < $NewFileCount; $i++) {
						$fldvar = ($this->product_image1->Upload->Index < 0) ? $this->product_image1->FldVar : substr($this->product_image1->FldVar, 0, 1) . $this->product_image1->Upload->Index . substr($this->product_image1->FldVar, 1);
						if ($NewFiles[$i] <> "") {
							$file = $NewFiles[$i];
							if (file_exists(ew_UploadTempPath($fldvar, $this->product_image1->TblVar) . $file)) {
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
								$file1 = ew_UploadFileNameEx($this->product_image1->PhysicalUploadPath(), $file); // Get new file name
								if ($file1 <> $file) { // Rename temp file
									while (file_exists(ew_UploadTempPath($fldvar, $this->product_image1->TblVar) . $file1) || file_exists($this->product_image1->PhysicalUploadPath() . $file1)) // Make sure no file name clash
										$file1 = ew_UniqueFilename($this->product_image1->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
									rename(ew_UploadTempPath($fldvar, $this->product_image1->TblVar) . $file, ew_UploadTempPath($fldvar, $this->product_image1->TblVar) . $file1);
									$NewFiles[$i] = $file1;
								}
							}
						}
					}
					$this->product_image1->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
					$this->product_image1->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
					$this->product_image1->SetDbValueDef($rsnew, $this->product_image1->Upload->FileName, "", $this->product_image1->ReadOnly);
				}
			}
			if ($this->product_image2->Visible && !$this->product_image2->Upload->KeepFile) {
				$this->product_image2->UploadPath = "../uploads/product/";
				$OldFiles = ew_Empty($this->product_image2->Upload->DbValue) ? array() : array($this->product_image2->Upload->DbValue);
				if (!ew_Empty($this->product_image2->Upload->FileName)) {
					$NewFiles = array($this->product_image2->Upload->FileName);
					$NewFileCount = count($NewFiles);
					for ($i = 0; $i < $NewFileCount; $i++) {
						$fldvar = ($this->product_image2->Upload->Index < 0) ? $this->product_image2->FldVar : substr($this->product_image2->FldVar, 0, 1) . $this->product_image2->Upload->Index . substr($this->product_image2->FldVar, 1);
						if ($NewFiles[$i] <> "") {
							$file = $NewFiles[$i];
							if (file_exists(ew_UploadTempPath($fldvar, $this->product_image2->TblVar) . $file)) {
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
								$file1 = ew_UploadFileNameEx($this->product_image2->PhysicalUploadPath(), $file); // Get new file name
								if ($file1 <> $file) { // Rename temp file
									while (file_exists(ew_UploadTempPath($fldvar, $this->product_image2->TblVar) . $file1) || file_exists($this->product_image2->PhysicalUploadPath() . $file1)) // Make sure no file name clash
										$file1 = ew_UniqueFilename($this->product_image2->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
									rename(ew_UploadTempPath($fldvar, $this->product_image2->TblVar) . $file, ew_UploadTempPath($fldvar, $this->product_image2->TblVar) . $file1);
									$NewFiles[$i] = $file1;
								}
							}
						}
					}
					$this->product_image2->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
					$this->product_image2->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
					$this->product_image2->SetDbValueDef($rsnew, $this->product_image2->Upload->FileName, "", $this->product_image2->ReadOnly);
				}
			}
			if ($this->product_image3->Visible && !$this->product_image3->Upload->KeepFile) {
				$this->product_image3->UploadPath = "../uploads/product/";
				$OldFiles = ew_Empty($this->product_image3->Upload->DbValue) ? array() : array($this->product_image3->Upload->DbValue);
				if (!ew_Empty($this->product_image3->Upload->FileName)) {
					$NewFiles = array($this->product_image3->Upload->FileName);
					$NewFileCount = count($NewFiles);
					for ($i = 0; $i < $NewFileCount; $i++) {
						$fldvar = ($this->product_image3->Upload->Index < 0) ? $this->product_image3->FldVar : substr($this->product_image3->FldVar, 0, 1) . $this->product_image3->Upload->Index . substr($this->product_image3->FldVar, 1);
						if ($NewFiles[$i] <> "") {
							$file = $NewFiles[$i];
							if (file_exists(ew_UploadTempPath($fldvar, $this->product_image3->TblVar) . $file)) {
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
								$file1 = ew_UploadFileNameEx($this->product_image3->PhysicalUploadPath(), $file); // Get new file name
								if ($file1 <> $file) { // Rename temp file
									while (file_exists(ew_UploadTempPath($fldvar, $this->product_image3->TblVar) . $file1) || file_exists($this->product_image3->PhysicalUploadPath() . $file1)) // Make sure no file name clash
										$file1 = ew_UniqueFilename($this->product_image3->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
									rename(ew_UploadTempPath($fldvar, $this->product_image3->TblVar) . $file, ew_UploadTempPath($fldvar, $this->product_image3->TblVar) . $file1);
									$NewFiles[$i] = $file1;
								}
							}
						}
					}
					$this->product_image3->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
					$this->product_image3->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
					$this->product_image3->SetDbValueDef($rsnew, $this->product_image3->Upload->FileName, "", $this->product_image3->ReadOnly);
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
										if (!$this->product_image->Upload->ResizeAndSaveToFile($this->product_image->ImageWidth, $this->product_image->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY, $NewFiles[$i], TRUE, $i)) {
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
					if ($this->product_image1->Visible && !$this->product_image1->Upload->KeepFile) {
						$OldFiles = ew_Empty($this->product_image1->Upload->DbValue) ? array() : array($this->product_image1->Upload->DbValue);
						if (!ew_Empty($this->product_image1->Upload->FileName)) {
							$NewFiles = array($this->product_image1->Upload->FileName);
							$NewFiles2 = array($rsnew['product_image1']);
							$NewFileCount = count($NewFiles);
							for ($i = 0; $i < $NewFileCount; $i++) {
								$fldvar = ($this->product_image1->Upload->Index < 0) ? $this->product_image1->FldVar : substr($this->product_image1->FldVar, 0, 1) . $this->product_image1->Upload->Index . substr($this->product_image1->FldVar, 1);
								if ($NewFiles[$i] <> "") {
									$file = ew_UploadTempPath($fldvar, $this->product_image1->TblVar) . $NewFiles[$i];
									if (file_exists($file)) {
										if (@$NewFiles2[$i] <> "") // Use correct file name
											$NewFiles[$i] = $NewFiles2[$i];
										if (!$this->product_image1->Upload->ResizeAndSaveToFile($this->product_image1->ImageWidth, $this->product_image1->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY, $NewFiles[$i], TRUE, $i)) {
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
								@unlink($this->product_image1->OldPhysicalUploadPath() . $OldFiles[$i]);
						}
					}
					if ($this->product_image2->Visible && !$this->product_image2->Upload->KeepFile) {
						$OldFiles = ew_Empty($this->product_image2->Upload->DbValue) ? array() : array($this->product_image2->Upload->DbValue);
						if (!ew_Empty($this->product_image2->Upload->FileName)) {
							$NewFiles = array($this->product_image2->Upload->FileName);
							$NewFiles2 = array($rsnew['product_image2']);
							$NewFileCount = count($NewFiles);
							for ($i = 0; $i < $NewFileCount; $i++) {
								$fldvar = ($this->product_image2->Upload->Index < 0) ? $this->product_image2->FldVar : substr($this->product_image2->FldVar, 0, 1) . $this->product_image2->Upload->Index . substr($this->product_image2->FldVar, 1);
								if ($NewFiles[$i] <> "") {
									$file = ew_UploadTempPath($fldvar, $this->product_image2->TblVar) . $NewFiles[$i];
									if (file_exists($file)) {
										if (@$NewFiles2[$i] <> "") // Use correct file name
											$NewFiles[$i] = $NewFiles2[$i];
										if (!$this->product_image2->Upload->ResizeAndSaveToFile($this->product_image2->ImageWidth, $this->product_image2->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY, $NewFiles[$i], TRUE, $i)) {
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
								@unlink($this->product_image2->OldPhysicalUploadPath() . $OldFiles[$i]);
						}
					}
					if ($this->product_image3->Visible && !$this->product_image3->Upload->KeepFile) {
						$OldFiles = ew_Empty($this->product_image3->Upload->DbValue) ? array() : array($this->product_image3->Upload->DbValue);
						if (!ew_Empty($this->product_image3->Upload->FileName)) {
							$NewFiles = array($this->product_image3->Upload->FileName);
							$NewFiles2 = array($rsnew['product_image3']);
							$NewFileCount = count($NewFiles);
							for ($i = 0; $i < $NewFileCount; $i++) {
								$fldvar = ($this->product_image3->Upload->Index < 0) ? $this->product_image3->FldVar : substr($this->product_image3->FldVar, 0, 1) . $this->product_image3->Upload->Index . substr($this->product_image3->FldVar, 1);
								if ($NewFiles[$i] <> "") {
									$file = ew_UploadTempPath($fldvar, $this->product_image3->TblVar) . $NewFiles[$i];
									if (file_exists($file)) {
										if (@$NewFiles2[$i] <> "") // Use correct file name
											$NewFiles[$i] = $NewFiles2[$i];
										if (!$this->product_image3->Upload->ResizeAndSaveToFile($this->product_image3->ImageWidth, $this->product_image3->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY, $NewFiles[$i], TRUE, $i)) {
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
								@unlink($this->product_image3->OldPhysicalUploadPath() . $OldFiles[$i]);
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

		// product_image1
		ew_CleanUploadTempPath($this->product_image1, $this->product_image1->Upload->Index);

		// product_image2
		ew_CleanUploadTempPath($this->product_image2, $this->product_image2->Upload->Index);

		// product_image3
		ew_CleanUploadTempPath($this->product_image3, $this->product_image3->Upload->Index);
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
		case "x_product_cat":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `cat_id` AS `LinkFld`, `cat_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categories`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`cat_title`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`cat_id` IN ({filter_value})', "t0" => "3", "fn0" => "", "f1" => '`brand_id` IN ({filter_value})', "t1" => "3", "fn1" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->product_cat, $sWhereWrk); // Call Lookup Selecting
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
			elm = this.GetElements("x" + infix + "_product_brand");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_brand->FldCaption(), $products->product_brand->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product_cat");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_cat->FldCaption(), $products->product_cat->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_title->FldCaption(), $products->product_title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product_price");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_price->FldCaption(), $products->product_price->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product_price");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($products->product_price->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_product_dist");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_dist->FldCaption(), $products->product_dist->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product_dist");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($products->product_dist->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_product_desc");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_desc->FldCaption(), $products->product_desc->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_product_image");
			elm = this.GetElements("fn_x" + infix + "_product_image");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_image->FldCaption(), $products->product_image->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_product_image1");
			elm = this.GetElements("fn_x" + infix + "_product_image1");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_image1->FldCaption(), $products->product_image1->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_product_image2");
			elm = this.GetElements("fn_x" + infix + "_product_image2");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_image2->FldCaption(), $products->product_image2->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_product_image3");
			elm = this.GetElements("fn_x" + infix + "_product_image3");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_image3->FldCaption(), $products->product_image3->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_top_sell");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->top_sell->FldCaption(), $products->top_sell->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_condition");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->condition->FldCaption(), $products->condition->ReqErrMsg)) ?>");
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
fproductsedit.Lists["x_product_brand"] = {"LinkField":"x_brand_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_brand_title","","",""],"ParentFields":[],"ChildFields":["x_product_cat"],"FilterFields":[],"Options":[],"Template":"","LinkTable":"brands"};
fproductsedit.Lists["x_product_brand"].Data = "<?php echo $products_edit->product_brand->LookupFilterQuery(FALSE, "edit") ?>";
fproductsedit.Lists["x_product_cat"] = {"LinkField":"x_cat_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_cat_title","","",""],"ParentFields":["x_product_brand"],"ChildFields":[],"FilterFields":["x_brand_id"],"Options":[],"Template":"","LinkTable":"categories"};
fproductsedit.Lists["x_product_cat"].Data = "<?php echo $products_edit->product_cat->LookupFilterQuery(FALSE, "edit") ?>";
fproductsedit.Lists["x_top_sell"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fproductsedit.Lists["x_top_sell"].Options = <?php echo json_encode($products_edit->top_sell->Options()) ?>;
fproductsedit.Lists["x_condition"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fproductsedit.Lists["x_condition"].Options = <?php echo json_encode($products_edit->condition->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $products_edit->ShowPageHeader(); ?>
<?php
$products_edit->ShowMessage();
?>
<?php if (!$products_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($products_edit->Pager)) $products_edit->Pager = new cPrevNextPager($products_edit->StartRec, $products_edit->DisplayRecs, $products_edit->TotalRecs, $products_edit->AutoHidePager) ?>
<?php if ($products_edit->Pager->RecordCount > 0 && $products_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($products_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $products_edit->PageUrl() ?>start=<?php echo $products_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($products_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $products_edit->PageUrl() ?>start=<?php echo $products_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $products_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($products_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $products_edit->PageUrl() ?>start=<?php echo $products_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($products_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $products_edit->PageUrl() ?>start=<?php echo $products_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $products_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
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
<?php if ($products->product_brand->Visible) { // product_brand ?>
	<div id="r_product_brand" class="form-group">
		<label id="elh_products_product_brand" for="x_product_brand" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->product_brand->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->product_brand->CellAttributes() ?>>
<span id="el_products_product_brand">
<?php $products->product_brand->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$products->product_brand->EditAttrs["onchange"]; ?>
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
<?php if ($products->product_dist->Visible) { // product_dist ?>
	<div id="r_product_dist" class="form-group">
		<label id="elh_products_product_dist" for="x_product_dist" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->product_dist->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->product_dist->CellAttributes() ?>>
<span id="el_products_product_dist">
<input type="text" data-table="products" data-field="x_product_dist" name="x_product_dist" id="x_product_dist" size="30" placeholder="<?php echo ew_HtmlEncode($products->product_dist->getPlaceHolder()) ?>" value="<?php echo $products->product_dist->EditValue ?>"<?php echo $products->product_dist->EditAttributes() ?>>
</span>
<?php echo $products->product_dist->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_desc->Visible) { // product_desc ?>
	<div id="r_product_desc" class="form-group">
		<label id="elh_products_product_desc" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->product_desc->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->product_desc->CellAttributes() ?>>
<span id="el_products_product_desc">
<?php ew_AppendClass($products->product_desc->EditAttrs["class"], "editor"); ?>
<textarea data-table="products" data-field="x_product_desc" name="x_product_desc" id="x_product_desc" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($products->product_desc->getPlaceHolder()) ?>"<?php echo $products->product_desc->EditAttributes() ?>><?php echo $products->product_desc->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fproductsedit", "x_product_desc", 35, 4, <?php echo ($products->product_desc->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
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
<?php if ($products->product_image1->Visible) { // product_image1 ?>
	<div id="r_product_image1" class="form-group">
		<label id="elh_products_product_image1" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->product_image1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->product_image1->CellAttributes() ?>>
<span id="el_products_product_image1">
<div id="fd_x_product_image1">
<span title="<?php echo $products->product_image1->FldTitle() ? $products->product_image1->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_image1->ReadOnly || $products->product_image1->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_image1" name="x_product_image1" id="x_product_image1"<?php echo $products->product_image1->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_product_image1" id= "fn_x_product_image1" value="<?php echo $products->product_image1->Upload->FileName ?>">
<?php if (@$_POST["fa_x_product_image1"] == "0") { ?>
<input type="hidden" name="fa_x_product_image1" id= "fa_x_product_image1" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_product_image1" id= "fa_x_product_image1" value="1">
<?php } ?>
<input type="hidden" name="fs_x_product_image1" id= "fs_x_product_image1" value="65535">
<input type="hidden" name="fx_x_product_image1" id= "fx_x_product_image1" value="<?php echo $products->product_image1->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_product_image1" id= "fm_x_product_image1" value="<?php echo $products->product_image1->UploadMaxFileSize ?>">
</div>
<table id="ft_x_product_image1" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $products->product_image1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_image2->Visible) { // product_image2 ?>
	<div id="r_product_image2" class="form-group">
		<label id="elh_products_product_image2" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->product_image2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->product_image2->CellAttributes() ?>>
<span id="el_products_product_image2">
<div id="fd_x_product_image2">
<span title="<?php echo $products->product_image2->FldTitle() ? $products->product_image2->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_image2->ReadOnly || $products->product_image2->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_image2" name="x_product_image2" id="x_product_image2"<?php echo $products->product_image2->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_product_image2" id= "fn_x_product_image2" value="<?php echo $products->product_image2->Upload->FileName ?>">
<?php if (@$_POST["fa_x_product_image2"] == "0") { ?>
<input type="hidden" name="fa_x_product_image2" id= "fa_x_product_image2" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_product_image2" id= "fa_x_product_image2" value="1">
<?php } ?>
<input type="hidden" name="fs_x_product_image2" id= "fs_x_product_image2" value="65535">
<input type="hidden" name="fx_x_product_image2" id= "fx_x_product_image2" value="<?php echo $products->product_image2->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_product_image2" id= "fm_x_product_image2" value="<?php echo $products->product_image2->UploadMaxFileSize ?>">
</div>
<table id="ft_x_product_image2" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $products->product_image2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_image3->Visible) { // product_image3 ?>
	<div id="r_product_image3" class="form-group">
		<label id="elh_products_product_image3" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->product_image3->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->product_image3->CellAttributes() ?>>
<span id="el_products_product_image3">
<div id="fd_x_product_image3">
<span title="<?php echo $products->product_image3->FldTitle() ? $products->product_image3->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_image3->ReadOnly || $products->product_image3->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_image3" name="x_product_image3" id="x_product_image3"<?php echo $products->product_image3->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_product_image3" id= "fn_x_product_image3" value="<?php echo $products->product_image3->Upload->FileName ?>">
<?php if (@$_POST["fa_x_product_image3"] == "0") { ?>
<input type="hidden" name="fa_x_product_image3" id= "fa_x_product_image3" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_product_image3" id= "fa_x_product_image3" value="1">
<?php } ?>
<input type="hidden" name="fs_x_product_image3" id= "fs_x_product_image3" value="65535">
<input type="hidden" name="fx_x_product_image3" id= "fx_x_product_image3" value="<?php echo $products->product_image3->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_product_image3" id= "fm_x_product_image3" value="<?php echo $products->product_image3->UploadMaxFileSize ?>">
</div>
<table id="ft_x_product_image3" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $products->product_image3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->top_sell->Visible) { // top_sell ?>
	<div id="r_top_sell" class="form-group">
		<label id="elh_products_top_sell" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->top_sell->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->top_sell->CellAttributes() ?>>
<span id="el_products_top_sell">
<div id="tp_x_top_sell" class="ewTemplate"><input type="radio" data-table="products" data-field="x_top_sell" data-value-separator="<?php echo $products->top_sell->DisplayValueSeparatorAttribute() ?>" name="x_top_sell" id="x_top_sell" value="{value}"<?php echo $products->top_sell->EditAttributes() ?>></div>
<div id="dsl_x_top_sell" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $products->top_sell->RadioButtonListHtml(FALSE, "x_top_sell") ?>
</div></div>
</span>
<?php echo $products->top_sell->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->condition->Visible) { // condition ?>
	<div id="r_condition" class="form-group">
		<label id="elh_products_condition" class="<?php echo $products_edit->LeftColumnClass ?>"><?php echo $products->condition->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $products_edit->RightColumnClass ?>"><div<?php echo $products->condition->CellAttributes() ?>>
<span id="el_products_condition">
<div id="tp_x_condition" class="ewTemplate"><input type="radio" data-table="products" data-field="x_condition" data-value-separator="<?php echo $products->condition->DisplayValueSeparatorAttribute() ?>" name="x_condition" id="x_condition" value="{value}"<?php echo $products->condition->EditAttributes() ?>></div>
<div id="dsl_x_condition" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $products->condition->RadioButtonListHtml(FALSE, "x_condition") ?>
</div></div>
</span>
<?php echo $products->condition->CustomMsg ?></div></div>
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
<?php if (!$products_edit->IsModal) { ?>
<?php if (!isset($products_edit->Pager)) $products_edit->Pager = new cPrevNextPager($products_edit->StartRec, $products_edit->DisplayRecs, $products_edit->TotalRecs, $products_edit->AutoHidePager) ?>
<?php if ($products_edit->Pager->RecordCount > 0 && $products_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($products_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $products_edit->PageUrl() ?>start=<?php echo $products_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($products_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $products_edit->PageUrl() ?>start=<?php echo $products_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $products_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($products_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $products_edit->PageUrl() ?>start=<?php echo $products_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($products_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $products_edit->PageUrl() ?>start=<?php echo $products_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $products_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
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
