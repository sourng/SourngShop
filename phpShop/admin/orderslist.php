<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "ordersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$orders_list = NULL; // Initialize page object first

class corders_list extends corders {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{DE3D48ED-60FB-4F03-A5AD-139B7A8A8A85}';

	// Table name
	var $TableName = 'orders';

	// Page object name
	var $PageObjName = 'orders_list';

	// Grid form hidden field names
	var $FormName = 'forderslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Table object (orders)
		if (!isset($GLOBALS["orders"]) || get_class($GLOBALS["orders"]) == "corders") {
			$GLOBALS["orders"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["orders"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "ordersadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "ordersdelete.php";
		$this->MultiUpdateUrl = "ordersupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'orders', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption forderslistsrch";

		// List actions
		$this->ListActions = new cListActions();
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->order_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->order_id->Visible = FALSE;
		$this->user_id->SetVisibility();
		$this->product_id->SetVisibility();
		$this->qty->SetVisibility();
		$this->trx_id->SetVisibility();
		$this->p_status->SetVisibility();

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
		global $EW_EXPORT, $orders;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($orders);
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
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $AutoHidePager = EW_AUTO_HIDE_PAGER;
	var $AutoHidePageSizeSelector = EW_AUTO_HIDE_PAGE_SIZE_SELECTOR;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security, $EW_EXPORT;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to grid edit mode
				if ($this->CurrentAction == "gridedit")
					$this->GridEditMode();

				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();

				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();

				// Switch to grid add mode
				if ($this->CurrentAction == "gridadd")
					$this->GridAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Grid Update
					if (($this->CurrentAction == "gridupdate" || $this->CurrentAction == "gridoverwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridedit") {
						if ($this->ValidateGridForm()) {
							$bGridUpdate = $this->GridUpdate();
						} else {
							$bGridUpdate = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridUpdate) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
						}
					}

					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();

					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();

					// Grid Insert
					if ($this->CurrentAction == "gridinsert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridadd") {
						if ($this->ValidateGridForm()) {
							$bGridInsert = $this->GridInsert();
						} else {
							$bGridInsert = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridInsert) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridadd"; // Stay in Grid Add mode
						}
					}
				}
			}

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->Command <> "json" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetupSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->Command <> "json" && $this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		if ($this->Command <> "json")
			$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif ($this->Command <> "json") {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter
		if ($this->Command == "json") {
			$this->UseSessionForListSQL = FALSE; // Do not use session for ListSQL
			$this->CurrentFilter = $sFilter;
		} else {
			$this->setSessionWhere($sFilter);
			$this->CurrentFilter = "";
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->ListRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Exit inline mode
	function ClearInlineMode() {
		$this->setKey("order_id", ""); // Clear inline edit key
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		$bInlineEdit = TRUE;
		if (isset($_GET["order_id"])) {
			$this->order_id->setQueryStringValue($_GET["order_id"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("order_id", $this->order_id->CurrentValue); // Set up inline edit key
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}

	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError;
		$objForm->Index = 1;
		$this->LoadFormValues(); // Get form values

		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {
			$bInlineUpdate = FALSE;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			if ($this->SetupKeyValues($rowkey)) { // Set up key values
				if ($this->CheckInlineEditKey()) { // Check key
					$this->SendEmail = TRUE; // Send email on update success
					$bInlineUpdate = $this->EditRow(); // Update record
				} else {
					$bInlineUpdate = FALSE;
				}
			}
		}
		if ($bInlineUpdate) { // Update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Cancel event
			$this->CurrentAction = "edit"; // Stay in edit mode
		}
	}

	// Check Inline Edit key
	function CheckInlineEditKey() {

		//CheckInlineEditKey = True
		if (strval($this->getKey("order_id")) <> strval($this->order_id->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if ($this->CurrentAction == "copy") {
			if (@$_GET["order_id"] <> "") {
				$this->order_id->setQueryStringValue($_GET["order_id"]);
				$this->setKey("order_id", $this->order_id->CurrentValue); // Set up key
			} else {
				$this->setKey("order_id", ""); // Clear key
				$this->CurrentAction = "add";
			}
		}
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}

	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError;
		$this->LoadOldRecord(); // Load old record
		$objForm->Index = 0;
		$this->LoadFormValues(); // Get form values

		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
			return;
		}
		$this->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
		}
	}

	// Perform update to grid
	function GridUpdate() {
		global $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		if ($this->CurrentFilter == "")
			$this->CurrentFilter = "0=1";
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}

		// Call Grid Updating event
		if (!$this->Grid_Updating($rsold)) {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("GridEditCancelled")); // Set grid edit cancelled message
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
		}
		return $bGridUpdate;
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->order_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->order_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Perform Grid Add
	function GridInsert() {
		global $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;
		$conn = &$this->Connection();

		// Call Grid Inserting event
		if (!$this->Grid_Inserting()) {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("GridAddCancelled")); // Set grid add cancelled message
			}
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->order_id->CurrentValue;

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->setFailureMessage($Language->Phrase("NoAddRecord"));
			$bGridInsert = FALSE;
		}
		if ($bGridInsert) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Inserted event
			$this->Grid_Inserted($rsnew);
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("InsertSuccess")); // Set up insert success message
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_user_id") && $objForm->HasValue("o_user_id") && $this->user_id->CurrentValue <> $this->user_id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_product_id") && $objForm->HasValue("o_product_id") && $this->product_id->CurrentValue <> $this->product_id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qty") && $objForm->HasValue("o_qty") && $this->qty->CurrentValue <> $this->qty->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_trx_id") && $objForm->HasValue("o_trx_id") && $this->trx_id->CurrentValue <> $this->trx_id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_p_status") && $objForm->HasValue("o_p_status") && $this->p_status->CurrentValue <> $this->p_status->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Get all form values of the grid
	function GetGridFormValues() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;
		$rows = array();

		// Loop through all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else {
					$rows[] = $this->GetFieldValues("FormValue"); // Return row as array
				}
			}
		}
		return $rows; // Return as array of array
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Initialize
		$sFilterList = "";
		$sSavedFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->order_id->AdvancedSearch->ToJson(), ","); // Field order_id
		$sFilterList = ew_Concat($sFilterList, $this->user_id->AdvancedSearch->ToJson(), ","); // Field user_id
		$sFilterList = ew_Concat($sFilterList, $this->product_id->AdvancedSearch->ToJson(), ","); // Field product_id
		$sFilterList = ew_Concat($sFilterList, $this->qty->AdvancedSearch->ToJson(), ","); // Field qty
		$sFilterList = ew_Concat($sFilterList, $this->trx_id->AdvancedSearch->ToJson(), ","); // Field trx_id
		$sFilterList = ew_Concat($sFilterList, $this->p_status->AdvancedSearch->ToJson(), ","); // Field p_status
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = @$_POST["filters"];
			$UserProfile->SetSearchFilters(CurrentUserName(), "forderslistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(@$_POST["filter"], TRUE);
		$this->Command = "search";

		// Field order_id
		$this->order_id->AdvancedSearch->SearchValue = @$filter["x_order_id"];
		$this->order_id->AdvancedSearch->SearchOperator = @$filter["z_order_id"];
		$this->order_id->AdvancedSearch->SearchCondition = @$filter["v_order_id"];
		$this->order_id->AdvancedSearch->SearchValue2 = @$filter["y_order_id"];
		$this->order_id->AdvancedSearch->SearchOperator2 = @$filter["w_order_id"];
		$this->order_id->AdvancedSearch->Save();

		// Field user_id
		$this->user_id->AdvancedSearch->SearchValue = @$filter["x_user_id"];
		$this->user_id->AdvancedSearch->SearchOperator = @$filter["z_user_id"];
		$this->user_id->AdvancedSearch->SearchCondition = @$filter["v_user_id"];
		$this->user_id->AdvancedSearch->SearchValue2 = @$filter["y_user_id"];
		$this->user_id->AdvancedSearch->SearchOperator2 = @$filter["w_user_id"];
		$this->user_id->AdvancedSearch->Save();

		// Field product_id
		$this->product_id->AdvancedSearch->SearchValue = @$filter["x_product_id"];
		$this->product_id->AdvancedSearch->SearchOperator = @$filter["z_product_id"];
		$this->product_id->AdvancedSearch->SearchCondition = @$filter["v_product_id"];
		$this->product_id->AdvancedSearch->SearchValue2 = @$filter["y_product_id"];
		$this->product_id->AdvancedSearch->SearchOperator2 = @$filter["w_product_id"];
		$this->product_id->AdvancedSearch->Save();

		// Field qty
		$this->qty->AdvancedSearch->SearchValue = @$filter["x_qty"];
		$this->qty->AdvancedSearch->SearchOperator = @$filter["z_qty"];
		$this->qty->AdvancedSearch->SearchCondition = @$filter["v_qty"];
		$this->qty->AdvancedSearch->SearchValue2 = @$filter["y_qty"];
		$this->qty->AdvancedSearch->SearchOperator2 = @$filter["w_qty"];
		$this->qty->AdvancedSearch->Save();

		// Field trx_id
		$this->trx_id->AdvancedSearch->SearchValue = @$filter["x_trx_id"];
		$this->trx_id->AdvancedSearch->SearchOperator = @$filter["z_trx_id"];
		$this->trx_id->AdvancedSearch->SearchCondition = @$filter["v_trx_id"];
		$this->trx_id->AdvancedSearch->SearchValue2 = @$filter["y_trx_id"];
		$this->trx_id->AdvancedSearch->SearchOperator2 = @$filter["w_trx_id"];
		$this->trx_id->AdvancedSearch->Save();

		// Field p_status
		$this->p_status->AdvancedSearch->SearchValue = @$filter["x_p_status"];
		$this->p_status->AdvancedSearch->SearchOperator = @$filter["z_p_status"];
		$this->p_status->AdvancedSearch->SearchCondition = @$filter["v_p_status"];
		$this->p_status->AdvancedSearch->SearchValue2 = @$filter["y_p_status"];
		$this->p_status->AdvancedSearch->SearchOperator2 = @$filter["w_p_status"];
		$this->p_status->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->trx_id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->p_status, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .= "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

		// Get search SQL
		if ($sSearchKeyword <> "") {
			$ar = $this->BasicSearch->KeywordList($Default);

			// Search keyword in any fields
			if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
				foreach ($ar as $sKeyword) {
					if ($sKeyword <> "") {
						if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
						$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
					}
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
			}
			if (!$Default && in_array($this->Command, array("", "reset", "resetall"))) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->order_id); // order_id
			$this->UpdateSort($this->user_id); // user_id
			$this->UpdateSort($this->product_id); // product_id
			$this->UpdateSort($this->qty); // qty
			$this->UpdateSort($this->trx_id); // trx_id
			$this->UpdateSort($this->p_status); // p_status
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->order_id->setSort("");
				$this->user_id->setSort("");
				$this->product_id->setSort("");
				$this->qty->setSort("");
				$this->trx_id->setSort("");
				$this->p_status->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssClass = "text-nowrap";
			$item->OnLeft = FALSE;
			$item->Visible = FALSE; // Default hidden
		}

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssClass = "text-nowrap";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// "sequence"
		$item = &$this->ListOptions->Add("sequence");
		$item->CssClass = "text-nowrap";
		$item->Visible = TRUE;
		$item->OnLeft = TRUE; // Always on left
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Call ListOptions_Rendering event
		$this->ListOptions_Rendering();

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" onclick=\"return ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
			}
		}

		// "sequence"
		$oListOpt = &$this->ListOptions->Items["sequence"];
		$oListOpt->Body = ew_FormatSeqNo($this->RecCnt);

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (($this->CurrentAction == "add" || $this->CurrentAction == "copy") && $this->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink ewInlineInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("InsertLink") . "</a>&nbsp;" .
				"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\"></div>";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink ewInlineUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . ew_UrlAddHash($this->PageName(), "r" . $this->RowCnt . "_" . $this->TableVar) . "');\">" . $Language->Phrase("UpdateLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->order_id->CurrentValue) . "\">";
			return;
		}

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->IsLoggedIn()) {
			if (ew_IsMobile())
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
			else
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-table=\"orders\" data-caption=\"" . $viewcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->ViewUrl) . "',btn:null});\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->IsLoggedIn()) {
			if (ew_IsMobile())
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
			else
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-table=\"orders\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'SaveBtn',url:'" . ew_HtmlEncode($this->EditUrl) . "'});\">" . $Language->Phrase("EditLink") . "</a>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" href=\"" . ew_HtmlEncode(ew_UrlAddHash($this->InlineEditUrl, "r" . $this->RowCnt . "_" . $this->TableVar)) . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->IsLoggedIn()) {
			if (ew_IsMobile())
				$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
			else
				$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-table=\"orders\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->CopyUrl) . "'});\">" . $Language->Phrase("CopyLink") . "</a>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineCopyUrl) . "\">" . $Language->Phrase("InlineCopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . " onclick=\"return ew_ConfirmDelete(this);\"" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->order_id->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->order_id->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		if (ew_IsMobile())
			$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-table=\"orders\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());

		// Inline Add
		$item = &$option->Add("inlineadd");
		$item->Body = "<a class=\"ewAddEdit ewInlineAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineAddUrl) . "\">" .$Language->Phrase("InlineAddLink") . "</a>";
		$item->Visible = ($this->InlineAddUrl <> "" && $Security->IsLoggedIn());
		$item = &$option->Add("gridadd");
		$item->Body = "<a class=\"ewAddEdit ewGridAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" href=\"" . ew_HtmlEncode($this->GridAddUrl) . "\">" . $Language->Phrase("GridAddLink") . "</a>";
		$item->Visible = ($this->GridAddUrl <> "" && $Security->IsLoggedIn());

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->IsLoggedIn());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"forderslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"forderslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "gridedit") { // Not grid add/edit mode
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.forderslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
		} else { // Grid add/edit mode

			// Hide all options first
			foreach ($options as &$option)
				$option->HideAllOptions();
			if ($this->CurrentAction == "gridadd") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->IsLoggedIn();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;

				// Add grid insert
				$item = &$option->Add("gridinsert");
				$item->Body = "<a class=\"ewAction ewGridInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("GridInsertLink") . "</a>";

				// Add grid cancel
				$item = &$option->Add("gridcancel");
				$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
			if ($this->CurrentAction == "gridedit") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->IsLoggedIn();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
					$item = &$option->Add("gridsave");
					$item->Body = "<a class=\"ewAction ewGridSave\" title=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("GridSaveLink") . "</a>";
					$item = &$option->Add("gridcancel");
					$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
					$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
		}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"forderslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load default values
	function LoadDefaultValues() {
		$this->order_id->CurrentValue = NULL;
		$this->order_id->OldValue = $this->order_id->CurrentValue;
		$this->user_id->CurrentValue = NULL;
		$this->user_id->OldValue = $this->user_id->CurrentValue;
		$this->product_id->CurrentValue = NULL;
		$this->product_id->OldValue = $this->product_id->CurrentValue;
		$this->qty->CurrentValue = NULL;
		$this->qty->OldValue = $this->qty->CurrentValue;
		$this->trx_id->CurrentValue = NULL;
		$this->trx_id->OldValue = $this->trx_id->CurrentValue;
		$this->p_status->CurrentValue = NULL;
		$this->p_status->OldValue = $this->p_status->CurrentValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "" && $this->Command == "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->order_id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->order_id->setFormValue($objForm->GetValue("x_order_id"));
		if (!$this->user_id->FldIsDetailKey) {
			$this->user_id->setFormValue($objForm->GetValue("x_user_id"));
		}
		$this->user_id->setOldValue($objForm->GetValue("o_user_id"));
		if (!$this->product_id->FldIsDetailKey) {
			$this->product_id->setFormValue($objForm->GetValue("x_product_id"));
		}
		$this->product_id->setOldValue($objForm->GetValue("o_product_id"));
		if (!$this->qty->FldIsDetailKey) {
			$this->qty->setFormValue($objForm->GetValue("x_qty"));
		}
		$this->qty->setOldValue($objForm->GetValue("o_qty"));
		if (!$this->trx_id->FldIsDetailKey) {
			$this->trx_id->setFormValue($objForm->GetValue("x_trx_id"));
		}
		$this->trx_id->setOldValue($objForm->GetValue("o_trx_id"));
		if (!$this->p_status->FldIsDetailKey) {
			$this->p_status->setFormValue($objForm->GetValue("x_p_status"));
		}
		$this->p_status->setOldValue($objForm->GetValue("o_p_status"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->order_id->CurrentValue = $this->order_id->FormValue;
		$this->user_id->CurrentValue = $this->user_id->FormValue;
		$this->product_id->CurrentValue = $this->product_id->FormValue;
		$this->qty->CurrentValue = $this->qty->FormValue;
		$this->trx_id->CurrentValue = $this->trx_id->FormValue;
		$this->p_status->CurrentValue = $this->p_status->FormValue;
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
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
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
		$this->order_id->setDbValue($row['order_id']);
		$this->user_id->setDbValue($row['user_id']);
		$this->product_id->setDbValue($row['product_id']);
		$this->qty->setDbValue($row['qty']);
		$this->trx_id->setDbValue($row['trx_id']);
		$this->p_status->setDbValue($row['p_status']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['order_id'] = $this->order_id->CurrentValue;
		$row['user_id'] = $this->user_id->CurrentValue;
		$row['product_id'] = $this->product_id->CurrentValue;
		$row['qty'] = $this->qty->CurrentValue;
		$row['trx_id'] = $this->trx_id->CurrentValue;
		$row['p_status'] = $this->p_status->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->order_id->DbValue = $row['order_id'];
		$this->user_id->DbValue = $row['user_id'];
		$this->product_id->DbValue = $row['product_id'];
		$this->qty->DbValue = $row['qty'];
		$this->trx_id->DbValue = $row['trx_id'];
		$this->p_status->DbValue = $row['p_status'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("order_id")) <> "")
			$this->order_id->CurrentValue = $this->getKey("order_id"); // order_id
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// order_id
		// user_id
		// product_id
		// qty
		// trx_id
		// p_status

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// order_id
		$this->order_id->ViewValue = $this->order_id->CurrentValue;
		$this->order_id->ViewCustomAttributes = "";

		// user_id
		if (strval($this->user_id->CurrentValue) <> "") {
			$sFilterWrk = "`user_id`" . ew_SearchString("=", $this->user_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `user_id`, `first_name` AS `DispFld`, `last_name` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `user_info`";
		$sWhereWrk = "";
		$this->user_id->LookupFilters = array("dx1" => '`first_name`', "dx2" => '`last_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->user_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->user_id->ViewValue = $this->user_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->user_id->ViewValue = $this->user_id->CurrentValue;
			}
		} else {
			$this->user_id->ViewValue = NULL;
		}
		$this->user_id->ViewCustomAttributes = "";

		// product_id
		if (strval($this->product_id->CurrentValue) <> "") {
			$sFilterWrk = "`product_id`" . ew_SearchString("=", $this->product_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `product_id`, `product_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `products`";
		$sWhereWrk = "";
		$this->product_id->LookupFilters = array("dx1" => '`product_title`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->product_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->product_id->ViewValue = $this->product_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->product_id->ViewValue = $this->product_id->CurrentValue;
			}
		} else {
			$this->product_id->ViewValue = NULL;
		}
		$this->product_id->ViewCustomAttributes = "";

		// qty
		$this->qty->ViewValue = $this->qty->CurrentValue;
		$this->qty->ViewCustomAttributes = "";

		// trx_id
		$this->trx_id->ViewValue = $this->trx_id->CurrentValue;
		$this->trx_id->ViewCustomAttributes = "";

		// p_status
		if (strval($this->p_status->CurrentValue) <> "") {
			$this->p_status->ViewValue = $this->p_status->OptionCaption($this->p_status->CurrentValue);
		} else {
			$this->p_status->ViewValue = NULL;
		}
		$this->p_status->ViewCustomAttributes = "";

			// order_id
			$this->order_id->LinkCustomAttributes = "";
			$this->order_id->HrefValue = "";
			$this->order_id->TooltipValue = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";
			$this->user_id->TooltipValue = "";

			// product_id
			$this->product_id->LinkCustomAttributes = "";
			$this->product_id->HrefValue = "";
			$this->product_id->TooltipValue = "";

			// qty
			$this->qty->LinkCustomAttributes = "";
			$this->qty->HrefValue = "";
			$this->qty->TooltipValue = "";

			// trx_id
			$this->trx_id->LinkCustomAttributes = "";
			$this->trx_id->HrefValue = "";
			$this->trx_id->TooltipValue = "";

			// p_status
			$this->p_status->LinkCustomAttributes = "";
			$this->p_status->HrefValue = "";
			$this->p_status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// order_id
			// user_id

			$this->user_id->EditCustomAttributes = "";
			if (trim(strval($this->user_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`user_id`" . ew_SearchString("=", $this->user_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `user_id`, `first_name` AS `DispFld`, `last_name` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `user_info`";
			$sWhereWrk = "";
			$this->user_id->LookupFilters = array("dx1" => '`first_name`', "dx2" => '`last_name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->user_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->user_id->ViewValue = $this->user_id->DisplayValue($arwrk);
			} else {
				$this->user_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->user_id->EditValue = $arwrk;

			// product_id
			$this->product_id->EditCustomAttributes = "";
			if (trim(strval($this->product_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`product_id`" . ew_SearchString("=", $this->product_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `product_id`, `product_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `products`";
			$sWhereWrk = "";
			$this->product_id->LookupFilters = array("dx1" => '`product_title`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->product_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->product_id->ViewValue = $this->product_id->DisplayValue($arwrk);
			} else {
				$this->product_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->product_id->EditValue = $arwrk;

			// qty
			$this->qty->EditAttrs["class"] = "form-control";
			$this->qty->EditCustomAttributes = "";
			$this->qty->EditValue = ew_HtmlEncode($this->qty->CurrentValue);
			$this->qty->PlaceHolder = ew_RemoveHtml($this->qty->FldCaption());

			// trx_id
			$this->trx_id->EditAttrs["class"] = "form-control";
			$this->trx_id->EditCustomAttributes = "";
			$this->trx_id->EditValue = ew_HtmlEncode($this->trx_id->CurrentValue);
			$this->trx_id->PlaceHolder = ew_RemoveHtml($this->trx_id->FldCaption());

			// p_status
			$this->p_status->EditCustomAttributes = "";
			$this->p_status->EditValue = $this->p_status->Options(FALSE);

			// Add refer script
			// order_id

			$this->order_id->LinkCustomAttributes = "";
			$this->order_id->HrefValue = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";

			// product_id
			$this->product_id->LinkCustomAttributes = "";
			$this->product_id->HrefValue = "";

			// qty
			$this->qty->LinkCustomAttributes = "";
			$this->qty->HrefValue = "";

			// trx_id
			$this->trx_id->LinkCustomAttributes = "";
			$this->trx_id->HrefValue = "";

			// p_status
			$this->p_status->LinkCustomAttributes = "";
			$this->p_status->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// order_id
			$this->order_id->EditAttrs["class"] = "form-control";
			$this->order_id->EditCustomAttributes = "";
			$this->order_id->EditValue = $this->order_id->CurrentValue;
			$this->order_id->ViewCustomAttributes = "";

			// user_id
			$this->user_id->EditCustomAttributes = "";
			if (trim(strval($this->user_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`user_id`" . ew_SearchString("=", $this->user_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `user_id`, `first_name` AS `DispFld`, `last_name` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `user_info`";
			$sWhereWrk = "";
			$this->user_id->LookupFilters = array("dx1" => '`first_name`', "dx2" => '`last_name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->user_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->user_id->ViewValue = $this->user_id->DisplayValue($arwrk);
			} else {
				$this->user_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->user_id->EditValue = $arwrk;

			// product_id
			$this->product_id->EditCustomAttributes = "";
			if (trim(strval($this->product_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`product_id`" . ew_SearchString("=", $this->product_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `product_id`, `product_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `products`";
			$sWhereWrk = "";
			$this->product_id->LookupFilters = array("dx1" => '`product_title`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->product_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->product_id->ViewValue = $this->product_id->DisplayValue($arwrk);
			} else {
				$this->product_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->product_id->EditValue = $arwrk;

			// qty
			$this->qty->EditAttrs["class"] = "form-control";
			$this->qty->EditCustomAttributes = "";
			$this->qty->EditValue = ew_HtmlEncode($this->qty->CurrentValue);
			$this->qty->PlaceHolder = ew_RemoveHtml($this->qty->FldCaption());

			// trx_id
			$this->trx_id->EditAttrs["class"] = "form-control";
			$this->trx_id->EditCustomAttributes = "";
			$this->trx_id->EditValue = ew_HtmlEncode($this->trx_id->CurrentValue);
			$this->trx_id->PlaceHolder = ew_RemoveHtml($this->trx_id->FldCaption());

			// p_status
			$this->p_status->EditCustomAttributes = "";
			$this->p_status->EditValue = $this->p_status->Options(FALSE);

			// Edit refer script
			// order_id

			$this->order_id->LinkCustomAttributes = "";
			$this->order_id->HrefValue = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";

			// product_id
			$this->product_id->LinkCustomAttributes = "";
			$this->product_id->HrefValue = "";

			// qty
			$this->qty->LinkCustomAttributes = "";
			$this->qty->HrefValue = "";

			// trx_id
			$this->trx_id->LinkCustomAttributes = "";
			$this->trx_id->HrefValue = "";

			// p_status
			$this->p_status->LinkCustomAttributes = "";
			$this->p_status->HrefValue = "";
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
		if (!$this->user_id->FldIsDetailKey && !is_null($this->user_id->FormValue) && $this->user_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->user_id->FldCaption(), $this->user_id->ReqErrMsg));
		}
		if (!$this->product_id->FldIsDetailKey && !is_null($this->product_id->FormValue) && $this->product_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_id->FldCaption(), $this->product_id->ReqErrMsg));
		}
		if (!$this->qty->FldIsDetailKey && !is_null($this->qty->FormValue) && $this->qty->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->qty->FldCaption(), $this->qty->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->qty->FormValue)) {
			ew_AddMessage($gsFormError, $this->qty->FldErrMsg());
		}
		if (!$this->trx_id->FldIsDetailKey && !is_null($this->trx_id->FormValue) && $this->trx_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->trx_id->FldCaption(), $this->trx_id->ReqErrMsg));
		}
		if ($this->p_status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->p_status->FldCaption(), $this->p_status->ReqErrMsg));
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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		}
		$rows = ($rs) ? $rs->GetRows() : array();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['order_id'];

				// Delete old files
				$this->LoadDbValues($row);
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		}
		if (!$DeleteRows) {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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
			$rsnew = array();

			// user_id
			$this->user_id->SetDbValueDef($rsnew, $this->user_id->CurrentValue, 0, $this->user_id->ReadOnly);

			// product_id
			$this->product_id->SetDbValueDef($rsnew, $this->product_id->CurrentValue, 0, $this->product_id->ReadOnly);

			// qty
			$this->qty->SetDbValueDef($rsnew, $this->qty->CurrentValue, 0, $this->qty->ReadOnly);

			// trx_id
			$this->trx_id->SetDbValueDef($rsnew, $this->trx_id->CurrentValue, "", $this->trx_id->ReadOnly);

			// p_status
			$this->p_status->SetDbValueDef($rsnew, $this->p_status->CurrentValue, "", $this->p_status->ReadOnly);

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
		return $EditRow;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// user_id
		$this->user_id->SetDbValueDef($rsnew, $this->user_id->CurrentValue, 0, FALSE);

		// product_id
		$this->product_id->SetDbValueDef($rsnew, $this->product_id->CurrentValue, 0, FALSE);

		// qty
		$this->qty->SetDbValueDef($rsnew, $this->qty->CurrentValue, 0, FALSE);

		// trx_id
		$this->trx_id->SetDbValueDef($rsnew, $this->trx_id->CurrentValue, "", FALSE);

		// p_status
		$this->p_status->SetDbValueDef($rsnew, $this->p_status->CurrentValue, "", FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
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
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_user_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `user_id` AS `LinkFld`, `first_name` AS `DispFld`, `last_name` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `user_info`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`first_name`', "dx2" => '`last_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`user_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->user_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_product_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `product_id` AS `LinkFld`, `product_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `products`";
			$sWhereWrk = "{filter}";
			$fld->LookupFilters = array("dx1" => '`product_title`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`product_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->product_id, $sWhereWrk); // Call Lookup Selecting
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendering event
	function ListOptions_Rendering() {

		//$GLOBALS["xxx_grid"]->DetailAdd = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailEdit = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailView = (...condition...); // Set to TRUE or FALSE conditionally

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example:
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($orders_list)) $orders_list = new corders_list();

// Page init
$orders_list->Page_Init();

// Page main
$orders_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$orders_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = forderslist = new ew_Form("forderslist", "list");
forderslist.FormKeyCountName = '<?php echo $orders_list->FormKeyCountName ?>';

// Validate form
forderslist.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_user_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $orders->user_id->FldCaption(), $orders->user_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $orders->product_id->FldCaption(), $orders->product_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_qty");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $orders->qty->FldCaption(), $orders->qty->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_qty");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($orders->qty->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_trx_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $orders->trx_id->FldCaption(), $orders->trx_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_p_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $orders->p_status->FldCaption(), $orders->p_status->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	if (gridinsert && addcnt == 0) { // No row added
		ew_Alert(ewLanguage.Phrase("NoAddRecord"));
		return false;
	}
	return true;
}

// Check empty row
forderslist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "user_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "product_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qty", false)) return false;
	if (ew_ValueChanged(fobj, infix, "trx_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "p_status", false)) return false;
	return true;
}

// Form_CustomValidate event
forderslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
forderslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
forderslist.Lists["x_user_id"] = {"LinkField":"x_user_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_first_name","x_last_name","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"user_info"};
forderslist.Lists["x_user_id"].Data = "<?php echo $orders_list->user_id->LookupFilterQuery(FALSE, "list") ?>";
forderslist.Lists["x_product_id"] = {"LinkField":"x_product_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_product_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"products"};
forderslist.Lists["x_product_id"].Data = "<?php echo $orders_list->product_id->LookupFilterQuery(FALSE, "list") ?>";
forderslist.Lists["x_p_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
forderslist.Lists["x_p_status"].Options = <?php echo json_encode($orders_list->p_status->Options()) ?>;

// Form object for search
var CurrentSearchForm = forderslistsrch = new ew_Form("forderslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if ($orders_list->TotalRecs > 0 && $orders_list->ExportOptions->Visible()) { ?>
<?php $orders_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($orders_list->SearchOptions->Visible()) { ?>
<?php $orders_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($orders_list->FilterOptions->Visible()) { ?>
<?php $orders_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
if ($orders->CurrentAction == "gridadd") {
	$orders->CurrentFilter = "0=1";
	$orders_list->StartRec = 1;
	$orders_list->DisplayRecs = $orders->GridAddRowCount;
	$orders_list->TotalRecs = $orders_list->DisplayRecs;
	$orders_list->StopRec = $orders_list->DisplayRecs;
} else {
	$bSelectLimit = $orders_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($orders_list->TotalRecs <= 0)
			$orders_list->TotalRecs = $orders->ListRecordCount();
	} else {
		if (!$orders_list->Recordset && ($orders_list->Recordset = $orders_list->LoadRecordset()))
			$orders_list->TotalRecs = $orders_list->Recordset->RecordCount();
	}
	$orders_list->StartRec = 1;
	if ($orders_list->DisplayRecs <= 0 || ($orders->Export <> "" && $orders->ExportAll)) // Display all records
		$orders_list->DisplayRecs = $orders_list->TotalRecs;
	if (!($orders->Export <> "" && $orders->ExportAll))
		$orders_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$orders_list->Recordset = $orders_list->LoadRecordset($orders_list->StartRec-1, $orders_list->DisplayRecs);

	// Set no record found message
	if ($orders->CurrentAction == "" && $orders_list->TotalRecs == 0) {
		if ($orders_list->SearchWhere == "0=101")
			$orders_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$orders_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$orders_list->RenderOtherOptions();
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($orders->Export == "" && $orders->CurrentAction == "") { ?>
<form name="forderslistsrch" id="forderslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($orders_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="forderslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="orders">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($orders_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($orders_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $orders_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($orders_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($orders_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($orders_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($orders_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $orders_list->ShowPageHeader(); ?>
<?php
$orders_list->ShowMessage();
?>
<?php if ($orders_list->TotalRecs > 0 || $orders->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($orders_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> orders">
<form name="forderslist" id="forderslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($orders_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $orders_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="orders">
<div id="gmp_orders" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($orders_list->TotalRecs > 0 || $orders->CurrentAction == "add" || $orders->CurrentAction == "copy" || $orders->CurrentAction == "gridedit") { ?>
<table id="tbl_orderslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$orders_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$orders_list->RenderListOptions();

// Render list options (header, left)
$orders_list->ListOptions->Render("header", "left");
?>
<?php if ($orders->order_id->Visible) { // order_id ?>
	<?php if ($orders->SortUrl($orders->order_id) == "") { ?>
		<th data-name="order_id" class="<?php echo $orders->order_id->HeaderCellClass() ?>"><div id="elh_orders_order_id" class="orders_order_id"><div class="ewTableHeaderCaption"><?php echo $orders->order_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="order_id" class="<?php echo $orders->order_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $orders->SortUrl($orders->order_id) ?>',1);"><div id="elh_orders_order_id" class="orders_order_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $orders->order_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($orders->order_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($orders->order_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($orders->user_id->Visible) { // user_id ?>
	<?php if ($orders->SortUrl($orders->user_id) == "") { ?>
		<th data-name="user_id" class="<?php echo $orders->user_id->HeaderCellClass() ?>"><div id="elh_orders_user_id" class="orders_user_id"><div class="ewTableHeaderCaption"><?php echo $orders->user_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="user_id" class="<?php echo $orders->user_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $orders->SortUrl($orders->user_id) ?>',1);"><div id="elh_orders_user_id" class="orders_user_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $orders->user_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($orders->user_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($orders->user_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($orders->product_id->Visible) { // product_id ?>
	<?php if ($orders->SortUrl($orders->product_id) == "") { ?>
		<th data-name="product_id" class="<?php echo $orders->product_id->HeaderCellClass() ?>"><div id="elh_orders_product_id" class="orders_product_id"><div class="ewTableHeaderCaption"><?php echo $orders->product_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="product_id" class="<?php echo $orders->product_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $orders->SortUrl($orders->product_id) ?>',1);"><div id="elh_orders_product_id" class="orders_product_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $orders->product_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($orders->product_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($orders->product_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($orders->qty->Visible) { // qty ?>
	<?php if ($orders->SortUrl($orders->qty) == "") { ?>
		<th data-name="qty" class="<?php echo $orders->qty->HeaderCellClass() ?>"><div id="elh_orders_qty" class="orders_qty"><div class="ewTableHeaderCaption"><?php echo $orders->qty->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="qty" class="<?php echo $orders->qty->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $orders->SortUrl($orders->qty) ?>',1);"><div id="elh_orders_qty" class="orders_qty">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $orders->qty->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($orders->qty->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($orders->qty->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($orders->trx_id->Visible) { // trx_id ?>
	<?php if ($orders->SortUrl($orders->trx_id) == "") { ?>
		<th data-name="trx_id" class="<?php echo $orders->trx_id->HeaderCellClass() ?>"><div id="elh_orders_trx_id" class="orders_trx_id"><div class="ewTableHeaderCaption"><?php echo $orders->trx_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="trx_id" class="<?php echo $orders->trx_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $orders->SortUrl($orders->trx_id) ?>',1);"><div id="elh_orders_trx_id" class="orders_trx_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $orders->trx_id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($orders->trx_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($orders->trx_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($orders->p_status->Visible) { // p_status ?>
	<?php if ($orders->SortUrl($orders->p_status) == "") { ?>
		<th data-name="p_status" class="<?php echo $orders->p_status->HeaderCellClass() ?>"><div id="elh_orders_p_status" class="orders_p_status"><div class="ewTableHeaderCaption"><?php echo $orders->p_status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="p_status" class="<?php echo $orders->p_status->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $orders->SortUrl($orders->p_status) ?>',1);"><div id="elh_orders_p_status" class="orders_p_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $orders->p_status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($orders->p_status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($orders->p_status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$orders_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($orders->CurrentAction == "add" || $orders->CurrentAction == "copy") {
		$orders_list->RowIndex = 0;
		$orders_list->KeyCount = $orders_list->RowIndex;
		if ($orders->CurrentAction == "copy" && !$orders_list->LoadRow())
			$orders->CurrentAction = "add";
		if ($orders->CurrentAction == "add")
			$orders_list->LoadRowValues();
		if ($orders->EventCancelled) // Insert failed
			$orders_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$orders->ResetAttrs();
		$orders->RowAttrs = array_merge($orders->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_orders', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$orders->RowType = EW_ROWTYPE_ADD;

		// Render row
		$orders_list->RenderRow();

		// Render list options
		$orders_list->RenderListOptions();
		$orders_list->StartRowCnt = 0;
?>
	<tr<?php echo $orders->RowAttributes() ?>>
<?php

// Render list options (body, left)
$orders_list->ListOptions->Render("body", "left", $orders_list->RowCnt);
?>
	<?php if ($orders->order_id->Visible) { // order_id ?>
		<td data-name="order_id">
<input type="hidden" data-table="orders" data-field="x_order_id" name="o<?php echo $orders_list->RowIndex ?>_order_id" id="o<?php echo $orders_list->RowIndex ?>_order_id" value="<?php echo ew_HtmlEncode($orders->order_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($orders->user_id->Visible) { // user_id ?>
		<td data-name="user_id">
<span id="el<?php echo $orders_list->RowCnt ?>_orders_user_id" class="form-group orders_user_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $orders_list->RowIndex ?>_user_id"><?php echo (strval($orders->user_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $orders->user_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($orders->user_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $orders_list->RowIndex ?>_user_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($orders->user_id->ReadOnly || $orders->user_id->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="orders" data-field="x_user_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $orders->user_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $orders_list->RowIndex ?>_user_id" id="x<?php echo $orders_list->RowIndex ?>_user_id" value="<?php echo $orders->user_id->CurrentValue ?>"<?php echo $orders->user_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="orders" data-field="x_user_id" name="o<?php echo $orders_list->RowIndex ?>_user_id" id="o<?php echo $orders_list->RowIndex ?>_user_id" value="<?php echo ew_HtmlEncode($orders->user_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($orders->product_id->Visible) { // product_id ?>
		<td data-name="product_id">
<span id="el<?php echo $orders_list->RowCnt ?>_orders_product_id" class="form-group orders_product_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $orders_list->RowIndex ?>_product_id"><?php echo (strval($orders->product_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $orders->product_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($orders->product_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $orders_list->RowIndex ?>_product_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($orders->product_id->ReadOnly || $orders->product_id->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="orders" data-field="x_product_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $orders->product_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $orders_list->RowIndex ?>_product_id" id="x<?php echo $orders_list->RowIndex ?>_product_id" value="<?php echo $orders->product_id->CurrentValue ?>"<?php echo $orders->product_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="orders" data-field="x_product_id" name="o<?php echo $orders_list->RowIndex ?>_product_id" id="o<?php echo $orders_list->RowIndex ?>_product_id" value="<?php echo ew_HtmlEncode($orders->product_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($orders->qty->Visible) { // qty ?>
		<td data-name="qty">
<span id="el<?php echo $orders_list->RowCnt ?>_orders_qty" class="form-group orders_qty">
<input type="text" data-table="orders" data-field="x_qty" name="x<?php echo $orders_list->RowIndex ?>_qty" id="x<?php echo $orders_list->RowIndex ?>_qty" size="30" placeholder="<?php echo ew_HtmlEncode($orders->qty->getPlaceHolder()) ?>" value="<?php echo $orders->qty->EditValue ?>"<?php echo $orders->qty->EditAttributes() ?>>
</span>
<input type="hidden" data-table="orders" data-field="x_qty" name="o<?php echo $orders_list->RowIndex ?>_qty" id="o<?php echo $orders_list->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($orders->qty->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($orders->trx_id->Visible) { // trx_id ?>
		<td data-name="trx_id">
<span id="el<?php echo $orders_list->RowCnt ?>_orders_trx_id" class="form-group orders_trx_id">
<input type="text" data-table="orders" data-field="x_trx_id" name="x<?php echo $orders_list->RowIndex ?>_trx_id" id="x<?php echo $orders_list->RowIndex ?>_trx_id" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($orders->trx_id->getPlaceHolder()) ?>" value="<?php echo $orders->trx_id->EditValue ?>"<?php echo $orders->trx_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="orders" data-field="x_trx_id" name="o<?php echo $orders_list->RowIndex ?>_trx_id" id="o<?php echo $orders_list->RowIndex ?>_trx_id" value="<?php echo ew_HtmlEncode($orders->trx_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($orders->p_status->Visible) { // p_status ?>
		<td data-name="p_status">
<span id="el<?php echo $orders_list->RowCnt ?>_orders_p_status" class="form-group orders_p_status">
<div id="tp_x<?php echo $orders_list->RowIndex ?>_p_status" class="ewTemplate"><input type="radio" data-table="orders" data-field="x_p_status" data-value-separator="<?php echo $orders->p_status->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $orders_list->RowIndex ?>_p_status" id="x<?php echo $orders_list->RowIndex ?>_p_status" value="{value}"<?php echo $orders->p_status->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $orders_list->RowIndex ?>_p_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $orders->p_status->RadioButtonListHtml(FALSE, "x{$orders_list->RowIndex}_p_status") ?>
</div></div>
</span>
<input type="hidden" data-table="orders" data-field="x_p_status" name="o<?php echo $orders_list->RowIndex ?>_p_status" id="o<?php echo $orders_list->RowIndex ?>_p_status" value="<?php echo ew_HtmlEncode($orders->p_status->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$orders_list->ListOptions->Render("body", "right", $orders_list->RowCnt);
?>
<script type="text/javascript">
forderslist.UpdateOpts(<?php echo $orders_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($orders->ExportAll && $orders->Export <> "") {
	$orders_list->StopRec = $orders_list->TotalRecs;
} else {

	// Set the last record to display
	if ($orders_list->TotalRecs > $orders_list->StartRec + $orders_list->DisplayRecs - 1)
		$orders_list->StopRec = $orders_list->StartRec + $orders_list->DisplayRecs - 1;
	else
		$orders_list->StopRec = $orders_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($orders_list->FormKeyCountName) && ($orders->CurrentAction == "gridadd" || $orders->CurrentAction == "gridedit" || $orders->CurrentAction == "F")) {
		$orders_list->KeyCount = $objForm->GetValue($orders_list->FormKeyCountName);
		$orders_list->StopRec = $orders_list->StartRec + $orders_list->KeyCount - 1;
	}
}
$orders_list->RecCnt = $orders_list->StartRec - 1;
if ($orders_list->Recordset && !$orders_list->Recordset->EOF) {
	$orders_list->Recordset->MoveFirst();
	$bSelectLimit = $orders_list->UseSelectLimit;
	if (!$bSelectLimit && $orders_list->StartRec > 1)
		$orders_list->Recordset->Move($orders_list->StartRec - 1);
} elseif (!$orders->AllowAddDeleteRow && $orders_list->StopRec == 0) {
	$orders_list->StopRec = $orders->GridAddRowCount;
}

// Initialize aggregate
$orders->RowType = EW_ROWTYPE_AGGREGATEINIT;
$orders->ResetAttrs();
$orders_list->RenderRow();
$orders_list->EditRowCnt = 0;
if ($orders->CurrentAction == "edit")
	$orders_list->RowIndex = 1;
if ($orders->CurrentAction == "gridadd")
	$orders_list->RowIndex = 0;
if ($orders->CurrentAction == "gridedit")
	$orders_list->RowIndex = 0;
while ($orders_list->RecCnt < $orders_list->StopRec) {
	$orders_list->RecCnt++;
	if (intval($orders_list->RecCnt) >= intval($orders_list->StartRec)) {
		$orders_list->RowCnt++;
		if ($orders->CurrentAction == "gridadd" || $orders->CurrentAction == "gridedit" || $orders->CurrentAction == "F") {
			$orders_list->RowIndex++;
			$objForm->Index = $orders_list->RowIndex;
			if ($objForm->HasValue($orders_list->FormActionName))
				$orders_list->RowAction = strval($objForm->GetValue($orders_list->FormActionName));
			elseif ($orders->CurrentAction == "gridadd")
				$orders_list->RowAction = "insert";
			else
				$orders_list->RowAction = "";
		}

		// Set up key count
		$orders_list->KeyCount = $orders_list->RowIndex;

		// Init row class and style
		$orders->ResetAttrs();
		$orders->CssClass = "";
		if ($orders->CurrentAction == "gridadd") {
			$orders_list->LoadRowValues(); // Load default values
		} else {
			$orders_list->LoadRowValues($orders_list->Recordset); // Load row values
		}
		$orders->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($orders->CurrentAction == "gridadd") // Grid add
			$orders->RowType = EW_ROWTYPE_ADD; // Render add
		if ($orders->CurrentAction == "gridadd" && $orders->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$orders_list->RestoreCurrentRowFormValues($orders_list->RowIndex); // Restore form values
		if ($orders->CurrentAction == "edit") {
			if ($orders_list->CheckInlineEditKey() && $orders_list->EditRowCnt == 0) { // Inline edit
				$orders->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($orders->CurrentAction == "gridedit") { // Grid edit
			if ($orders->EventCancelled) {
				$orders_list->RestoreCurrentRowFormValues($orders_list->RowIndex); // Restore form values
			}
			if ($orders_list->RowAction == "insert")
				$orders->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$orders->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($orders->CurrentAction == "edit" && $orders->RowType == EW_ROWTYPE_EDIT && $orders->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$orders_list->RestoreFormValues(); // Restore form values
		}
		if ($orders->CurrentAction == "gridedit" && ($orders->RowType == EW_ROWTYPE_EDIT || $orders->RowType == EW_ROWTYPE_ADD) && $orders->EventCancelled) // Update failed
			$orders_list->RestoreCurrentRowFormValues($orders_list->RowIndex); // Restore form values
		if ($orders->RowType == EW_ROWTYPE_EDIT) // Edit row
			$orders_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$orders->RowAttrs = array_merge($orders->RowAttrs, array('data-rowindex'=>$orders_list->RowCnt, 'id'=>'r' . $orders_list->RowCnt . '_orders', 'data-rowtype'=>$orders->RowType));

		// Render row
		$orders_list->RenderRow();

		// Render list options
		$orders_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($orders_list->RowAction <> "delete" && $orders_list->RowAction <> "insertdelete" && !($orders_list->RowAction == "insert" && $orders->CurrentAction == "F" && $orders_list->EmptyRow())) {
?>
	<tr<?php echo $orders->RowAttributes() ?>>
<?php

// Render list options (body, left)
$orders_list->ListOptions->Render("body", "left", $orders_list->RowCnt);
?>
	<?php if ($orders->order_id->Visible) { // order_id ?>
		<td data-name="order_id"<?php echo $orders->order_id->CellAttributes() ?>>
<?php if ($orders->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="orders" data-field="x_order_id" name="o<?php echo $orders_list->RowIndex ?>_order_id" id="o<?php echo $orders_list->RowIndex ?>_order_id" value="<?php echo ew_HtmlEncode($orders->order_id->OldValue) ?>">
<?php } ?>
<?php if ($orders->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $orders_list->RowCnt ?>_orders_order_id" class="form-group orders_order_id">
<span<?php echo $orders->order_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $orders->order_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="orders" data-field="x_order_id" name="x<?php echo $orders_list->RowIndex ?>_order_id" id="x<?php echo $orders_list->RowIndex ?>_order_id" value="<?php echo ew_HtmlEncode($orders->order_id->CurrentValue) ?>">
<?php } ?>
<?php if ($orders->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $orders_list->RowCnt ?>_orders_order_id" class="orders_order_id">
<span<?php echo $orders->order_id->ViewAttributes() ?>>
<?php echo $orders->order_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($orders->user_id->Visible) { // user_id ?>
		<td data-name="user_id"<?php echo $orders->user_id->CellAttributes() ?>>
<?php if ($orders->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $orders_list->RowCnt ?>_orders_user_id" class="form-group orders_user_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $orders_list->RowIndex ?>_user_id"><?php echo (strval($orders->user_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $orders->user_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($orders->user_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $orders_list->RowIndex ?>_user_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($orders->user_id->ReadOnly || $orders->user_id->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="orders" data-field="x_user_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $orders->user_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $orders_list->RowIndex ?>_user_id" id="x<?php echo $orders_list->RowIndex ?>_user_id" value="<?php echo $orders->user_id->CurrentValue ?>"<?php echo $orders->user_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="orders" data-field="x_user_id" name="o<?php echo $orders_list->RowIndex ?>_user_id" id="o<?php echo $orders_list->RowIndex ?>_user_id" value="<?php echo ew_HtmlEncode($orders->user_id->OldValue) ?>">
<?php } ?>
<?php if ($orders->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $orders_list->RowCnt ?>_orders_user_id" class="form-group orders_user_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $orders_list->RowIndex ?>_user_id"><?php echo (strval($orders->user_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $orders->user_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($orders->user_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $orders_list->RowIndex ?>_user_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($orders->user_id->ReadOnly || $orders->user_id->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="orders" data-field="x_user_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $orders->user_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $orders_list->RowIndex ?>_user_id" id="x<?php echo $orders_list->RowIndex ?>_user_id" value="<?php echo $orders->user_id->CurrentValue ?>"<?php echo $orders->user_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($orders->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $orders_list->RowCnt ?>_orders_user_id" class="orders_user_id">
<span<?php echo $orders->user_id->ViewAttributes() ?>>
<?php echo $orders->user_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($orders->product_id->Visible) { // product_id ?>
		<td data-name="product_id"<?php echo $orders->product_id->CellAttributes() ?>>
<?php if ($orders->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $orders_list->RowCnt ?>_orders_product_id" class="form-group orders_product_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $orders_list->RowIndex ?>_product_id"><?php echo (strval($orders->product_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $orders->product_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($orders->product_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $orders_list->RowIndex ?>_product_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($orders->product_id->ReadOnly || $orders->product_id->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="orders" data-field="x_product_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $orders->product_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $orders_list->RowIndex ?>_product_id" id="x<?php echo $orders_list->RowIndex ?>_product_id" value="<?php echo $orders->product_id->CurrentValue ?>"<?php echo $orders->product_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="orders" data-field="x_product_id" name="o<?php echo $orders_list->RowIndex ?>_product_id" id="o<?php echo $orders_list->RowIndex ?>_product_id" value="<?php echo ew_HtmlEncode($orders->product_id->OldValue) ?>">
<?php } ?>
<?php if ($orders->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $orders_list->RowCnt ?>_orders_product_id" class="form-group orders_product_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $orders_list->RowIndex ?>_product_id"><?php echo (strval($orders->product_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $orders->product_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($orders->product_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $orders_list->RowIndex ?>_product_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($orders->product_id->ReadOnly || $orders->product_id->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="orders" data-field="x_product_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $orders->product_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $orders_list->RowIndex ?>_product_id" id="x<?php echo $orders_list->RowIndex ?>_product_id" value="<?php echo $orders->product_id->CurrentValue ?>"<?php echo $orders->product_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($orders->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $orders_list->RowCnt ?>_orders_product_id" class="orders_product_id">
<span<?php echo $orders->product_id->ViewAttributes() ?>>
<?php echo $orders->product_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($orders->qty->Visible) { // qty ?>
		<td data-name="qty"<?php echo $orders->qty->CellAttributes() ?>>
<?php if ($orders->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $orders_list->RowCnt ?>_orders_qty" class="form-group orders_qty">
<input type="text" data-table="orders" data-field="x_qty" name="x<?php echo $orders_list->RowIndex ?>_qty" id="x<?php echo $orders_list->RowIndex ?>_qty" size="30" placeholder="<?php echo ew_HtmlEncode($orders->qty->getPlaceHolder()) ?>" value="<?php echo $orders->qty->EditValue ?>"<?php echo $orders->qty->EditAttributes() ?>>
</span>
<input type="hidden" data-table="orders" data-field="x_qty" name="o<?php echo $orders_list->RowIndex ?>_qty" id="o<?php echo $orders_list->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($orders->qty->OldValue) ?>">
<?php } ?>
<?php if ($orders->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $orders_list->RowCnt ?>_orders_qty" class="form-group orders_qty">
<input type="text" data-table="orders" data-field="x_qty" name="x<?php echo $orders_list->RowIndex ?>_qty" id="x<?php echo $orders_list->RowIndex ?>_qty" size="30" placeholder="<?php echo ew_HtmlEncode($orders->qty->getPlaceHolder()) ?>" value="<?php echo $orders->qty->EditValue ?>"<?php echo $orders->qty->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($orders->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $orders_list->RowCnt ?>_orders_qty" class="orders_qty">
<span<?php echo $orders->qty->ViewAttributes() ?>>
<?php echo $orders->qty->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($orders->trx_id->Visible) { // trx_id ?>
		<td data-name="trx_id"<?php echo $orders->trx_id->CellAttributes() ?>>
<?php if ($orders->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $orders_list->RowCnt ?>_orders_trx_id" class="form-group orders_trx_id">
<input type="text" data-table="orders" data-field="x_trx_id" name="x<?php echo $orders_list->RowIndex ?>_trx_id" id="x<?php echo $orders_list->RowIndex ?>_trx_id" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($orders->trx_id->getPlaceHolder()) ?>" value="<?php echo $orders->trx_id->EditValue ?>"<?php echo $orders->trx_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="orders" data-field="x_trx_id" name="o<?php echo $orders_list->RowIndex ?>_trx_id" id="o<?php echo $orders_list->RowIndex ?>_trx_id" value="<?php echo ew_HtmlEncode($orders->trx_id->OldValue) ?>">
<?php } ?>
<?php if ($orders->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $orders_list->RowCnt ?>_orders_trx_id" class="form-group orders_trx_id">
<input type="text" data-table="orders" data-field="x_trx_id" name="x<?php echo $orders_list->RowIndex ?>_trx_id" id="x<?php echo $orders_list->RowIndex ?>_trx_id" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($orders->trx_id->getPlaceHolder()) ?>" value="<?php echo $orders->trx_id->EditValue ?>"<?php echo $orders->trx_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($orders->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $orders_list->RowCnt ?>_orders_trx_id" class="orders_trx_id">
<span<?php echo $orders->trx_id->ViewAttributes() ?>>
<?php echo $orders->trx_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($orders->p_status->Visible) { // p_status ?>
		<td data-name="p_status"<?php echo $orders->p_status->CellAttributes() ?>>
<?php if ($orders->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $orders_list->RowCnt ?>_orders_p_status" class="form-group orders_p_status">
<div id="tp_x<?php echo $orders_list->RowIndex ?>_p_status" class="ewTemplate"><input type="radio" data-table="orders" data-field="x_p_status" data-value-separator="<?php echo $orders->p_status->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $orders_list->RowIndex ?>_p_status" id="x<?php echo $orders_list->RowIndex ?>_p_status" value="{value}"<?php echo $orders->p_status->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $orders_list->RowIndex ?>_p_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $orders->p_status->RadioButtonListHtml(FALSE, "x{$orders_list->RowIndex}_p_status") ?>
</div></div>
</span>
<input type="hidden" data-table="orders" data-field="x_p_status" name="o<?php echo $orders_list->RowIndex ?>_p_status" id="o<?php echo $orders_list->RowIndex ?>_p_status" value="<?php echo ew_HtmlEncode($orders->p_status->OldValue) ?>">
<?php } ?>
<?php if ($orders->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $orders_list->RowCnt ?>_orders_p_status" class="form-group orders_p_status">
<div id="tp_x<?php echo $orders_list->RowIndex ?>_p_status" class="ewTemplate"><input type="radio" data-table="orders" data-field="x_p_status" data-value-separator="<?php echo $orders->p_status->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $orders_list->RowIndex ?>_p_status" id="x<?php echo $orders_list->RowIndex ?>_p_status" value="{value}"<?php echo $orders->p_status->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $orders_list->RowIndex ?>_p_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $orders->p_status->RadioButtonListHtml(FALSE, "x{$orders_list->RowIndex}_p_status") ?>
</div></div>
</span>
<?php } ?>
<?php if ($orders->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $orders_list->RowCnt ?>_orders_p_status" class="orders_p_status">
<span<?php echo $orders->p_status->ViewAttributes() ?>>
<?php echo $orders->p_status->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$orders_list->ListOptions->Render("body", "right", $orders_list->RowCnt);
?>
	</tr>
<?php if ($orders->RowType == EW_ROWTYPE_ADD || $orders->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
forderslist.UpdateOpts(<?php echo $orders_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($orders->CurrentAction <> "gridadd")
		if (!$orders_list->Recordset->EOF) $orders_list->Recordset->MoveNext();
}
?>
<?php
	if ($orders->CurrentAction == "gridadd" || $orders->CurrentAction == "gridedit") {
		$orders_list->RowIndex = '$rowindex$';
		$orders_list->LoadRowValues();

		// Set row properties
		$orders->ResetAttrs();
		$orders->RowAttrs = array_merge($orders->RowAttrs, array('data-rowindex'=>$orders_list->RowIndex, 'id'=>'r0_orders', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($orders->RowAttrs["class"], "ewTemplate");
		$orders->RowType = EW_ROWTYPE_ADD;

		// Render row
		$orders_list->RenderRow();

		// Render list options
		$orders_list->RenderListOptions();
		$orders_list->StartRowCnt = 0;
?>
	<tr<?php echo $orders->RowAttributes() ?>>
<?php

// Render list options (body, left)
$orders_list->ListOptions->Render("body", "left", $orders_list->RowIndex);
?>
	<?php if ($orders->order_id->Visible) { // order_id ?>
		<td data-name="order_id">
<input type="hidden" data-table="orders" data-field="x_order_id" name="o<?php echo $orders_list->RowIndex ?>_order_id" id="o<?php echo $orders_list->RowIndex ?>_order_id" value="<?php echo ew_HtmlEncode($orders->order_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($orders->user_id->Visible) { // user_id ?>
		<td data-name="user_id">
<span id="el$rowindex$_orders_user_id" class="form-group orders_user_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $orders_list->RowIndex ?>_user_id"><?php echo (strval($orders->user_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $orders->user_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($orders->user_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $orders_list->RowIndex ?>_user_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($orders->user_id->ReadOnly || $orders->user_id->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="orders" data-field="x_user_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $orders->user_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $orders_list->RowIndex ?>_user_id" id="x<?php echo $orders_list->RowIndex ?>_user_id" value="<?php echo $orders->user_id->CurrentValue ?>"<?php echo $orders->user_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="orders" data-field="x_user_id" name="o<?php echo $orders_list->RowIndex ?>_user_id" id="o<?php echo $orders_list->RowIndex ?>_user_id" value="<?php echo ew_HtmlEncode($orders->user_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($orders->product_id->Visible) { // product_id ?>
		<td data-name="product_id">
<span id="el$rowindex$_orders_product_id" class="form-group orders_product_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $orders_list->RowIndex ?>_product_id"><?php echo (strval($orders->product_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $orders->product_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($orders->product_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $orders_list->RowIndex ?>_product_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($orders->product_id->ReadOnly || $orders->product_id->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="orders" data-field="x_product_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $orders->product_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $orders_list->RowIndex ?>_product_id" id="x<?php echo $orders_list->RowIndex ?>_product_id" value="<?php echo $orders->product_id->CurrentValue ?>"<?php echo $orders->product_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="orders" data-field="x_product_id" name="o<?php echo $orders_list->RowIndex ?>_product_id" id="o<?php echo $orders_list->RowIndex ?>_product_id" value="<?php echo ew_HtmlEncode($orders->product_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($orders->qty->Visible) { // qty ?>
		<td data-name="qty">
<span id="el$rowindex$_orders_qty" class="form-group orders_qty">
<input type="text" data-table="orders" data-field="x_qty" name="x<?php echo $orders_list->RowIndex ?>_qty" id="x<?php echo $orders_list->RowIndex ?>_qty" size="30" placeholder="<?php echo ew_HtmlEncode($orders->qty->getPlaceHolder()) ?>" value="<?php echo $orders->qty->EditValue ?>"<?php echo $orders->qty->EditAttributes() ?>>
</span>
<input type="hidden" data-table="orders" data-field="x_qty" name="o<?php echo $orders_list->RowIndex ?>_qty" id="o<?php echo $orders_list->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($orders->qty->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($orders->trx_id->Visible) { // trx_id ?>
		<td data-name="trx_id">
<span id="el$rowindex$_orders_trx_id" class="form-group orders_trx_id">
<input type="text" data-table="orders" data-field="x_trx_id" name="x<?php echo $orders_list->RowIndex ?>_trx_id" id="x<?php echo $orders_list->RowIndex ?>_trx_id" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($orders->trx_id->getPlaceHolder()) ?>" value="<?php echo $orders->trx_id->EditValue ?>"<?php echo $orders->trx_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="orders" data-field="x_trx_id" name="o<?php echo $orders_list->RowIndex ?>_trx_id" id="o<?php echo $orders_list->RowIndex ?>_trx_id" value="<?php echo ew_HtmlEncode($orders->trx_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($orders->p_status->Visible) { // p_status ?>
		<td data-name="p_status">
<span id="el$rowindex$_orders_p_status" class="form-group orders_p_status">
<div id="tp_x<?php echo $orders_list->RowIndex ?>_p_status" class="ewTemplate"><input type="radio" data-table="orders" data-field="x_p_status" data-value-separator="<?php echo $orders->p_status->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $orders_list->RowIndex ?>_p_status" id="x<?php echo $orders_list->RowIndex ?>_p_status" value="{value}"<?php echo $orders->p_status->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $orders_list->RowIndex ?>_p_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $orders->p_status->RadioButtonListHtml(FALSE, "x{$orders_list->RowIndex}_p_status") ?>
</div></div>
</span>
<input type="hidden" data-table="orders" data-field="x_p_status" name="o<?php echo $orders_list->RowIndex ?>_p_status" id="o<?php echo $orders_list->RowIndex ?>_p_status" value="<?php echo ew_HtmlEncode($orders->p_status->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$orders_list->ListOptions->Render("body", "right", $orders_list->RowIndex);
?>
<script type="text/javascript">
forderslist.UpdateOpts(<?php echo $orders_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($orders->CurrentAction == "add" || $orders->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $orders_list->FormKeyCountName ?>" id="<?php echo $orders_list->FormKeyCountName ?>" value="<?php echo $orders_list->KeyCount ?>">
<?php } ?>
<?php if ($orders->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $orders_list->FormKeyCountName ?>" id="<?php echo $orders_list->FormKeyCountName ?>" value="<?php echo $orders_list->KeyCount ?>">
<?php echo $orders_list->MultiSelectKey ?>
<?php } ?>
<?php if ($orders->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $orders_list->FormKeyCountName ?>" id="<?php echo $orders_list->FormKeyCountName ?>" value="<?php echo $orders_list->KeyCount ?>">
<?php } ?>
<?php if ($orders->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $orders_list->FormKeyCountName ?>" id="<?php echo $orders_list->FormKeyCountName ?>" value="<?php echo $orders_list->KeyCount ?>">
<?php echo $orders_list->MultiSelectKey ?>
<?php } ?>
<?php if ($orders->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($orders_list->Recordset)
	$orders_list->Recordset->Close();
?>
<div class="box-footer ewGridLowerPanel">
<?php if ($orders->CurrentAction <> "gridadd" && $orders->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($orders_list->Pager)) $orders_list->Pager = new cPrevNextPager($orders_list->StartRec, $orders_list->DisplayRecs, $orders_list->TotalRecs, $orders_list->AutoHidePager) ?>
<?php if ($orders_list->Pager->RecordCount > 0 && $orders_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($orders_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $orders_list->PageUrl() ?>start=<?php echo $orders_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($orders_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $orders_list->PageUrl() ?>start=<?php echo $orders_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $orders_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($orders_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $orders_list->PageUrl() ?>start=<?php echo $orders_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($orders_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $orders_list->PageUrl() ?>start=<?php echo $orders_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $orders_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($orders_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $orders_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $orders_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $orders_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($orders_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($orders_list->TotalRecs == 0 && $orders->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($orders_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
forderslistsrch.FilterList = <?php echo $orders_list->GetFilterList() ?>;
forderslistsrch.Init();
forderslist.Init();
</script>
<?php
$orders_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$orders_list->Page_Terminate();
?>
