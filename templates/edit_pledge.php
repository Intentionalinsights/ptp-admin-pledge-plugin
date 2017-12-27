<?php
/**
* Description: Manages the Pro Truth Pledge form data
* Version: 1.0
* Author: Pro-Truth Pledge developers
* Author URI: protruthpledge.org
*/

class PTPPledges {

    public $pledgeId;
    public $key;
    public $show;
    public $category;
    public $step;
    public $fName;
    public $lName;
    public $groupName;
    public $email;
    public $volunteer;
    public $emailList;
    public $directory;
    public $emailAlerts;
    public $textAlerts;
    public $repNudge;
    public $address1;
    public $address2;
    public $city;
    public $region;
    public $zip;
    public $country;
    public $orgs;
    public $phone;
    public $description;
    public $imageUrl;
    public $linkText1;
    public $linkUrl1;
    public $linkText2;
    public $linkUrl2;
    public $linkText;
    public $linkUrl3;
    public $edited;

    public function __construct($id)
    {
        $result = $this->get_pledge($id);
        
        $this->pledgeId = $result['pledgeId'];
        $this->key = $result['key'];
        $this->show = $result['show'];
        $this->category = $result['category'];
        $this->fName = $result['fName'];
        $this->lName = $result['lName'];
        $this->groupName =  $result['groupName'];
        $this->email = $result['email'];
        $this->volunteer = $result['volunteer'];
        $this->emailList = $result['emailList'];
        $this->directory =  $result['directory'];
        $this->emailAlerts = $result['emailAlerts'];
        $this->textAlerts = $result['textAlerts'];
        $this->repNudge = $result['repNudge'];
        $this->address1 = $result['address1'];
        $this->address2 = $result['address2'];
        $this->city = $result['city'];
        $this->region = $result['region'];
        $this->zip = $result['zip'];
        $this->country = $result['country'];
        $this->orgs = $result['orgs'];
        $this->phone = $result['phone'];
        $this->description = $result['description'];        
        $this->imageUrl = $result['imageUrl'];
        $this->linkText1 = $result['linkText1'];
        $this->linkUrl1 = $result['linkUrl1'];
        $this->linkText2 = $result['linkText2'];
        $this->linkUrl2 = $result['linkUrl2'];
        $this->linkText3 = $result['linkText3'];
        $this->linkUrl3 = $result['linkUrl3'];

        $this->edited = $result['edited'];
    }

    private function get_pledge( $id ) {
      global $wpdb;

      $sql = "SELECT * FROM {$wpdb->prefix}ptp_pledges WHERE pledgeId=" . $id;
      $sql .= " LIMIT 1";

      $result = $wpdb->get_results( $sql, 'ARRAY_A' );

      //only ever select 1 record so look at first entry
      //echo print_r($result[0]);
      return $result[0];
    }

    public function update_entry($pledgeId, $key) {
        global $wpdb;
        $pledgeTable = $wpdb->prefix . "ptp_pledges"; 

        $data = array( 
        'category' => strip_tags($_POST["category"], ""),
        'fName' => strip_tags($_POST["fName"], ""),
        'lName' => strip_tags($_POST["lName"], ""),
        'email' => strip_tags($_POST["email"], ""),

        'show' => isset($_POST['show']),
        'volunteer' => isset($_POST['volunteer']),
        'directory' => isset($_POST['directory']),
        'emailList' => isset($_POST['emailList']),
        'emailAlerts' => isset($_POST['emailAlerts']),

        'repNudge' => isset($_POST['repNudge']),
        'address1' => strip_tags($_POST["address1"], ""),
        'address2' => strip_tags($_POST["address2"], ""),
        'city' => strip_tags($_POST["city"], ""),
        'region' => strip_tags($_POST["region"], ""),
        'zip' => strip_tags($_POST["zip"], ""),
        'country' => strip_tags($_POST["country"], ""),
        'phone' => strip_tags($_POST["phone"], ""),
        'textAlerts' => isset($_POST['textAlerts']),
        'orgs' => strip_tags($_POST["orgs"], ""),

        'description' => strip_tags($_POST["description"], ""),
        'imageUrl' => strip_tags($_POST["imageUrl"], ""),
        );

        if(isset($_POST['linkText1']) && $_POST['linkUrl1'] != "https://www.facebook.com/"){
            $data += ['linkText1' => strip_tags($_POST["linkText1"], "")];
            $data += ['linkUrl1' => strip_tags($_POST["linkUrl1"], "")];
        }
        if(isset($_POST['linkText2']) && $_POST['linkUrl2'] != "https://twitter.com/"){
            $data += ['linkText2' => strip_tags($_POST["linkText2"], "")];
            $data += ['linkUrl2' => strip_tags($_POST["linkUrl2"], "")];
        }
        if(isset($_POST['linkText3']) && $_POST['linkUrl3'] != "http://"){
            $data += ['linkText3' => strip_tags($_POST["linkText3"], "")];
            $data += ['linkUrl3' => strip_tags($_POST["linkUrl3"], "")];
        }

        $wpdb->update( 
            $pledgeTable, 
            $data,
            array( 
                'pledgeId' => $pledgeId, 
                'key' => $key
            )
        );

        return "<h1>Success! Entry ".$pledgeId." was updated! Please refresh page to see latest data.</h1>";
    }

}

$pledgeId = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['pledgers_edit']);

if (isset($_GET['pledgers_edit'])) {
    echo '<h1>Editing pledge data for id: '.$pledgeId.'</h1>';
    $pledger_data = new PTPPledges($pledgeId);
    echo '<h2>Last update time: '.$pledger_data->edited.'</h2>';
} else {
    echo '<h1>Please use the PTP Manage table list view to access a record to edit.</h1>';
}
   
if(isset($_POST['SubmitButton'])){ //check if form was submitted
    $message = $pledger_data->update_entry($pledger_data->pledgeId, $pledger_data->key);
}

?>
<div class="wrap">
    <form action="" method="post">
    <?php echo $message; ?>
        <div class="row">
            <div class="form-group col-sm-6" >
                <label for="fName">First Name</label>
                <input type="text" name="fName" id="fName" class="form-control" autocomplete="fname" 
                value="<?php echo htmlspecialchars($pledger_data->fName); ?>" 
                />
            </div>
              
            <div class="form-group col-sm-6" >
                <label for="lName">Last Name</label>
                <input type="text" name="lName" id="lName" class="form-control" autocomplete="lname"
                value="<?php echo htmlspecialchars($pledger_data->lName); ?>" 
                />
            </div>
        </div>

        <div class="form-group">
            <div class="form-check form-check-inline">
                <label class="form-check-label" style="display: inline;">
                    <input class="form-check-input" type="checkbox" name="show" value="show" <?php echo ($pledger_data->show == 1? htmlspecialchars(checked): ''); ?> />
                    Show this record to the public.
                </label>
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email (will be kept private) </label>
            <input type="email" name="email" id="email" class="form-control" required autocomplete="email"
            value="<?php echo htmlspecialchars($pledger_data->email); ?>" 
            />
        </div>
        
        <div class="form-group">
            <div class="form-check form-check-inline">
                <label class="form-check-label" style="display: inline;">
                    <input class="form-check-input" type="checkbox" name="directory" value="directory" <?php echo ($pledger_data->directory == 1? htmlspecialchars(checked): ''); ?> />
                    I want to be in the public directory of signers
                </label> (We will only post your name and social media links you provide)
            </div>
        </div>  
        
        <div class="form-group">
            <div class="form-check form-check-inline">
                <label class="form-check-label" style="display: inline;">
                    <input class="form-check-input" type="checkbox" name="volunteer" value="volunteer" <?php echo ($pledger_data->volunteer == 1? htmlspecialchars(checked): ''); ?> />
                    I want to help with the Pro-Truth Pledge
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label>Notifications</label>
            <div class="form-check form-check-inline">
                <label class="form-check-label" style="display: inline;">
                <input class="form-check-input" type="checkbox" name="emailList" value="emailList" <?php echo ($pledger_data->emailList == 1? htmlspecialchars(checked): ''); ?> /> Infrequent Email Updates
                </label>
                (important to motivate public figures to sign the pledge)
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label"  style="display: inline;">
                <input class="form-check-input" type="checkbox" name="emailAlerts" value="emailAlerts" <?php echo ($pledger_data->emailAlerts == 1? htmlspecialchars(checked): ''); ?> /> Email Action Alerts
                </label>(
                important to ensure we can hold public figures accountable)
            </div>
        </div>
        
        <div class="form-group">
            <label>Are you?</label>
            <div class="form-check form-check-inline">
                <label class="form-check-label" style="display: inline;">
                <input class="form-check-input" type="radio" name="category" value="Public" <?php echo ($pledger_data->category == 'Public'? htmlspecialchars(checked): ''); ?> />General Public
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label" style="display: inline;">
                <input class="form-check-input" type="radio" name="category" value="Figure" <?php echo ($pledger_data->category == 'Figure'? htmlspecialchars(checked): ''); ?> /> Public Figure
                </label>
                or staff
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label"  style="display: inline;">
                <input class="form-check-input" type="radio" name="category" value="Official" <?php echo ($pledger_data->category == 'Official'? htmlspecialchars(checked): ''); ?> /> Elected or Appointed Official or Candidate
                </label>
                or staff
            </div>          
            <div class="form-check form-check-inline">
                <label class="form-check-label"  style="display: inline;">
                <input class="form-check-input" type="radio" name="category" value="Group" <?php echo ($pledger_data->category == 'Group'? htmlspecialchars(checked): ''); ?> /> Organization or Group
                </label>
            </div>
        </div>

        <div class="form-group">
            <textarea name="description" id="description" placeholder="I signed the pledge because..." class="form-control"><?php echo $pledger_data->description; ?></textarea>
        </div>
        <div class="form-group">
            <div class="form-check form-check-inline">
                <label class="form-check-label" style="display: inline;">
                    <input class="form-check-input" type="checkbox" name="repNudge" value="repNudge" <?php echo ($pledger_data->repNudge == 1? htmlspecialchars(checked): ''); ?> />
                    I call on all of my elected representatives to take the Pro-Truth Pledge
                </label>
            </div>
        </div>  
        <div class="form-group">
            <label for="address1">Address</label>
            <input name="address1"  id="address" class="form-control" value="<?php echo htmlspecialchars($pledger_data->address1); ?>" />
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="city">City</label>
                <input name="city"  id="city" class="form-control" value="<?php echo htmlspecialchars($pledger_data->city); ?>">
            </div>
            <div class="form-group col-sm-6">
                <label for="region">State/ Region</label>
                <input name="region"  id="state" class="form-control" value="<?php echo htmlspecialchars($pledger_data->region); ?>">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="zip">Zip/ Postal Code</label>
                <input name="zip"  id="zip" class="form-control" value="<?php echo htmlspecialchars($pledger_data->zip); ?>">
            </div>
            <div class="form-group col-sm-6">
                <label for="country">Country</label>
                <input name="country"  id="country" class="form-control" value="USA" value="<?php echo htmlspecialchars($pledger_data->country); ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="phone">Phone Number</label>will not be made public - we need it for action alerts and contacting you if you wish to help with the pledge, or if we need to clarify your information
            <input name="phone" type="tel" id="phone" class="form-control" value="<?php echo htmlspecialchars($pledger_data->phone); ?>">
        </div>
        <div class="form-group">
            <div class="form-check form-check-inline">
                <label class="form-check-label" style="display: inline;">
                    <input class="form-check-input" type="checkbox" name="textAlerts" value="textAlerts" <?php echo ($pledger_data->textAlerts == 1? htmlspecialchars(checked): ''); ?> />
                    Send me text action alerts
                </label>
            </div>
        </div>

        <div class="form-group">
            <label for="imageUrl">Image Address</label>
            <input name="imageUrl"  id="imageUrl" class="form-control" value="<?php echo htmlspecialchars($pledger_data->imageUrl); ?>">
        </div>

        <div class="row">
            <div class="form-group col-sm-6">
                <label for="linkText1">Text 1</label>
                <input name="linkText1" id="linkText1" class="form-control" value="<?php echo htmlspecialchars($pledger_data->linkText1); ?>">
            </div>
            <div class="form-group col-sm-6">
                <label for="linkUrl1">link 1</label>
                <input name="linkUrl1" id="linkUrl1"  class="form-control" value="<?php echo htmlspecialchars($pledger_data->linkUrl1); ?>">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="linkText2">Text 2</label>
                <input name="linkText2" id="linkText2" class="form-control" value="<?php echo htmlspecialchars($pledger_data->linkText2); ?>">
            </div>
            <div class="form-group col-sm-6">
                <label for="linkUrl2">link 2</label>
                <input name="linkUrl2" id="linkUrl2" class="form-control" value="<?php echo htmlspecialchars($pledger_data->linkUrl2); ?>">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="linkText3">Text 3</label>
                <input name="linkText3" id="linkText3" class="form-control" value="<?php echo htmlspecialchars($pledger_data->linkText3); ?>">
            </div>
            <div class="form-group col-sm-6">
                <label for="linkUrl3">link 3</label>
                <input name="linkUrl3" id="linkUrl3" class="form-control" value="<?php echo htmlspecialchars($pledger_data->linkUrl3); ?>">
            </div>
        </div>
        
        <div class="row">
            <div class="form-group col-sm-12">
                <input type="submit" name="SubmitButton" class="btn btn-primary"/>
            </div>
        </div>

    </form>  

</div>