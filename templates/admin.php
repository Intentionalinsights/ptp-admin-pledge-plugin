<?php
/**
 * @package  PTPAdminPledgePlugin
 */
/**
* Plugin Name: Pro-Truth Pledge Admin Management Page
* Description: Manages the Pro Truth Pledge form data
* Version: 1.0
* Author: Pro-Truth Pledge developers
* Author URI: protruthpledge.org
*/

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Pledgers_List_Table extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Pledge', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Pledgers', 'sp' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?

		] );

	}

	/**
	 * Retrieve customer’s data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_pledgers( $per_page = 5, $page_number = 1 ) {
	  global $wpdb;

	  $sql = "SELECT * FROM {$wpdb->prefix}ptp_pledges";
	  if ( ! empty( $_REQUEST['orderby'] ) ) {
	    $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
	    $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
	  }

	  $sql .= " LIMIT $per_page";

	  $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


	  $result = $wpdb->get_results( $sql, 'ARRAY_A' );
	  return $result;
	}

	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_pledger( $id ) {
	  global $wpdb;

	  echo '<h1>Deleted record: ' . $id . '</h1>';

	  $wpdb->delete(
	    "{$wpdb->prefix}ptp_pledges",
	    [ 'pledgeId' => $id ],
	    [ '%d' ]
	  );
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
	  global $wpdb;

	  $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}ptp_pledges";

	  return $wpdb->get_var( $sql );
	}

	/**
	 * Disabled:  BULK ITEMS MANAGEMENT FOR THE FUTURE
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 
	public function get_bulk_actions() {
	  $actions = [
	  	// 'bulk-edit' => 'Edit',
	   //  'bulk-delete' => 'Delete'
	  ];

	  return $actions;
	}


	function process_bulk_action() {
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            echo '<h1>Items deleted (or they would be if we had items to delete)!</h1>';
        }

                //Detect when a bulk action is being triggered...
        if( 'edit'===$this->current_action() ) {
            echo '<h1>Items edited (or they would be if we had items to delete)!</h1>';
        }
    }

	function column_cb( $item ) {
	  return sprintf(
	    '<input type="checkbox" name="pledge_alter" value="%s" />', $item['pledgeId']
	  );
	}

    */

	/** ************************************************************************
     * Recommended. This is a custom column method and is responsible for what
     * is rendered in any column with a name/slug of 'title'. Every time the class
     * needs to render a column, it first looks for a method named 
     * column_{$column_title} - if it exists, that method is run. If it doesn't
     * exist, column_default() is called instead.
     * 
     * This example also illustrates how to implement rollover actions. Actions
     * should be an associative array formatted as 'slug'=>'link html' - and you
     * will need to generate the URLs yourself. You could even ensure the links
     * 
     * 
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
	 * @param array $item an array of DB data
	 *
	 * @return string
	 **************************************************************************/
	function column_pledgeId( $item ) {
	  // create a nonce
	  $delete_nonce = wp_create_nonce( 'sp_delete_pledge' );

	  $actions = [
	  	'edit' => sprintf( '<a href="?page=%s&action=%s&pledgers_edit=%s">Edit</a>', 'ptp_edit_pledge', 'edit', absint( $item['pledgeId'] ) ),
	    'delete' => sprintf( '<a href="?page=%s&action=%s&pledgers_delete=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['pledgeId'] ), $delete_nonce )
	  ];

	  return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item['pledgeId'],
            /*$2%s*/ $item['key'],
            /*$3%s*/ $this->row_actions($actions));
	}

	/**
	 * Render a column when no column specific method exists.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
	  return $item[ $column_name ];
	}

	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
        $columns = array(
            //'cb'        => '<input type="checkbox" />', //* Disabled:  BULK ITEMS MANAGEMENT FOR THE FUTURE
			'pledgeId' => 'PledgeId',
	        'category' => 'Category',
			'step' => 'Step',
	        'fName' => 'FName',
	        'lName' => 'LName',
	        'groupName' => 'GroupName',
	        'email' => 'Email',
	        'volunteer' => 'Volunteer',
	        'emailList' => 'EmailList',
	        'directory' => 'Directory',
	        'emailAlerts' => 'EmailAlerts',
	        'textAlerts' => 'TextAlerts',
	        'repNudge' => 'RepNudge',
	        'address1' => 'Address1',
	        'address2' => 'Address2',
	        'city' => 'City',
	        'region' => 'Region',
	        'zip' => 'Zip',
	        'country' => 'Country',
	        'orgs' => 'Orgs',
	        'phone' => 'Phone',
	        'description' => 'Description',
	        'imageUrl' => 'ImageUrl',
	        'linkText1' => 'LinkText1',
	        'linkUrl1' => 'LinkUrl1',
	        'linkText2' => 'LinkText2',
	        'linkUrl2' => 'LinkUrl2',
	        'linkText3' => 'LinkText3',
	        'linkUrl3' => 'LinkUrl3',
			// 'vaddress1' => '',
	  //       'vaddress2' => '',
	  //       'vaddress3' => '',
	  //       'vcity' => '',
	  //       'vregion' => '',
	  //       'vzip' => '',
	  //       'vcountry' => '',
			'created' => 'Created',
			'edited' => 'Edited'
        );
        return $columns;
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
	  $sortable_columns = array(
	    // 'category' => array( 'Category', false ),
	    // 'fName' => array( 'FName', false )
	  );

	  return $sortable_columns;
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {
	  /**
		* REQUIRED. Now we need to define our column headers. This includes a complete
		* array of columns to be displayed (slugs & titles), a list of columns
		* to keep hidden, and a list of columns that are sortable. Each of these
		* can be defined in another method (as we've done here) before being
		* used to build the value for our _column_headers property.
	  */
	  $columns = $this->get_columns();
	  $hidden = array();
	  $sortable = $this->get_sortable_columns();


	  /**
		* REQUIRED. Finally, we build an array to be used by the class for column 
		* headers. The $this->_column_headers property takes an array which contains
		* 3 other arrays. One for all columns, one for hidden columns, and one
		* for sortable columns.
	  */
	  $this->_column_headers = array($columns, $hidden, $sortable);

	  /** Process bulk action */
	  $this->process_bulk_action();

	  $per_page     = $this->get_items_per_page( 'pledgers_per_page', 5 );
	  $current_page = $this->get_pagenum();
	  $total_items  = self::record_count();

	  $this->set_pagination_args( [
	    'total_items' => $total_items, //WE have to calculate the total number of items
	    'per_page'    => $per_page //WE have to determine how many items to show on a page
	  ] );


	  $this->items = self::get_pledgers( $per_page, $current_page );
	  //echo print_r($this->items);
	}

	function handle_custom_action() {
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
        	$this->delete_pledger($_GET["pledgers_delete"]);
            echo '<h1>Deleted item ' .$_GET["pledgers_delete"]. '</h1>';
        }
		else if( 'edit'===$this->current_action() ) {
            echo '<h1>Enable editing of item '. $_GET["pledgers_edit"]. '</h1>';
        }
    }
}

$pledgers_table = new Pledgers_List_Table();
$pledgers_table->handle_custom_action();
?>
<h1>Pledgers Management</h1>

<div class="wrap">

	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<form method="post">
						<?php
						$pledgers_table->prepare_items();
						$pledgers_table->display(); 
						?>
					</form>
				</div>
			</div>
		</div>
		<br class="clear">
	</div>
</div>
