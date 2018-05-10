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

$products_list = NULL; // Initialize page object first

class cproducts_list extends cproducts {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{DE3D48ED-60FB-4F03-A5AD-139B7A8A8A85}';

	// Table name
	var $TableName = 'products';

	// Page object name
	var $PageObjName = 'products_list';

	// Grid form hidden field names
	var $FormName = 'fproductslist';
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

		// Table object (products)
		if (!isset($GLOBALS["products"]) || get_class($GLOBALS["products"]) == "cproducts") {
			$GLOBALS["products"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["products"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "productsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "productsdelete.php";
		$this->MultiUpdateUrl = "productsupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fproductslistsrch";

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
		$this->product_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->product_id->Visible = FALSE;
		$this->product_cat->SetVisibility();
		$this->product_brand->SetVisibility();
		$this->product_title->SetVisibility();
		$this->product_price->SetVisibility();
		$this->product_image->SetVisibility();

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
		$this->setKey("product_id", ""); // Clear inline edit key
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
		if (isset($_GET["product_id"])) {
			$this->product_id->setQueryStringValue($_GET["product_id"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("product_id", $this->product_id->CurrentValue); // Set up inline edit key
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
		if (strval($this->getKey("product_id")) <> strval($this->product_id->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if ($this->CurrentAction == "copy") {
			if (@$_GET["product_id"] <> "") {
				$this->product_id->setQueryStringValue($_GET["product_id"]);
				$this->setKey("product_id", $this->product_id->CurrentValue); // Set up key
			} else {
				$this->setKey("product_id", ""); // Clear key
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
			$this->product_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->product_id->FormValue))
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
					$sKey .= $this->product_id->CurrentValue;

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
		if ($objForm->HasValue("x_product_cat") && $objForm->HasValue("o_product_cat") && $this->product_cat->CurrentValue <> $this->product_cat->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_product_brand") && $objForm->HasValue("o_product_brand") && $this->product_brand->CurrentValue <> $this->product_brand->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_product_title") && $objForm->HasValue("o_product_title") && $this->product_title->CurrentValue <> $this->product_title->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_product_price") && $objForm->HasValue("o_product_price") && $this->product_price->CurrentValue <> $this->product_price->OldValue)
			return FALSE;
		if (!ew_Empty($this->product_image->Upload->Value))
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
		$sFilterList = ew_Concat($sFilterList, $this->product_id->AdvancedSearch->ToJson(), ","); // Field product_id
		$sFilterList = ew_Concat($sFilterList, $this->product_cat->AdvancedSearch->ToJson(), ","); // Field product_cat
		$sFilterList = ew_Concat($sFilterList, $this->product_brand->AdvancedSearch->ToJson(), ","); // Field product_brand
		$sFilterList = ew_Concat($sFilterList, $this->product_title->AdvancedSearch->ToJson(), ","); // Field product_title
		$sFilterList = ew_Concat($sFilterList, $this->product_price->AdvancedSearch->ToJson(), ","); // Field product_price
		$sFilterList = ew_Concat($sFilterList, $this->product_desc->AdvancedSearch->ToJson(), ","); // Field product_desc
		$sFilterList = ew_Concat($sFilterList, $this->product_image->AdvancedSearch->ToJson(), ","); // Field product_image
		$sFilterList = ew_Concat($sFilterList, $this->product_keywords->AdvancedSearch->ToJson(), ","); // Field product_keywords
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fproductslistsrch", $filters);

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

		// Field product_id
		$this->product_id->AdvancedSearch->SearchValue = @$filter["x_product_id"];
		$this->product_id->AdvancedSearch->SearchOperator = @$filter["z_product_id"];
		$this->product_id->AdvancedSearch->SearchCondition = @$filter["v_product_id"];
		$this->product_id->AdvancedSearch->SearchValue2 = @$filter["y_product_id"];
		$this->product_id->AdvancedSearch->SearchOperator2 = @$filter["w_product_id"];
		$this->product_id->AdvancedSearch->Save();

		// Field product_cat
		$this->product_cat->AdvancedSearch->SearchValue = @$filter["x_product_cat"];
		$this->product_cat->AdvancedSearch->SearchOperator = @$filter["z_product_cat"];
		$this->product_cat->AdvancedSearch->SearchCondition = @$filter["v_product_cat"];
		$this->product_cat->AdvancedSearch->SearchValue2 = @$filter["y_product_cat"];
		$this->product_cat->AdvancedSearch->SearchOperator2 = @$filter["w_product_cat"];
		$this->product_cat->AdvancedSearch->Save();

		// Field product_brand
		$this->product_brand->AdvancedSearch->SearchValue = @$filter["x_product_brand"];
		$this->product_brand->AdvancedSearch->SearchOperator = @$filter["z_product_brand"];
		$this->product_brand->AdvancedSearch->SearchCondition = @$filter["v_product_brand"];
		$this->product_brand->AdvancedSearch->SearchValue2 = @$filter["y_product_brand"];
		$this->product_brand->AdvancedSearch->SearchOperator2 = @$filter["w_product_brand"];
		$this->product_brand->AdvancedSearch->Save();

		// Field product_title
		$this->product_title->AdvancedSearch->SearchValue = @$filter["x_product_title"];
		$this->product_title->AdvancedSearch->SearchOperator = @$filter["z_product_title"];
		$this->product_title->AdvancedSearch->SearchCondition = @$filter["v_product_title"];
		$this->product_title->AdvancedSearch->SearchValue2 = @$filter["y_product_title"];
		$this->product_title->AdvancedSearch->SearchOperator2 = @$filter["w_product_title"];
		$this->product_title->AdvancedSearch->Save();

		// Field product_price
		$this->product_price->AdvancedSearch->SearchValue = @$filter["x_product_price"];
		$this->product_price->AdvancedSearch->SearchOperator = @$filter["z_product_price"];
		$this->product_price->AdvancedSearch->SearchCondition = @$filter["v_product_price"];
		$this->product_price->AdvancedSearch->SearchValue2 = @$filter["y_product_price"];
		$this->product_price->AdvancedSearch->SearchOperator2 = @$filter["w_product_price"];
		$this->product_price->AdvancedSearch->Save();

		// Field product_desc
		$this->product_desc->AdvancedSearch->SearchValue = @$filter["x_product_desc"];
		$this->product_desc->AdvancedSearch->SearchOperator = @$filter["z_product_desc"];
		$this->product_desc->AdvancedSearch->SearchCondition = @$filter["v_product_desc"];
		$this->product_desc->AdvancedSearch->SearchValue2 = @$filter["y_product_desc"];
		$this->product_desc->AdvancedSearch->SearchOperator2 = @$filter["w_product_desc"];
		$this->product_desc->AdvancedSearch->Save();

		// Field product_image
		$this->product_image->AdvancedSearch->SearchValue = @$filter["x_product_image"];
		$this->product_image->AdvancedSearch->SearchOperator = @$filter["z_product_image"];
		$this->product_image->AdvancedSearch->SearchCondition = @$filter["v_product_image"];
		$this->product_image->AdvancedSearch->SearchValue2 = @$filter["y_product_image"];
		$this->product_image->AdvancedSearch->SearchOperator2 = @$filter["w_product_image"];
		$this->product_image->AdvancedSearch->Save();

		// Field product_keywords
		$this->product_keywords->AdvancedSearch->SearchValue = @$filter["x_product_keywords"];
		$this->product_keywords->AdvancedSearch->SearchOperator = @$filter["z_product_keywords"];
		$this->product_keywords->AdvancedSearch->SearchCondition = @$filter["v_product_keywords"];
		$this->product_keywords->AdvancedSearch->SearchValue2 = @$filter["y_product_keywords"];
		$this->product_keywords->AdvancedSearch->SearchOperator2 = @$filter["w_product_keywords"];
		$this->product_keywords->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->product_title, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->product_desc, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->product_image, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->product_keywords, $arKeywords, $type);
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
			$this->UpdateSort($this->product_id); // product_id
			$this->UpdateSort($this->product_cat); // product_cat
			$this->UpdateSort($this->product_brand); // product_brand
			$this->UpdateSort($this->product_title); // product_title
			$this->UpdateSort($this->product_price); // product_price
			$this->UpdateSort($this->product_image); // product_image
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
				$this->setSessionOrderByList($sOrderBy);
				$this->product_id->setSort("");
				$this->product_cat->setSort("");
				$this->product_brand->setSort("");
				$this->product_title->setSort("");
				$this->product_price->setSort("");
				$this->product_image->setSort("");
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
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->product_id->CurrentValue) . "\">";
			return;
		}

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->IsLoggedIn()) {
			if (ew_IsMobile())
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
			else
				$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-table=\"products\" data-caption=\"" . $viewcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->ViewUrl) . "',btn:null});\">" . $Language->Phrase("ViewLink") . "</a>";
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
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-table=\"products\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'SaveBtn',url:'" . ew_HtmlEncode($this->EditUrl) . "'});\">" . $Language->Phrase("EditLink") . "</a>";
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
				$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-table=\"products\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->CopyUrl) . "'});\">" . $Language->Phrase("CopyLink") . "</a>";
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->product_id->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->product_id->CurrentValue . "\">";
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
			$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-table=\"products\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("AddLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fproductslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fproductslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fproductslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fproductslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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
		$this->product_keywords->CurrentValue = NULL;
		$this->product_keywords->OldValue = $this->product_keywords->CurrentValue;
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
		$this->GetUploadFiles(); // Get upload files
		if (!$this->product_id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->product_id->setFormValue($objForm->GetValue("x_product_id"));
		if (!$this->product_cat->FldIsDetailKey) {
			$this->product_cat->setFormValue($objForm->GetValue("x_product_cat"));
		}
		$this->product_cat->setOldValue($objForm->GetValue("o_product_cat"));
		if (!$this->product_brand->FldIsDetailKey) {
			$this->product_brand->setFormValue($objForm->GetValue("x_product_brand"));
		}
		$this->product_brand->setOldValue($objForm->GetValue("o_product_brand"));
		if (!$this->product_title->FldIsDetailKey) {
			$this->product_title->setFormValue($objForm->GetValue("x_product_title"));
		}
		$this->product_title->setOldValue($objForm->GetValue("o_product_title"));
		if (!$this->product_price->FldIsDetailKey) {
			$this->product_price->setFormValue($objForm->GetValue("x_product_price"));
		}
		$this->product_price->setOldValue($objForm->GetValue("o_product_price"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->product_id->CurrentValue = $this->product_id->FormValue;
		$this->product_cat->CurrentValue = $this->product_cat->FormValue;
		$this->product_brand->CurrentValue = $this->product_brand->FormValue;
		$this->product_title->CurrentValue = $this->product_title->FormValue;
		$this->product_price->CurrentValue = $this->product_price->FormValue;
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
				$this->product_image->LinkAttrs["data-rel"] = "products_x" . $this->RowCnt . "_product_image";
				ew_AppendClass($this->product_image->LinkAttrs["class"], "ewLightbox");
			}
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// product_id
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
					if ($this->RowIndex == '$rowindex$')
						$this->product_image->Upload->FileName = "";
					else
						$this->product_image->Upload->FileName = $this->product_image->CurrentValue;
			if (is_numeric($this->RowIndex) && !$this->EventCancelled) ew_RenderUploadField($this->product_image, $this->RowIndex);

			// Add refer script
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
					if ($this->RowIndex == '$rowindex$')
						$this->product_image->Upload->FileName = "";
					else
						$this->product_image->Upload->FileName = $this->product_image->CurrentValue;
			if (is_numeric($this->RowIndex) && !$this->EventCancelled) ew_RenderUploadField($this->product_image, $this->RowIndex);

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
		if ($this->product_image->Upload->FileName == "" && !$this->product_image->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_image->FldCaption(), $this->product_image->ReqErrMsg));
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
				$sThisKey .= $row['product_id'];

				// Delete old files
				$this->LoadDbValues($row);
				$this->product_image->OldUploadPath = "..\product_images";
				$OldFiles = ew_Empty($row['product_image']) ? array() : array($row['product_image']);
				$OldFileCount = count($OldFiles);
				for ($i = 0; $i < $OldFileCount; $i++) {
					if (file_exists($this->product_image->OldPhysicalUploadPath() . $OldFiles[$i]))
						@unlink($this->product_image->OldPhysicalUploadPath() . $OldFiles[$i]);
				}
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

			// product_image
			if ($this->product_image->Visible && !$this->product_image->ReadOnly && !$this->product_image->Upload->KeepFile) {
				$this->product_image->Upload->DbValue = $rsold['product_image']; // Get original value
				if ($this->product_image->Upload->FileName == "") {
					$rsnew['product_image'] = NULL;
				} else {
					$rsnew['product_image'] = $this->product_image->Upload->FileName;
				}
			}
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

		// product_image
		if ($this->product_image->Visible && !$this->product_image->Upload->KeepFile) {
			$this->product_image->Upload->DbValue = ""; // No need to delete old file
			if ($this->product_image->Upload->FileName == "") {
				$rsnew['product_image'] = NULL;
			} else {
				$rsnew['product_image'] = $this->product_image->Upload->FileName;
			}
		}
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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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
if (!isset($products_list)) $products_list = new cproducts_list();

// Page init
$products_list->Page_Init();

// Page main
$products_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$products_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fproductslist = new ew_Form("fproductslist", "list");
fproductslist.FormKeyCountName = '<?php echo $products_list->FormKeyCountName ?>';

// Validate form
fproductslist.Validate = function() {
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
			felm = this.GetElements("x" + infix + "_product_image");
			elm = this.GetElements("fn_x" + infix + "_product_image");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_image->FldCaption(), $products->product_image->ReqErrMsg)) ?>");

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
fproductslist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "product_cat", false)) return false;
	if (ew_ValueChanged(fobj, infix, "product_brand", false)) return false;
	if (ew_ValueChanged(fobj, infix, "product_title", false)) return false;
	if (ew_ValueChanged(fobj, infix, "product_price", false)) return false;
	if (ew_ValueChanged(fobj, infix, "product_image", false)) return false;
	return true;
}

// Form_CustomValidate event
fproductslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fproductslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fproductslist.Lists["x_product_cat"] = {"LinkField":"x_cat_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_cat_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"categories"};
fproductslist.Lists["x_product_cat"].Data = "<?php echo $products_list->product_cat->LookupFilterQuery(FALSE, "list") ?>";
fproductslist.Lists["x_product_brand"] = {"LinkField":"x_brand_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_brand_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"brands"};
fproductslist.Lists["x_product_brand"].Data = "<?php echo $products_list->product_brand->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = fproductslistsrch = new ew_Form("fproductslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if ($products_list->TotalRecs > 0 && $products_list->ExportOptions->Visible()) { ?>
<?php $products_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($products_list->SearchOptions->Visible()) { ?>
<?php $products_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($products_list->FilterOptions->Visible()) { ?>
<?php $products_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
if ($products->CurrentAction == "gridadd") {
	$products->CurrentFilter = "0=1";
	$products_list->StartRec = 1;
	$products_list->DisplayRecs = $products->GridAddRowCount;
	$products_list->TotalRecs = $products_list->DisplayRecs;
	$products_list->StopRec = $products_list->DisplayRecs;
} else {
	$bSelectLimit = $products_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($products_list->TotalRecs <= 0)
			$products_list->TotalRecs = $products->ListRecordCount();
	} else {
		if (!$products_list->Recordset && ($products_list->Recordset = $products_list->LoadRecordset()))
			$products_list->TotalRecs = $products_list->Recordset->RecordCount();
	}
	$products_list->StartRec = 1;
	if ($products_list->DisplayRecs <= 0 || ($products->Export <> "" && $products->ExportAll)) // Display all records
		$products_list->DisplayRecs = $products_list->TotalRecs;
	if (!($products->Export <> "" && $products->ExportAll))
		$products_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$products_list->Recordset = $products_list->LoadRecordset($products_list->StartRec-1, $products_list->DisplayRecs);

	// Set no record found message
	if ($products->CurrentAction == "" && $products_list->TotalRecs == 0) {
		if ($products_list->SearchWhere == "0=101")
			$products_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$products_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$products_list->RenderOtherOptions();
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($products->Export == "" && $products->CurrentAction == "") { ?>
<form name="fproductslistsrch" id="fproductslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($products_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fproductslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="products">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($products_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($products_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $products_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($products_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($products_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($products_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($products_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $products_list->ShowPageHeader(); ?>
<?php
$products_list->ShowMessage();
?>
<?php if ($products_list->TotalRecs > 0 || $products->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($products_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> products">
<form name="fproductslist" id="fproductslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($products_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $products_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="products">
<div id="gmp_products" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($products_list->TotalRecs > 0 || $products->CurrentAction == "add" || $products->CurrentAction == "copy" || $products->CurrentAction == "gridedit") { ?>
<table id="tbl_productslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$products_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$products_list->RenderListOptions();

// Render list options (header, left)
$products_list->ListOptions->Render("header", "left");
?>
<?php if ($products->product_id->Visible) { // product_id ?>
	<?php if ($products->SortUrl($products->product_id) == "") { ?>
		<th data-name="product_id" class="<?php echo $products->product_id->HeaderCellClass() ?>"><div id="elh_products_product_id" class="products_product_id"><div class="ewTableHeaderCaption"><?php echo $products->product_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="product_id" class="<?php echo $products->product_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $products->SortUrl($products->product_id) ?>',1);"><div id="elh_products_product_id" class="products_product_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $products->product_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($products->product_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($products->product_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($products->product_cat->Visible) { // product_cat ?>
	<?php if ($products->SortUrl($products->product_cat) == "") { ?>
		<th data-name="product_cat" class="<?php echo $products->product_cat->HeaderCellClass() ?>"><div id="elh_products_product_cat" class="products_product_cat"><div class="ewTableHeaderCaption"><?php echo $products->product_cat->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="product_cat" class="<?php echo $products->product_cat->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $products->SortUrl($products->product_cat) ?>',1);"><div id="elh_products_product_cat" class="products_product_cat">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $products->product_cat->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($products->product_cat->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($products->product_cat->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($products->product_brand->Visible) { // product_brand ?>
	<?php if ($products->SortUrl($products->product_brand) == "") { ?>
		<th data-name="product_brand" class="<?php echo $products->product_brand->HeaderCellClass() ?>"><div id="elh_products_product_brand" class="products_product_brand"><div class="ewTableHeaderCaption"><?php echo $products->product_brand->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="product_brand" class="<?php echo $products->product_brand->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $products->SortUrl($products->product_brand) ?>',1);"><div id="elh_products_product_brand" class="products_product_brand">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $products->product_brand->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($products->product_brand->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($products->product_brand->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($products->product_title->Visible) { // product_title ?>
	<?php if ($products->SortUrl($products->product_title) == "") { ?>
		<th data-name="product_title" class="<?php echo $products->product_title->HeaderCellClass() ?>"><div id="elh_products_product_title" class="products_product_title"><div class="ewTableHeaderCaption"><?php echo $products->product_title->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="product_title" class="<?php echo $products->product_title->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $products->SortUrl($products->product_title) ?>',1);"><div id="elh_products_product_title" class="products_product_title">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $products->product_title->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($products->product_title->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($products->product_title->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($products->product_price->Visible) { // product_price ?>
	<?php if ($products->SortUrl($products->product_price) == "") { ?>
		<th data-name="product_price" class="<?php echo $products->product_price->HeaderCellClass() ?>"><div id="elh_products_product_price" class="products_product_price"><div class="ewTableHeaderCaption"><?php echo $products->product_price->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="product_price" class="<?php echo $products->product_price->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $products->SortUrl($products->product_price) ?>',1);"><div id="elh_products_product_price" class="products_product_price">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $products->product_price->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($products->product_price->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($products->product_price->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($products->product_image->Visible) { // product_image ?>
	<?php if ($products->SortUrl($products->product_image) == "") { ?>
		<th data-name="product_image" class="<?php echo $products->product_image->HeaderCellClass() ?>"><div id="elh_products_product_image" class="products_product_image"><div class="ewTableHeaderCaption"><?php echo $products->product_image->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="product_image" class="<?php echo $products->product_image->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $products->SortUrl($products->product_image) ?>',1);"><div id="elh_products_product_image" class="products_product_image">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $products->product_image->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($products->product_image->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($products->product_image->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$products_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($products->CurrentAction == "add" || $products->CurrentAction == "copy") {
		$products_list->RowIndex = 0;
		$products_list->KeyCount = $products_list->RowIndex;
		if ($products->CurrentAction == "copy" && !$products_list->LoadRow())
			$products->CurrentAction = "add";
		if ($products->CurrentAction == "add")
			$products_list->LoadRowValues();
		if ($products->EventCancelled) // Insert failed
			$products_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$products->ResetAttrs();
		$products->RowAttrs = array_merge($products->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_products', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$products->RowType = EW_ROWTYPE_ADD;

		// Render row
		$products_list->RenderRow();

		// Render list options
		$products_list->RenderListOptions();
		$products_list->StartRowCnt = 0;
?>
	<tr<?php echo $products->RowAttributes() ?>>
<?php

// Render list options (body, left)
$products_list->ListOptions->Render("body", "left", $products_list->RowCnt);
?>
	<?php if ($products->product_id->Visible) { // product_id ?>
		<td data-name="product_id">
<input type="hidden" data-table="products" data-field="x_product_id" name="o<?php echo $products_list->RowIndex ?>_product_id" id="o<?php echo $products_list->RowIndex ?>_product_id" value="<?php echo ew_HtmlEncode($products->product_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->product_cat->Visible) { // product_cat ?>
		<td data-name="product_cat">
<span id="el<?php echo $products_list->RowCnt ?>_products_product_cat" class="form-group products_product_cat">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $products_list->RowIndex ?>_product_cat"><?php echo (strval($products->product_cat->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $products->product_cat->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($products->product_cat->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $products_list->RowIndex ?>_product_cat',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($products->product_cat->ReadOnly || $products->product_cat->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="products" data-field="x_product_cat" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $products->product_cat->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $products_list->RowIndex ?>_product_cat" id="x<?php echo $products_list->RowIndex ?>_product_cat" value="<?php echo $products->product_cat->CurrentValue ?>"<?php echo $products->product_cat->EditAttributes() ?>>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $products->product_cat->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $products_list->RowIndex ?>_product_cat',url:'categoriesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $products_list->RowIndex ?>_product_cat"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $products->product_cat->FldCaption() ?></span></button>
</span>
<input type="hidden" data-table="products" data-field="x_product_cat" name="o<?php echo $products_list->RowIndex ?>_product_cat" id="o<?php echo $products_list->RowIndex ?>_product_cat" value="<?php echo ew_HtmlEncode($products->product_cat->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->product_brand->Visible) { // product_brand ?>
		<td data-name="product_brand">
<span id="el<?php echo $products_list->RowCnt ?>_products_product_brand" class="form-group products_product_brand">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $products_list->RowIndex ?>_product_brand"><?php echo (strval($products->product_brand->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $products->product_brand->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($products->product_brand->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $products_list->RowIndex ?>_product_brand',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($products->product_brand->ReadOnly || $products->product_brand->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="products" data-field="x_product_brand" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $products->product_brand->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $products_list->RowIndex ?>_product_brand" id="x<?php echo $products_list->RowIndex ?>_product_brand" value="<?php echo $products->product_brand->CurrentValue ?>"<?php echo $products->product_brand->EditAttributes() ?>>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $products->product_brand->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $products_list->RowIndex ?>_product_brand',url:'brandsaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $products_list->RowIndex ?>_product_brand"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $products->product_brand->FldCaption() ?></span></button>
</span>
<input type="hidden" data-table="products" data-field="x_product_brand" name="o<?php echo $products_list->RowIndex ?>_product_brand" id="o<?php echo $products_list->RowIndex ?>_product_brand" value="<?php echo ew_HtmlEncode($products->product_brand->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->product_title->Visible) { // product_title ?>
		<td data-name="product_title">
<span id="el<?php echo $products_list->RowCnt ?>_products_product_title" class="form-group products_product_title">
<input type="text" data-table="products" data-field="x_product_title" name="x<?php echo $products_list->RowIndex ?>_product_title" id="x<?php echo $products_list->RowIndex ?>_product_title" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($products->product_title->getPlaceHolder()) ?>" value="<?php echo $products->product_title->EditValue ?>"<?php echo $products->product_title->EditAttributes() ?>>
</span>
<input type="hidden" data-table="products" data-field="x_product_title" name="o<?php echo $products_list->RowIndex ?>_product_title" id="o<?php echo $products_list->RowIndex ?>_product_title" value="<?php echo ew_HtmlEncode($products->product_title->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->product_price->Visible) { // product_price ?>
		<td data-name="product_price">
<span id="el<?php echo $products_list->RowCnt ?>_products_product_price" class="form-group products_product_price">
<input type="text" data-table="products" data-field="x_product_price" name="x<?php echo $products_list->RowIndex ?>_product_price" id="x<?php echo $products_list->RowIndex ?>_product_price" size="30" placeholder="<?php echo ew_HtmlEncode($products->product_price->getPlaceHolder()) ?>" value="<?php echo $products->product_price->EditValue ?>"<?php echo $products->product_price->EditAttributes() ?>>
</span>
<input type="hidden" data-table="products" data-field="x_product_price" name="o<?php echo $products_list->RowIndex ?>_product_price" id="o<?php echo $products_list->RowIndex ?>_product_price" value="<?php echo ew_HtmlEncode($products->product_price->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->product_image->Visible) { // product_image ?>
		<td data-name="product_image">
<span id="el<?php echo $products_list->RowCnt ?>_products_product_image" class="form-group products_product_image">
<div id="fd_x<?php echo $products_list->RowIndex ?>_product_image">
<span title="<?php echo $products->product_image->FldTitle() ? $products->product_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_image->ReadOnly || $products->product_image->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_image" name="x<?php echo $products_list->RowIndex ?>_product_image" id="x<?php echo $products_list->RowIndex ?>_product_image"<?php echo $products->product_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $products_list->RowIndex ?>_product_image" id= "fn_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $products_list->RowIndex ?>_product_image" id= "fa_x<?php echo $products_list->RowIndex ?>_product_image" value="0">
<input type="hidden" name="fs_x<?php echo $products_list->RowIndex ?>_product_image" id= "fs_x<?php echo $products_list->RowIndex ?>_product_image" value="65535">
<input type="hidden" name="fx_x<?php echo $products_list->RowIndex ?>_product_image" id= "fx_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $products_list->RowIndex ?>_product_image" id= "fm_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $products_list->RowIndex ?>_product_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="products" data-field="x_product_image" name="o<?php echo $products_list->RowIndex ?>_product_image" id="o<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo ew_HtmlEncode($products->product_image->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$products_list->ListOptions->Render("body", "right", $products_list->RowCnt);
?>
<script type="text/javascript">
fproductslist.UpdateOpts(<?php echo $products_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($products->ExportAll && $products->Export <> "") {
	$products_list->StopRec = $products_list->TotalRecs;
} else {

	// Set the last record to display
	if ($products_list->TotalRecs > $products_list->StartRec + $products_list->DisplayRecs - 1)
		$products_list->StopRec = $products_list->StartRec + $products_list->DisplayRecs - 1;
	else
		$products_list->StopRec = $products_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($products_list->FormKeyCountName) && ($products->CurrentAction == "gridadd" || $products->CurrentAction == "gridedit" || $products->CurrentAction == "F")) {
		$products_list->KeyCount = $objForm->GetValue($products_list->FormKeyCountName);
		$products_list->StopRec = $products_list->StartRec + $products_list->KeyCount - 1;
	}
}
$products_list->RecCnt = $products_list->StartRec - 1;
if ($products_list->Recordset && !$products_list->Recordset->EOF) {
	$products_list->Recordset->MoveFirst();
	$bSelectLimit = $products_list->UseSelectLimit;
	if (!$bSelectLimit && $products_list->StartRec > 1)
		$products_list->Recordset->Move($products_list->StartRec - 1);
} elseif (!$products->AllowAddDeleteRow && $products_list->StopRec == 0) {
	$products_list->StopRec = $products->GridAddRowCount;
}

// Initialize aggregate
$products->RowType = EW_ROWTYPE_AGGREGATEINIT;
$products->ResetAttrs();
$products_list->RenderRow();
$products_list->EditRowCnt = 0;
if ($products->CurrentAction == "edit")
	$products_list->RowIndex = 1;
if ($products->CurrentAction == "gridadd")
	$products_list->RowIndex = 0;
if ($products->CurrentAction == "gridedit")
	$products_list->RowIndex = 0;
while ($products_list->RecCnt < $products_list->StopRec) {
	$products_list->RecCnt++;
	if (intval($products_list->RecCnt) >= intval($products_list->StartRec)) {
		$products_list->RowCnt++;
		if ($products->CurrentAction == "gridadd" || $products->CurrentAction == "gridedit" || $products->CurrentAction == "F") {
			$products_list->RowIndex++;
			$objForm->Index = $products_list->RowIndex;
			if ($objForm->HasValue($products_list->FormActionName))
				$products_list->RowAction = strval($objForm->GetValue($products_list->FormActionName));
			elseif ($products->CurrentAction == "gridadd")
				$products_list->RowAction = "insert";
			else
				$products_list->RowAction = "";
		}

		// Set up key count
		$products_list->KeyCount = $products_list->RowIndex;

		// Init row class and style
		$products->ResetAttrs();
		$products->CssClass = "";
		if ($products->CurrentAction == "gridadd") {
			$products_list->LoadRowValues(); // Load default values
		} else {
			$products_list->LoadRowValues($products_list->Recordset); // Load row values
		}
		$products->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($products->CurrentAction == "gridadd") // Grid add
			$products->RowType = EW_ROWTYPE_ADD; // Render add
		if ($products->CurrentAction == "gridadd" && $products->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$products_list->RestoreCurrentRowFormValues($products_list->RowIndex); // Restore form values
		if ($products->CurrentAction == "edit") {
			if ($products_list->CheckInlineEditKey() && $products_list->EditRowCnt == 0) { // Inline edit
				$products->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($products->CurrentAction == "gridedit") { // Grid edit
			if ($products->EventCancelled) {
				$products_list->RestoreCurrentRowFormValues($products_list->RowIndex); // Restore form values
			}
			if ($products_list->RowAction == "insert")
				$products->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$products->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($products->CurrentAction == "edit" && $products->RowType == EW_ROWTYPE_EDIT && $products->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$products_list->RestoreFormValues(); // Restore form values
		}
		if ($products->CurrentAction == "gridedit" && ($products->RowType == EW_ROWTYPE_EDIT || $products->RowType == EW_ROWTYPE_ADD) && $products->EventCancelled) // Update failed
			$products_list->RestoreCurrentRowFormValues($products_list->RowIndex); // Restore form values
		if ($products->RowType == EW_ROWTYPE_EDIT) // Edit row
			$products_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$products->RowAttrs = array_merge($products->RowAttrs, array('data-rowindex'=>$products_list->RowCnt, 'id'=>'r' . $products_list->RowCnt . '_products', 'data-rowtype'=>$products->RowType));

		// Render row
		$products_list->RenderRow();

		// Render list options
		$products_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($products_list->RowAction <> "delete" && $products_list->RowAction <> "insertdelete" && !($products_list->RowAction == "insert" && $products->CurrentAction == "F" && $products_list->EmptyRow())) {
?>
	<tr<?php echo $products->RowAttributes() ?>>
<?php

// Render list options (body, left)
$products_list->ListOptions->Render("body", "left", $products_list->RowCnt);
?>
	<?php if ($products->product_id->Visible) { // product_id ?>
		<td data-name="product_id"<?php echo $products->product_id->CellAttributes() ?>>
<?php if ($products->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="products" data-field="x_product_id" name="o<?php echo $products_list->RowIndex ?>_product_id" id="o<?php echo $products_list->RowIndex ?>_product_id" value="<?php echo ew_HtmlEncode($products->product_id->OldValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_id" class="form-group products_product_id">
<span<?php echo $products->product_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $products->product_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="products" data-field="x_product_id" name="x<?php echo $products_list->RowIndex ?>_product_id" id="x<?php echo $products_list->RowIndex ?>_product_id" value="<?php echo ew_HtmlEncode($products->product_id->CurrentValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_id" class="products_product_id">
<span<?php echo $products->product_id->ViewAttributes() ?>>
<?php echo $products->product_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($products->product_cat->Visible) { // product_cat ?>
		<td data-name="product_cat"<?php echo $products->product_cat->CellAttributes() ?>>
<?php if ($products->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_cat" class="form-group products_product_cat">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $products_list->RowIndex ?>_product_cat"><?php echo (strval($products->product_cat->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $products->product_cat->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($products->product_cat->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $products_list->RowIndex ?>_product_cat',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($products->product_cat->ReadOnly || $products->product_cat->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="products" data-field="x_product_cat" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $products->product_cat->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $products_list->RowIndex ?>_product_cat" id="x<?php echo $products_list->RowIndex ?>_product_cat" value="<?php echo $products->product_cat->CurrentValue ?>"<?php echo $products->product_cat->EditAttributes() ?>>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $products->product_cat->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $products_list->RowIndex ?>_product_cat',url:'categoriesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $products_list->RowIndex ?>_product_cat"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $products->product_cat->FldCaption() ?></span></button>
</span>
<input type="hidden" data-table="products" data-field="x_product_cat" name="o<?php echo $products_list->RowIndex ?>_product_cat" id="o<?php echo $products_list->RowIndex ?>_product_cat" value="<?php echo ew_HtmlEncode($products->product_cat->OldValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_cat" class="form-group products_product_cat">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $products_list->RowIndex ?>_product_cat"><?php echo (strval($products->product_cat->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $products->product_cat->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($products->product_cat->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $products_list->RowIndex ?>_product_cat',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($products->product_cat->ReadOnly || $products->product_cat->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="products" data-field="x_product_cat" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $products->product_cat->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $products_list->RowIndex ?>_product_cat" id="x<?php echo $products_list->RowIndex ?>_product_cat" value="<?php echo $products->product_cat->CurrentValue ?>"<?php echo $products->product_cat->EditAttributes() ?>>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $products->product_cat->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $products_list->RowIndex ?>_product_cat',url:'categoriesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $products_list->RowIndex ?>_product_cat"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $products->product_cat->FldCaption() ?></span></button>
</span>
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_cat" class="products_product_cat">
<span<?php echo $products->product_cat->ViewAttributes() ?>>
<?php echo $products->product_cat->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($products->product_brand->Visible) { // product_brand ?>
		<td data-name="product_brand"<?php echo $products->product_brand->CellAttributes() ?>>
<?php if ($products->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_brand" class="form-group products_product_brand">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $products_list->RowIndex ?>_product_brand"><?php echo (strval($products->product_brand->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $products->product_brand->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($products->product_brand->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $products_list->RowIndex ?>_product_brand',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($products->product_brand->ReadOnly || $products->product_brand->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="products" data-field="x_product_brand" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $products->product_brand->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $products_list->RowIndex ?>_product_brand" id="x<?php echo $products_list->RowIndex ?>_product_brand" value="<?php echo $products->product_brand->CurrentValue ?>"<?php echo $products->product_brand->EditAttributes() ?>>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $products->product_brand->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $products_list->RowIndex ?>_product_brand',url:'brandsaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $products_list->RowIndex ?>_product_brand"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $products->product_brand->FldCaption() ?></span></button>
</span>
<input type="hidden" data-table="products" data-field="x_product_brand" name="o<?php echo $products_list->RowIndex ?>_product_brand" id="o<?php echo $products_list->RowIndex ?>_product_brand" value="<?php echo ew_HtmlEncode($products->product_brand->OldValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_brand" class="form-group products_product_brand">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $products_list->RowIndex ?>_product_brand"><?php echo (strval($products->product_brand->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $products->product_brand->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($products->product_brand->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $products_list->RowIndex ?>_product_brand',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($products->product_brand->ReadOnly || $products->product_brand->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="products" data-field="x_product_brand" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $products->product_brand->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $products_list->RowIndex ?>_product_brand" id="x<?php echo $products_list->RowIndex ?>_product_brand" value="<?php echo $products->product_brand->CurrentValue ?>"<?php echo $products->product_brand->EditAttributes() ?>>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $products->product_brand->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $products_list->RowIndex ?>_product_brand',url:'brandsaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $products_list->RowIndex ?>_product_brand"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $products->product_brand->FldCaption() ?></span></button>
</span>
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_brand" class="products_product_brand">
<span<?php echo $products->product_brand->ViewAttributes() ?>>
<?php echo $products->product_brand->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($products->product_title->Visible) { // product_title ?>
		<td data-name="product_title"<?php echo $products->product_title->CellAttributes() ?>>
<?php if ($products->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_title" class="form-group products_product_title">
<input type="text" data-table="products" data-field="x_product_title" name="x<?php echo $products_list->RowIndex ?>_product_title" id="x<?php echo $products_list->RowIndex ?>_product_title" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($products->product_title->getPlaceHolder()) ?>" value="<?php echo $products->product_title->EditValue ?>"<?php echo $products->product_title->EditAttributes() ?>>
</span>
<input type="hidden" data-table="products" data-field="x_product_title" name="o<?php echo $products_list->RowIndex ?>_product_title" id="o<?php echo $products_list->RowIndex ?>_product_title" value="<?php echo ew_HtmlEncode($products->product_title->OldValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_title" class="form-group products_product_title">
<input type="text" data-table="products" data-field="x_product_title" name="x<?php echo $products_list->RowIndex ?>_product_title" id="x<?php echo $products_list->RowIndex ?>_product_title" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($products->product_title->getPlaceHolder()) ?>" value="<?php echo $products->product_title->EditValue ?>"<?php echo $products->product_title->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_title" class="products_product_title">
<span<?php echo $products->product_title->ViewAttributes() ?>>
<?php echo $products->product_title->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($products->product_price->Visible) { // product_price ?>
		<td data-name="product_price"<?php echo $products->product_price->CellAttributes() ?>>
<?php if ($products->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_price" class="form-group products_product_price">
<input type="text" data-table="products" data-field="x_product_price" name="x<?php echo $products_list->RowIndex ?>_product_price" id="x<?php echo $products_list->RowIndex ?>_product_price" size="30" placeholder="<?php echo ew_HtmlEncode($products->product_price->getPlaceHolder()) ?>" value="<?php echo $products->product_price->EditValue ?>"<?php echo $products->product_price->EditAttributes() ?>>
</span>
<input type="hidden" data-table="products" data-field="x_product_price" name="o<?php echo $products_list->RowIndex ?>_product_price" id="o<?php echo $products_list->RowIndex ?>_product_price" value="<?php echo ew_HtmlEncode($products->product_price->OldValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_price" class="form-group products_product_price">
<input type="text" data-table="products" data-field="x_product_price" name="x<?php echo $products_list->RowIndex ?>_product_price" id="x<?php echo $products_list->RowIndex ?>_product_price" size="30" placeholder="<?php echo ew_HtmlEncode($products->product_price->getPlaceHolder()) ?>" value="<?php echo $products->product_price->EditValue ?>"<?php echo $products->product_price->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_price" class="products_product_price">
<span<?php echo $products->product_price->ViewAttributes() ?>>
<?php echo $products->product_price->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($products->product_image->Visible) { // product_image ?>
		<td data-name="product_image"<?php echo $products->product_image->CellAttributes() ?>>
<?php if ($products->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_image" class="form-group products_product_image">
<div id="fd_x<?php echo $products_list->RowIndex ?>_product_image">
<span title="<?php echo $products->product_image->FldTitle() ? $products->product_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_image->ReadOnly || $products->product_image->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_image" name="x<?php echo $products_list->RowIndex ?>_product_image" id="x<?php echo $products_list->RowIndex ?>_product_image"<?php echo $products->product_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $products_list->RowIndex ?>_product_image" id= "fn_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $products_list->RowIndex ?>_product_image" id= "fa_x<?php echo $products_list->RowIndex ?>_product_image" value="0">
<input type="hidden" name="fs_x<?php echo $products_list->RowIndex ?>_product_image" id= "fs_x<?php echo $products_list->RowIndex ?>_product_image" value="65535">
<input type="hidden" name="fx_x<?php echo $products_list->RowIndex ?>_product_image" id= "fx_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $products_list->RowIndex ?>_product_image" id= "fm_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $products_list->RowIndex ?>_product_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="products" data-field="x_product_image" name="o<?php echo $products_list->RowIndex ?>_product_image" id="o<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo ew_HtmlEncode($products->product_image->OldValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_image" class="form-group products_product_image">
<div id="fd_x<?php echo $products_list->RowIndex ?>_product_image">
<span title="<?php echo $products->product_image->FldTitle() ? $products->product_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_image->ReadOnly || $products->product_image->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_image" name="x<?php echo $products_list->RowIndex ?>_product_image" id="x<?php echo $products_list->RowIndex ?>_product_image"<?php echo $products->product_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $products_list->RowIndex ?>_product_image" id= "fn_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $products_list->RowIndex ?>_product_image"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $products_list->RowIndex ?>_product_image" id= "fa_x<?php echo $products_list->RowIndex ?>_product_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $products_list->RowIndex ?>_product_image" id= "fa_x<?php echo $products_list->RowIndex ?>_product_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $products_list->RowIndex ?>_product_image" id= "fs_x<?php echo $products_list->RowIndex ?>_product_image" value="65535">
<input type="hidden" name="fx_x<?php echo $products_list->RowIndex ?>_product_image" id= "fx_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $products_list->RowIndex ?>_product_image" id= "fm_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $products_list->RowIndex ?>_product_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_image" class="products_product_image">
<span>
<?php echo ew_GetFileViewTag($products->product_image, $products->product_image->ListViewValue()) ?>
</span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$products_list->ListOptions->Render("body", "right", $products_list->RowCnt);
?>
	</tr>
<?php if ($products->RowType == EW_ROWTYPE_ADD || $products->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fproductslist.UpdateOpts(<?php echo $products_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($products->CurrentAction <> "gridadd")
		if (!$products_list->Recordset->EOF) $products_list->Recordset->MoveNext();
}
?>
<?php
	if ($products->CurrentAction == "gridadd" || $products->CurrentAction == "gridedit") {
		$products_list->RowIndex = '$rowindex$';
		$products_list->LoadRowValues();

		// Set row properties
		$products->ResetAttrs();
		$products->RowAttrs = array_merge($products->RowAttrs, array('data-rowindex'=>$products_list->RowIndex, 'id'=>'r0_products', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($products->RowAttrs["class"], "ewTemplate");
		$products->RowType = EW_ROWTYPE_ADD;

		// Render row
		$products_list->RenderRow();

		// Render list options
		$products_list->RenderListOptions();
		$products_list->StartRowCnt = 0;
?>
	<tr<?php echo $products->RowAttributes() ?>>
<?php

// Render list options (body, left)
$products_list->ListOptions->Render("body", "left", $products_list->RowIndex);
?>
	<?php if ($products->product_id->Visible) { // product_id ?>
		<td data-name="product_id">
<input type="hidden" data-table="products" data-field="x_product_id" name="o<?php echo $products_list->RowIndex ?>_product_id" id="o<?php echo $products_list->RowIndex ?>_product_id" value="<?php echo ew_HtmlEncode($products->product_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->product_cat->Visible) { // product_cat ?>
		<td data-name="product_cat">
<span id="el$rowindex$_products_product_cat" class="form-group products_product_cat">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $products_list->RowIndex ?>_product_cat"><?php echo (strval($products->product_cat->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $products->product_cat->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($products->product_cat->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $products_list->RowIndex ?>_product_cat',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($products->product_cat->ReadOnly || $products->product_cat->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="products" data-field="x_product_cat" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $products->product_cat->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $products_list->RowIndex ?>_product_cat" id="x<?php echo $products_list->RowIndex ?>_product_cat" value="<?php echo $products->product_cat->CurrentValue ?>"<?php echo $products->product_cat->EditAttributes() ?>>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $products->product_cat->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $products_list->RowIndex ?>_product_cat',url:'categoriesaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $products_list->RowIndex ?>_product_cat"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $products->product_cat->FldCaption() ?></span></button>
</span>
<input type="hidden" data-table="products" data-field="x_product_cat" name="o<?php echo $products_list->RowIndex ?>_product_cat" id="o<?php echo $products_list->RowIndex ?>_product_cat" value="<?php echo ew_HtmlEncode($products->product_cat->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->product_brand->Visible) { // product_brand ?>
		<td data-name="product_brand">
<span id="el$rowindex$_products_product_brand" class="form-group products_product_brand">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $products_list->RowIndex ?>_product_brand"><?php echo (strval($products->product_brand->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $products->product_brand->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($products->product_brand->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $products_list->RowIndex ?>_product_brand',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($products->product_brand->ReadOnly || $products->product_brand->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="products" data-field="x_product_brand" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $products->product_brand->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $products_list->RowIndex ?>_product_brand" id="x<?php echo $products_list->RowIndex ?>_product_brand" value="<?php echo $products->product_brand->CurrentValue ?>"<?php echo $products->product_brand->EditAttributes() ?>>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $products->product_brand->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $products_list->RowIndex ?>_product_brand',url:'brandsaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $products_list->RowIndex ?>_product_brand"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $products->product_brand->FldCaption() ?></span></button>
</span>
<input type="hidden" data-table="products" data-field="x_product_brand" name="o<?php echo $products_list->RowIndex ?>_product_brand" id="o<?php echo $products_list->RowIndex ?>_product_brand" value="<?php echo ew_HtmlEncode($products->product_brand->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->product_title->Visible) { // product_title ?>
		<td data-name="product_title">
<span id="el$rowindex$_products_product_title" class="form-group products_product_title">
<input type="text" data-table="products" data-field="x_product_title" name="x<?php echo $products_list->RowIndex ?>_product_title" id="x<?php echo $products_list->RowIndex ?>_product_title" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($products->product_title->getPlaceHolder()) ?>" value="<?php echo $products->product_title->EditValue ?>"<?php echo $products->product_title->EditAttributes() ?>>
</span>
<input type="hidden" data-table="products" data-field="x_product_title" name="o<?php echo $products_list->RowIndex ?>_product_title" id="o<?php echo $products_list->RowIndex ?>_product_title" value="<?php echo ew_HtmlEncode($products->product_title->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->product_price->Visible) { // product_price ?>
		<td data-name="product_price">
<span id="el$rowindex$_products_product_price" class="form-group products_product_price">
<input type="text" data-table="products" data-field="x_product_price" name="x<?php echo $products_list->RowIndex ?>_product_price" id="x<?php echo $products_list->RowIndex ?>_product_price" size="30" placeholder="<?php echo ew_HtmlEncode($products->product_price->getPlaceHolder()) ?>" value="<?php echo $products->product_price->EditValue ?>"<?php echo $products->product_price->EditAttributes() ?>>
</span>
<input type="hidden" data-table="products" data-field="x_product_price" name="o<?php echo $products_list->RowIndex ?>_product_price" id="o<?php echo $products_list->RowIndex ?>_product_price" value="<?php echo ew_HtmlEncode($products->product_price->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->product_image->Visible) { // product_image ?>
		<td data-name="product_image">
<span id="el$rowindex$_products_product_image" class="form-group products_product_image">
<div id="fd_x<?php echo $products_list->RowIndex ?>_product_image">
<span title="<?php echo $products->product_image->FldTitle() ? $products->product_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_image->ReadOnly || $products->product_image->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_image" name="x<?php echo $products_list->RowIndex ?>_product_image" id="x<?php echo $products_list->RowIndex ?>_product_image"<?php echo $products->product_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $products_list->RowIndex ?>_product_image" id= "fn_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $products_list->RowIndex ?>_product_image" id= "fa_x<?php echo $products_list->RowIndex ?>_product_image" value="0">
<input type="hidden" name="fs_x<?php echo $products_list->RowIndex ?>_product_image" id= "fs_x<?php echo $products_list->RowIndex ?>_product_image" value="65535">
<input type="hidden" name="fx_x<?php echo $products_list->RowIndex ?>_product_image" id= "fx_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $products_list->RowIndex ?>_product_image" id= "fm_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $products_list->RowIndex ?>_product_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="products" data-field="x_product_image" name="o<?php echo $products_list->RowIndex ?>_product_image" id="o<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo ew_HtmlEncode($products->product_image->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$products_list->ListOptions->Render("body", "right", $products_list->RowIndex);
?>
<script type="text/javascript">
fproductslist.UpdateOpts(<?php echo $products_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($products->CurrentAction == "add" || $products->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $products_list->FormKeyCountName ?>" id="<?php echo $products_list->FormKeyCountName ?>" value="<?php echo $products_list->KeyCount ?>">
<?php } ?>
<?php if ($products->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $products_list->FormKeyCountName ?>" id="<?php echo $products_list->FormKeyCountName ?>" value="<?php echo $products_list->KeyCount ?>">
<?php echo $products_list->MultiSelectKey ?>
<?php } ?>
<?php if ($products->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $products_list->FormKeyCountName ?>" id="<?php echo $products_list->FormKeyCountName ?>" value="<?php echo $products_list->KeyCount ?>">
<?php } ?>
<?php if ($products->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $products_list->FormKeyCountName ?>" id="<?php echo $products_list->FormKeyCountName ?>" value="<?php echo $products_list->KeyCount ?>">
<?php echo $products_list->MultiSelectKey ?>
<?php } ?>
<?php if ($products->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($products_list->Recordset)
	$products_list->Recordset->Close();
?>
<div class="box-footer ewGridLowerPanel">
<?php if ($products->CurrentAction <> "gridadd" && $products->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($products_list->Pager)) $products_list->Pager = new cPrevNextPager($products_list->StartRec, $products_list->DisplayRecs, $products_list->TotalRecs, $products_list->AutoHidePager) ?>
<?php if ($products_list->Pager->RecordCount > 0 && $products_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($products_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $products_list->PageUrl() ?>start=<?php echo $products_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($products_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $products_list->PageUrl() ?>start=<?php echo $products_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $products_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($products_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $products_list->PageUrl() ?>start=<?php echo $products_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($products_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $products_list->PageUrl() ?>start=<?php echo $products_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $products_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($products_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $products_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $products_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $products_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($products_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($products_list->TotalRecs == 0 && $products->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($products_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fproductslistsrch.FilterList = <?php echo $products_list->GetFilterList() ?>;
fproductslistsrch.Init();
fproductslist.Init();
</script>
<?php
$products_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$products_list->Page_Terminate();
?>
