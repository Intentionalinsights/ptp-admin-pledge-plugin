<?php
/**
* Description: Manages the Pro Truth Pledge form data
* Version: 1.0
* Author: Pro-Truth Pledge developers
* Author URI: protruthpledge.org
*/

class PTPPledges
{
    /**
     * @var $wpdb
     */
    private $wpdb;

    /**
     * @var string
     */
    private $pledgeTable;

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
    public $linkText3;
    public $linkUrl3;
    public $edited;

    public function __construct($id)
    {
        global $wpdb;

        $this->wpdb        = $wpdb;
        $this->pledgeTable = $wpdb->prefix . 'ptp_pledges';

        $details = $this->getPledgeData($id);
        $this->populatePledgeProperties($details);
    }

    public function updateEntry($pledgeId, $key, $updatedData)
    {
        $data = array(
            'show'        => isset($updatedData['show']),
            'prominent'   => ((bool) $updatedData["prominent"]) ? "1" : "0",

            'category'  => strip_tags($updatedData["category"]),
            'fName'     => strip_tags($updatedData["fName"]),
            'lName'     => strip_tags($updatedData["lName"]),
            'groupName' => strip_tags($updatedData["groupName"]),
            'email'     => strip_tags($updatedData["email"]),

            'volunteer'   => isset($updatedData['volunteer']),
            'directory'   => isset($updatedData['directory']),
            'emailList'   => isset($updatedData['emailList']),
            'emailAlerts' => isset($updatedData['emailAlerts']),
            'repNudge'    => isset($updatedData['repNudge']),

            'address1'   => strip_tags($updatedData["address1"]),
            'address2'   => strip_tags($updatedData["address2"]),
            'city'       => strip_tags($updatedData["city"]),
            'region'     => strip_tags($updatedData["region"]),
            'zip'        => strip_tags($updatedData["zip"]),
            'country'    => strip_tags($updatedData["country"]),

            'phone'      => strip_tags($updatedData["phone"]),
            'textAlerts' => isset($updatedData['textAlerts']),

            'orgs'       => strip_tags($updatedData["orgs"]),

            'description' => strip_tags($updatedData["description"]),
            'imageUrl'    => strip_tags($updatedData["imageUrl"]),
        );

        if (isset($updatedData['linkText1']) && $updatedData['linkUrl1'] != "https://www.facebook.com/") {
            $data['linkText1'] = strip_tags($updatedData["linkText1"]);
            $data['linkUrl1']  = strip_tags($updatedData["linkUrl1"]);
        }

        if (isset($updatedData['linkText2']) && $updatedData['linkUrl2'] != "https://twitter.com/") {
            $data['linkText2'] = strip_tags($updatedData["linkText2"]);
            $data['linkUrl2']  = strip_tags($updatedData["linkUrl2"]);
        }

        if (isset($updatedData['linkText3']) && $updatedData['linkUrl3'] != "http://") {
            $data['linkText3'] = strip_tags($updatedData["linkText3"]);
            $data['linkUrl3']  = strip_tags($updatedData["linkUrl3"]);
        }

        $this->wpdb->update(
            $this->pledgeTable,
            $data,
            array(
                'pledgeId' => $pledgeId,
                'key'      => $key
            )
        );

        $updatedDetails = $this->getPledgeData($pledgeId);
        $this->populatePledgeProperties($updatedDetails);

        return "<h1>Success! Entry " . $pledgeId . " was updated!</h1>";
    }

    private function getPledgeData($id)
    {
        $sql  = "SELECT * FROM {$this->pledgeTable} WHERE pledgeId = " . $id;
        $sql .= " LIMIT 1";

        $result = $this->wpdb->get_results($sql, 'ARRAY_A');

        //only ever select 1 record so look at first entry
        return $result[0];
    }

    private function populatePledgeProperties($details)
    {
        $this->pledgeId    = $details['pledgeId'];
        $this->key         = $details['key'];
        $this->category    = $details['category'];
        $this->show        = $details['show'];
        $this->prominent   = $details['prominent'];
        $this->fName       = $details['fName'];
        $this->lName       = $details['lName'];
        $this->groupName   = $details['groupName'];
        $this->email       = $details['email'];
        $this->volunteer   = $details['volunteer'];
        $this->emailList   = $details['emailList'];
        $this->directory   = $details['directory'];
        $this->emailAlerts = $details['emailAlerts'];
        $this->textAlerts  = $details['textAlerts'];
        $this->repNudge    = $details['repNudge'];
        $this->address1    = $details['address1'];
        $this->address2    = $details['address2'];
        $this->city        = $details['city'];
        $this->region      = $details['region'];
        $this->zip         = $details['zip'];
        $this->country     = $details['country'];
        $this->orgs        = $details['orgs'];
        $this->phone       = $details['phone'];
        $this->description = $details['description'];
        $this->imageUrl    = $details['imageUrl'];
        $this->linkText1   = $details['linkText1'];
        $this->linkUrl1    = $details['linkUrl1'];
        $this->linkText2   = $details['linkText2'];
        $this->linkUrl2    = $details['linkUrl2'];
        $this->linkText3   = $details['linkText3'];
        $this->linkUrl3    = $details['linkUrl3'];
        $this->edited      = $details['edited'];
    }

}

$pledgeId = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['pledgers_edit']);

if (isset($_GET['pledgers_edit'])) {
    $pledger_data = new PTPPledges($pledgeId);

    echo '<h1>Editing pledge data for id: ' . $pledgeId . '</h1>';
    echo '<h2>Last update time: ' . $pledger_data->edited . '</h2>';

} else {

    echo '<h1>Please use the PTP Manage table list view to access a record to edit.</h1>';

}

//check if form was submitted
if (isset($_POST['SubmitButton'])) {
    $message = $pledger_data->updateEntry($pledger_data->pledgeId, $pledger_data->key, $_POST);
}

?>
<div class="wrap">
    <form action="" method="post">
    <?php echo $message; ?>

        <div class="row" style="border-left: 2px solid #999; padding-left: 15px; padding-top: 15px;">
            <div class="form-group">
                <div class="form-check form-check-inline">
                    <label class="form-check-label" for="show">
                        <input class="form-check-input" type="checkbox" name="show" id="show" value="show" <?php echo ($pledger_data->show == 1) ? 'checked': ''; ?> />
                        Show this record to the public
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="form-check form-check-inline">
                    <label for="prominent">
                        <input type="checkbox" name="prominent" id="prominent" value="1" <?php echo ($pledger_data->prominent) ? "checked" : ""; ?> >
                        Prominent Pledge Taker
                    </label>
                    (Pledge taker will be displayed with special emphasis on the public figures page)
                </div>
            </div>
        </div>

        <hr>

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

        <div class="row">
            <div class="form-group col-sm-6" >
                <label for="groupName">Group Name</label>
                <input type="text" name="groupName" id="groupName" class="form-control" autocomplete="groupName"
                value="<?php echo htmlspecialchars($pledger_data->groupName); ?>"
                />
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
                    <input class="form-check-input" type="checkbox" name="directory" value="directory" <?php echo ($pledger_data->directory == 1) ? 'checked' : ''; ?> />
                    I want to be in the public directory of signers
                </label> (We will only post your name and social media links you provide)
            </div>
        </div>

        <div class="form-group">
            <div class="form-check form-check-inline">
                <label class="form-check-label" style="display: inline;">
                    <input class="form-check-input" type="checkbox" name="volunteer" value="volunteer" <?php echo ($pledger_data->volunteer == 1) ? 'checked' : ''; ?> />
                    I want to help with the Pro-Truth Pledge
                </label>
            </div>
        </div>

        <div class="form-group">
            <label>Notifications</label>
            <div class="form-check form-check-inline">
                <label class="form-check-label" style="display: inline;">
                <input class="form-check-input" type="checkbox" name="emailList" value="emailList" <?php echo ($pledger_data->emailList == 1) ? 'checked': ''; ?> /> Infrequent Email Updates
                </label>
                (important to motivate public figures to sign the pledge)
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label"  style="display: inline;">
                <input class="form-check-input" type="checkbox" name="emailAlerts" value="emailAlerts" <?php echo ($pledger_data->emailAlerts == 1) ? 'checked' : ''; ?> /> Email Action Alerts
                </label>(
                important to ensure we can hold public figures accountable)
            </div>
        </div>

        <div class="form-group">
            <label>Are you?</label>
            <div class="form-check form-check-inline">
                <label class="form-check-label" style="display: inline;">
                <input class="form-check-input" type="radio" name="category" value="Public" <?php echo ($pledger_data->category) == 'Public' ? 'checked' : ''; ?> /> General Public
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label" style="display: inline;">
                <input class="form-check-input" type="radio" name="category" value="Figure" <?php echo ($pledger_data->category == 'Figure') ? 'checked' : ''; ?> /> Public Figure
                </label>
                or staff
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label"  style="display: inline;">
                <input class="form-check-input" type="radio" name="category" value="Official" <?php echo ($pledger_data->category == 'Official') ? 'checked' : ''; ?> /> Elected or Appointed Official or Candidate
                </label>
                or staff
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label"  style="display: inline;">
                <input class="form-check-input" type="radio" name="category" value="Group" <?php echo ($pledger_data->category == 'Group') ? 'checked' : ''; ?> /> Organization or Group
                </label>
            </div>
        </div>

        <div class="form-group">
            <textarea name="description" id="description" placeholder="I signed the pledge because..." class="form-control"><?php echo htmlspecialchars($pledger_data->description); ?></textarea>
        </div>
        <div class="form-group">
            <div class="form-check form-check-inline">
                <label class="form-check-label" style="display: inline;">
                    <input class="form-check-input" type="checkbox" name="repNudge" value="repNudge" <?php echo ($pledger_data->repNudge == 1) ? 'checked' : ''; ?> />
                    I call on all of my elected representatives to take the Pro-Truth Pledge
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="address1">Address</label>
            <input name="address1"  id="address1" class="form-control" value="<?php echo htmlspecialchars($pledger_data->address1); ?>" />
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="city">City</label>
                <input name="city"  id="city" class="form-control" value="<?php echo htmlspecialchars($pledger_data->city); ?>">
            </div>
            <div class="form-group col-sm-6">
                <label for="region">State/ Region</label>
                <input name="region"  id="region" class="form-control" value="<?php echo htmlspecialchars($pledger_data->region); ?>">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="zip">Zip/ Postal Code</label>
                <input name="zip"  id="zip" class="form-control" value="<?php echo htmlspecialchars($pledger_data->zip); ?>">
            </div>
            <div class="form-group col-sm-6">
                <label for="country">Country</label>
                <input name="country"  id="country" class="form-control" value="<?php echo htmlspecialchars($pledger_data->country); ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input name="phone" type="tel" id="phone" class="form-control" value="<?php echo htmlspecialchars($pledger_data->phone); ?>">
        </div>
        <div class="form-group">
            <div class="form-check form-check-inline">
                <label class="form-check-label" style="display: inline;">
                    <input class="form-check-input" type="checkbox" name="textAlerts" value="textAlerts" <?php echo ($pledger_data->textAlerts == 1) ? 'checked': ''; ?>>
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
