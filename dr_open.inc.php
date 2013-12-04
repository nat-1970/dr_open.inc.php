
<?php
if(!defined('OSTCLIENTINC')) die('Access Denied!');
$info=array();
if($thisclient && $thisclient->isValid()) {
    $info=array('order'=>$thisclient->getOrder(),
                'driverName'=>$thisclient->getDriverName(),
                'passengerName'=>$thisclient->getPassengerName(),
                'phone_ext'=>$thisclient->getPhoneExt());
}

$info=($_POST && $errors)?Format::htmlchars($_POST):$info;
?>
<h1>Open a Driver Complaint</h1>
<p>Please fill in the form below to open a new complaint.</p>
<form id="complaintForm" method="post" action="driver_complaint.php" enctype="multipart/form-data">
  <?php csrf_token(); ?>
  <input type="hidden" name="a" value="open">
 
  <table width="800" cellpadding="1" cellspacing="0" border="0">
  	<tr>
        <th class="required" width="160">Order Number:</th>
        <td>
            <?php
            if($thisclient && $thisclient->isValid()) {
                echo $thisclient->getOrder();
            } else { ?>
                <input id="order_id" type="number" name="order_id" maxlength="4" size="4" value="<?php echo $info['order_id']; ?>">
                <font class="error">*&nbsp;<?php echo $errors['order_id']; ?></font>
           
            
            <?php
            } ?>
        </td>
    </tr>
    <tr>
        <th class="required" width="160">Diver Name/Number:</th>
        <td>
            <?php
            if($thisclient && $thisclient->isValid()) {
                echo $thisclient->getDriverName();
            } else { ?>
                <input id="driver_name" type="text" name="driver_name" size="40" value="<?php echo $info['driver_name']; ?>">
                <font class="error">*&nbsp;<?php echo $errors['driver_name']; ?></font>
            <?php
            } ?>
        </td>
    </tr>
    <tr>
        <th class="required" width="160">Passenger Name:</th>
        <td>
            <?php
            	if($thisclient && $thisclient->isValid()) { 
                	echo $thisclient->getPassengerName();
            	} else { ?>
                	<input id="PassengerName1" type="text" name="PassengerName" size="40" value="<?php echo $info['PassengerName1']; ?>">
                	<font class="error">*&nbsp;<?php echo $info['PassengerName1']; ?></font>
            <?php
            } ?>
        </td>
    </tr>
    <tr>
        <td class="required">Description of the incident:</td>
        <td>
            <div><em>Please provide as much detail as possible so we can best assist you.</em> <font class="error">*&nbsp;
            <?php echo $errors['message']; ?></font></div>
            <textarea id="message" cols="60" rows="8" name="message"><?php echo $info['message']; ?></textarea>
        </td>
    </tr>
	<tr>
        <th  width="50">Was the issue resolved</br> in a car with driver:</th>
        <td>
            <input type="checkbox" name="IssueResolved" value="Issue"> 
        </td>
    </tr>
	

    <?php if(($cfg->allowOnlineAttachments() && !$cfg->allowAttachmentsOnlogin())
            || ($cfg->allowAttachmentsOnlogin() && ($thisclient && $thisclient->isValid()))) { ?>
    
    <tr><td colspan=2>&nbsp;</td></tr>
    <?php } ?>
    <?php
    if($cfg->allowPriorityChange() && ($priorities=Priority::getPriorities())) { ?>
    <tr>
        <td>Ticket Priority:</td>
        <td>
            <select id="priority" name="priorityId">
                <?php
                    if(!$info['priorityId'])
                        $info['priorityId'] = $cfg->getDefaultPriorityId(); //System default.
                    foreach($priorities as $id =>$name) {
                        echo sprintf('<option value="%d" %s>%s</option>',
                                        $id, ($info['priorityId']==$id)?'selected="selected"':'', $name);
                        
                    }
                ?>
            </select>
            <font class="error">&nbsp;<?php echo $errors['priorityId']; ?></font>
        </td>
    </tr>
    <?php
    }
    ?>
    <?php
    if($cfg && $cfg->isCaptchaEnabled() && (!$thisclient || !$thisclient->isValid())) {
        if($_POST && $errors && !$errors['captcha'])
            $errors['captcha']='Please re-enter the text again';
        ?>
    <tr class="captchaRow">
        <td class="required">CAPTCHA Text:</td>
        <td>
            <span class="captcha"><img src="captcha.php" border="0" align="left"></span>
            &nbsp;&nbsp;
            <input id="captcha" type="text" name="captcha" size="6">
            <em>Enter the text shown on the image.</em>
            <font class="error">*&nbsp;<?php echo $errors['captcha']; ?></font>
        </td>
    </tr>
    <?php
    } ?>
    <tr><td colspan=2>&nbsp;</td></tr>
  </table>
  
  
  <p style="padding-left:150px;">
        <input type="submit" value="Create Complaint">
        <input type="reset" value="Reset">
        <input type="button" value="Cancel" onClick='window.location.href="index.php"'>
  </p>
</form>